<?php

declare(strict_types=1);

namespace ADS\Exception\Context;

use ADS\JsonImmutableObjects\JsonSchemaAwareRecordLogic;
use EventEngine\JsonSchema\JsonSchemaAwareRecord;
use TeamBlue\Common\General\ValueObject\DateTime;

class DateTimeContext implements JsonSchemaAwareRecord
{
    use JsonSchemaAwareRecordLogic;

    private DateTime $datetime;

    public function datetime(): DateTime
    {
        return $this->datetime;
    }
}
