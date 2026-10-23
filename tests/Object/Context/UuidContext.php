<?php

declare(strict_types=1);

namespace TeamBlue\Exception\Tests\Object\Context;

use EventEngine\JsonSchema\JsonSchemaAwareRecord;
use TeamBlue\JsonImmutableObjects\JsonSchemaAwareRecordLogic;

class UuidContext implements JsonSchemaAwareRecord
{
    use JsonSchemaAwareRecordLogic;

    private string $uuid;
}
