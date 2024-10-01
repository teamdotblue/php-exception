<?php

declare(strict_types=1);

namespace ADS\Exception\Context;

use ADS\JsonImmutableObjects\JsonSchemaAwareRecordLogic;
use EventEngine\JsonSchema\JsonSchemaAwareRecord;

class ValueLimitContext implements JsonSchemaAwareRecord
{
    use JsonSchemaAwareRecordLogic;

    private float $value;

    private int $limit;

    public function value(): float
    {
        return $this->value;
    }

    public function limit(): int
    {
        return $this->limit;
    }
}
