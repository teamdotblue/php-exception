<?php

declare(strict_types=1);

namespace TeamBlue\Exception\Tests\Object\Context;

use ADS\JsonImmutableObjects\JsonSchemaAwareRecordLogic;
use EventEngine\JsonSchema\JsonSchemaAwareRecord;

class UuidContext implements JsonSchemaAwareRecord
{
    use JsonSchemaAwareRecordLogic;

    private string $uuid;
}
