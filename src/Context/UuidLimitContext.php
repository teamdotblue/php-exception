<?php

declare(strict_types=1);

namespace ADS\Exception\Context;

use ADS\JsonImmutableObjects\JsonSchemaAwareRecordLogic;
use EventEngine\JsonSchema\JsonSchemaAwareRecord;
use TeamBlue\Common\General\ValueObject\Uuid;

class UuidLimitContext implements JsonSchemaAwareRecord
{
    use JsonSchemaAwareRecordLogic;

    private Uuid $uuid;

    private int $limit;

    public function uuid(): Uuid
    {
        return $this->uuid;
    }

    public function limit(): int
    {
        return $this->limit;
    }
}
