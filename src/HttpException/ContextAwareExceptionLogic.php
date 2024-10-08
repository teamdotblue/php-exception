<?php

declare(strict_types=1);

namespace TeamBlue\Exception\HttpException;

use EventEngine\JsonSchema\JsonSchemaAwareRecord;
use LogicException;
use TeamBlue\Exception\DefaultJsonSchemaException;

use function array_combine;
use function array_keys;
use function array_map;
use function array_values;
use function is_a;
use function sprintf;
use function strtr;

/**
 * @property JsonSchemaAwareRecord $context
 * @property string $detail
 */
trait ContextAwareExceptionLogic
{
    use DefaultJsonSchemaException {
        buildPropTypeMap as defaultJsonSchemaExceptionBuildPropTypeMap;
        init as parentInit;
    }

    /** @param array<string, mixed> $context */
    public static function fromContextArray(array $context): self
    {
        return self::fromArray(['context' => $context]);
    }

    /** @return array<string, array{0: string, 1: bool, 2: bool}> */
    private static function buildPropTypeMap(): array
    {
        $propertyTypeMap = self::defaultJsonSchemaExceptionBuildPropTypeMap();

        if (! isset($propertyTypeMap['context'])) {
            throw new LogicException(
                sprintf(
                    'No context found for ContextAwareException %s',
                    static::class,
                ),
            );
        }

        $contextType = $propertyTypeMap['context'];

        if (! is_a($contextType[0], JsonSchemaAwareRecord::class, true)) {
            throw new LogicException(
                sprintf(
                    'Context for ContextAwareException %s must implement JsonSchemaAwareRecord',
                    static::class,
                ),
            );
        }

        return $propertyTypeMap;
    }

    private function init(): void
    {
        $this->renderTemplate();
        $this->parentInit();
    }

    private function renderTemplate(): void
    {
        $contextWithCurlyBraces = array_combine(
            array_map(
                static fn (string $key): string => sprintf('{%s}', $key),
                array_keys($this->context->toArray()),
            ),
            array_values($this->context->toArray()),
        );

        $this->detail = strtr(static::template(), $contextWithCurlyBraces);
    }

    abstract protected static function template(): string;

    public function context(): JsonSchemaAwareRecord
    {
        return $this->context;
    }
}
