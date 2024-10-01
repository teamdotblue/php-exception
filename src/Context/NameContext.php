<?php

declare(strict_types=1);

namespace ADS\Exception\Context;

use ADS\JsonImmutableObjects\JsonSchemaAwareRecordLogic;
use EventEngine\JsonSchema\JsonSchemaAwareRecord;

class NameContext implements JsonSchemaAwareRecord
{
    use JsonSchemaAwareRecordLogic;

    private string $name;

    public function name(): string
    {
        return $this->name;
    }
}
