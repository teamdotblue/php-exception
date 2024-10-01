<?php

declare(strict_types=1);

namespace ADS\Exception\Context;

use ADS\JsonImmutableObjects\JsonSchemaAwareRecordLogic;
use EventEngine\JsonSchema\JsonSchemaAwareRecord;
use TeamBlue\Common\CmsInstall\Version\ValueObject\VersionId;

class VersionIdContext implements JsonSchemaAwareRecord
{
    use JsonSchemaAwareRecordLogic;

    private VersionId $versionId;

    public function versionId(): VersionId
    {
        return $this->versionId;
    }
}
