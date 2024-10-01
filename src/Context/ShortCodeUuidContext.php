<?php

declare(strict_types=1);

namespace ADS\Exception\Context;

use ADS\JsonImmutableObjects\JsonSchemaAwareRecordLogic;
use EventEngine\JsonSchema\JsonSchemaAwareRecord;
use TeamBlue\Common\General\ValueObject\Uuid;
use TeamBlue\Common\Shaas\Identifier\ValueObject\ShortCode;

class ShortCodeUuidContext implements JsonSchemaAwareRecord
{
    use JsonSchemaAwareRecordLogic;

    private ShortCode $shortCode;

    public function shortCode(): ShortCode
    {
        return $this->shortCode;
    }

    private Uuid $uuid;

    public function uuid(): Uuid
    {
        return $this->uuid;
    }
}
