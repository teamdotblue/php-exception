<?php

declare(strict_types=1);

namespace ADS\Exception\Context;

use ADS\JsonImmutableObjects\JsonSchemaAwareRecordLogic;
use EventEngine\JsonSchema\JsonSchemaAwareRecord;

class LimitContext implements JsonSchemaAwareRecord
{
    use JsonSchemaAwareRecordLogic;

    private int $limit;

    public function limit(): int
    {
        return $this->limit;
    }
}
