<?php

declare(strict_types=1);

namespace ADS\Exception\HttpException;

use EventEngine\JsonSchema\JsonSchemaAwareRecord;

interface ContextException
{
    public static function title(): string;

    public static function template(): string;

    public function context(): JsonSchemaAwareRecord|null;

    /** @return class-string<JsonSchemaAwareRecord>|null */
    public static function contextClass(): string|null;

    public static function statusCode(): int;

    public static function schemaName(): string;
}
