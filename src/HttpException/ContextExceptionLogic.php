<?php

declare(strict_types=1);

namespace ADS\Exception\HttpException;

use ADS\Util\StringUtil;
use EventEngine\JsonSchema\JsonSchemaAwareRecord;
use LogicException;
use ReflectionClass;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

use function array_combine;
use function array_keys;
use function array_map;
use function array_values;
use function get_parent_class;
use function json_encode;
use function preg_match;
use function preg_replace;
use function sprintf;
use function strtr;
use function ucfirst;

trait ContextExceptionLogic
{
    protected JsonSchemaAwareRecord|null $context = null;

    /**
     * @param array<string, mixed> $headers
     * @param array<string, mixed> $context
     */
    public function __construct(
        string $message = '',
        Throwable|null $previous = null,
        int $code = 0,
        array $headers = [],
        array $context = [],
    ) {
        $contextClass = static::contextClass();

        if ($contextClass === null && $context !== []) {
            throw new LogicException(
                sprintf(
                    "Context for exception '%s' is not allowed, because context class is not defined",
                    static::class,
                ),
            );
        }

        $this->context = $contextClass !== null
            ? $contextClass::fromArray($context)
            : null;

        $contextWithCurlyBraces = array_combine(
            array_map(
                static fn (string $key): string => sprintf('{%s}', $key),
                array_keys($this->context?->toArray() ?? []),
            ),
            array_values($this->context?->toArray() ?? []),
        );

        $message = strtr(static::template(), $contextWithCurlyBraces);

        if ($message === '' || $message === '0') {
            throw new LogicException(sprintf("Template from exception '%s' is empty", static::class));
        }

        if (preg_match('/\{([^\}]+)\}/', $message, $matches)) {
            throw new LogicException(
                sprintf(
                    "Template for exception '%s' contains variables that are not used: %s",
                    static::class,
                    json_encode($matches),
                ),
            );
        }

        parent::__construct($message, $previous, $code, $headers);
    }

    public static function template(): string
    {
        throw new LogicException('Template not implemented');
    }

    public static function schemaName(): string
    {
        $reflectionClass = (new ReflectionClass(static::class));
        $className = $reflectionClass->getShortName();

        /** @var string $schemaName */
        $schemaName = preg_replace('/(Http)?Exception$/', '', $className);

        return $schemaName;
    }

    public static function title(): string
    {
        return ucfirst(StringUtil::decamelize(self::schemaName(), ' '));
    }

    public function context(): JsonSchemaAwareRecord|null
    {
        return $this->context;
    }

    public static function statusCode(): int
    {
        $parentClass = static::class;
        do {
            $childClass = $parentClass;
            $parentClass = get_parent_class($childClass);
        } while ($parentClass !== false && $parentClass !== HttpException::class);

        /** @var HttpException $exception */
        $exception = new $childClass();

        return $exception->getStatusCode();
    }
}
