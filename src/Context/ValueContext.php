<?php

declare(strict_types=1);

namespace ADS\Exception\Context;

use ADS\JsonImmutableObjects\JsonSchemaAwareRecordLogic;
use EventEngine\JsonSchema\JsonSchemaAwareRecord;

class ValueContext implements JsonSchemaAwareRecord
{
    use JsonSchemaAwareRecordLogic;

    private float $value;

    public function value(): float
    {
        return $this->value;
    }
}
