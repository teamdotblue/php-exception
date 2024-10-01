<?php

declare(strict_types=1);

namespace ADS\Exception\Context;

use ADS\JsonImmutableObjects\JsonSchemaAwareRecordLogic;
use EventEngine\JsonSchema\JsonSchemaAwareRecord;
use TeamBlue\Common\General\ValueObject\Uuid;

class TriggerPipelineUrlInstanceContext implements JsonSchemaAwareRecord
{
    use JsonSchemaAwareRecordLogic;

    private Uuid $triggerPipelineUrlUuid;

    private Uuid $instanceUuid;

    public function triggerPipelineUrlUuid(): Uuid
    {
        return $this->triggerPipelineUrlUuid;
    }

    public function instanceUuid(): Uuid
    {
        return $this->instanceUuid;
    }
}
