<?php

declare(strict_types=1);

namespace ADS\Exception\Context;

use ADS\JsonImmutableObjects\JsonSchemaAwareRecordLogic;
use EventEngine\JsonSchema\JsonSchemaAwareRecord;

class StringValueContext implements JsonSchemaAwareRecord
{
    use JsonSchemaAwareRecordLogic;

    private string $value;

    public function value(): string
    {
        return $this->value;
    }
}
