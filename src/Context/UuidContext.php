<?php

declare(strict_types=1);

namespace ADS\Exception\Context;

use ADS\JsonImmutableObjects\JsonSchemaAwareRecordLogic;
use EventEngine\JsonSchema\JsonSchemaAwareRecord;
use TeamBlue\Common\General\ValueObject\Uuid;

class UuidContext implements JsonSchemaAwareRecord
{
    use JsonSchemaAwareRecordLogic;

    private Uuid $uuid;

    public function uuid(): Uuid
    {
        return $this->uuid;
    }
}
