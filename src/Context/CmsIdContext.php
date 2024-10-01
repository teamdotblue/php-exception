<?php

declare(strict_types=1);

namespace ADS\Exception\Context;

use ADS\JsonImmutableObjects\JsonSchemaAwareRecordLogic;
use EventEngine\JsonSchema\JsonSchemaAwareRecord;
use TeamBlue\Common\CmsInstall\Cms\ValueObject\CmsId;

class CmsIdContext implements JsonSchemaAwareRecord
{
    use JsonSchemaAwareRecordLogic;

    private CmsId $cmsId;

    public function cmsId(): CmsId
    {
        return $this->cmsId;
    }
}
