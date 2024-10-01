<?php

declare(strict_types=1);

namespace ADS\Exception\SchemaFactory;

use ADS\Exception\HttpException\ContextExceptionsExtractor;
use ADS\Exception\Normalizer\ErrorNormalizer;
use ADS\JsonImmutableObjects\Polymorphism\Discriminator;
use ApiPlatform\JsonSchema\Schema;
use ApiPlatform\JsonSchema\SchemaFactoryAwareInterface;
use ApiPlatform\JsonSchema\SchemaFactoryInterface;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\Metadata\Util\ResourceClassInfoTrait;
use EventEngine\JsonSchema\JsonSchemaAwareRecord;
use EventEngine\JsonSchema\Type\ObjectType;
use Symfony\Component\DependencyInjection\Attribute\AsDecorator;
use Symfony\Component\DependencyInjection\Attribute\AutowireDecorated;

#[AsDecorator('api_platform.json_schema.schema_factory')]
class ContextExceptionsSchemaFactory implements SchemaFactoryInterface, SchemaFactoryAwareInterface
{
    use ResourceClassInfoTrait;

    private const UUID_SCHEMA = [
        'type' => 'string',
        'format' => 'uuid',
    ];

    private const HEX32_SCHEMA = [
        'type' => 'string',
        'pattern' => '^[0-9a-fA-F]{32}$',
    ];

    private const HEX16_SCHEMA = [
        'type' => 'string',
        'pattern' => '^[0-9a-fA-F]{16}$',
    ];

    private const DEFAULT_CONTEXT_SCHEMA = [
        'type' => 'object',
        'properties' => [],
    ];

    public function __construct(
        #[AutowireDecorated]
        private SchemaFactoryInterface $schemaFactory,
        private readonly ContextExceptionsExtractor $contextExceptionsExtractor,
    ) {
        if (! ($this->schemaFactory instanceof SchemaFactoryAwareInterface)) {
            return;
        }

        $this->schemaFactory->setSchemaFactory($this);
    }

    /**
     * @param class-string<JsonSchemaAwareRecord|Discriminator> $className
     * @param array<string, mixed>|null                         $serializerContext
     * @param Schema<mixed>|null                                $schema
     *
     * @return Schema<mixed>
     *
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
     */
    public function buildSchema(
        string $className,
        string $format = 'json',
        string $type = Schema::TYPE_OUTPUT,
        Operation|null $operation = null,
        Schema|null $schema = null,
        array|null $serializerContext = null,
        bool $forceCollection = false,
    ): Schema {
        $schema = $this->schemaFactory->buildSchema(
            $className,
            $format,
            $type,
            $operation,
            $schema,
            $serializerContext,
            $forceCollection,
        );

        $messageClass = $operation?->getInput()['class'] ?? null;

        if ($messageClass === null || $type !== Schema::TYPE_OUTPUT) {
            return $schema;
        }

        $definitions = $schema->getDefinitions();

        foreach ($this->contextExceptionsExtractor->extract($messageClass) as $exceptionClass) {
            $schemaName = $exceptionClass::schemaName();

            if (isset($definitions[$schemaName])) {
                continue;
            }

            $type = ErrorNormalizer::typeFromException($exceptionClass);
            $title = $exceptionClass::title();
            $statusCode = $exceptionClass::statusCode();
            $contextClass = $exceptionClass::contextClass();
            /** @var ObjectType|null $contextSchema */
            $contextSchema = $contextClass ? $contextClass::__schema() : null;
            $context = $contextSchema?->asNullable()?->toArray() ?? self::DEFAULT_CONTEXT_SCHEMA;
            $definitions[$schemaName] = [
                'type' => 'object',
                'properties' => [
                    'type' => [
                        'type' => 'string',
                        'enum' => [$type],
                        'example' => $type,
                    ],
                    'title' => [
                        'type' => 'string',
                        'enum' => [$title],
                        'example' => $title,
                    ],
                    'detail' => [
                        'type' => 'string',
                        'example' => $exceptionClass::template(),
                    ],
                    'status' => [
                        'type' => 'integer',
                        'enum' => [$statusCode],
                        'example' => $statusCode,
                    ],
                    'metadata' => [
                        'type' => 'object',
                        'properties' => [
                            'request_id' => self::UUID_SCHEMA,
                            'sentry_trace' => [
                                'type' => 'object',
                                'properties' => [
                                    'trace_id' => self::HEX32_SCHEMA,
                                    'span_id' => self::HEX16_SCHEMA,
                                    'parent_span_id' => self::HEX16_SCHEMA,
                                ],
                                'required' => [
                                    'trace_id',
                                    'span_id',
                                ],
                            ],
                        ],
                        'required' => ['request_id', 'sentry_trace'],
                    ],
                    'context' => $context,
                ],
                'required' => [
                    'type',
                    'title',
                    'detail',
                    'status',
                    'metadata',
                    'context',
                ],
            ];
        }

        return $schema;
    }

    public function setSchemaFactory(SchemaFactoryInterface $schemaFactory): void
    {
        if (! ($this->schemaFactory instanceof SchemaFactoryAwareInterface)) {
            return;
        }

        $this->schemaFactory->setSchemaFactory($schemaFactory);
    }
}
