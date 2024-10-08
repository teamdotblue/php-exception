<?php

declare(strict_types=1);

namespace ADS\Exception\SchemaFactory;

use ADS\Exception\Extractor\ExceptionExtractor;
use ADS\JsonImmutableObjects\Polymorphism\Discriminator;
use ApiPlatform\JsonSchema\Schema;
use ApiPlatform\JsonSchema\SchemaFactoryAwareInterface;
use ApiPlatform\JsonSchema\SchemaFactoryInterface;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\Metadata\Util\ResourceClassInfoTrait;
use EventEngine\JsonSchema\JsonSchemaAwareRecord;
use Symfony\Component\DependencyInjection\Attribute\AsDecorator;
use Symfony\Component\DependencyInjection\Attribute\AutowireDecorated;

#[AsDecorator('api_platform.json_schema.schema_factory')]
class ContextExceptionsSchemaFactory implements SchemaFactoryInterface, SchemaFactoryAwareInterface
{
    use ResourceClassInfoTrait;

    public function __construct(
        #[AutowireDecorated]
        private SchemaFactoryInterface $schemaFactory,
        private readonly ExceptionExtractor $contextExceptionsExtractor,
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
            $schemaName = $exceptionClass::__type();

            if (isset($definitions[$schemaName])) {
                continue;
            }

            $definitions[$schemaName] = $exceptionClass::__schema()->toArray();
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
