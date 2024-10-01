<?php

declare(strict_types=1);

namespace ADS\Exception\Context;

use ADS\JsonImmutableObjects\JsonSchemaAwareRecordLogic;
use EventEngine\JsonSchema\JsonSchemaAwareRecord;
use TeamBlue\Common\Shaas\Identifier\ValueObject\ShortCode;

class LimitShortCodeContext implements JsonSchemaAwareRecord
{
    use JsonSchemaAwareRecordLogic;

    private ShortCode $shortCode;

    public function shortCode(): ShortCode
    {
        return $this->shortCode;
    }

    private int $limit;

    public function limit(): int
    {
        return $this->limit;
    }
}
