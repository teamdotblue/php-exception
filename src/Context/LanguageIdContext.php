<?php

declare(strict_types=1);

namespace ADS\Exception\Context;

use ADS\JsonImmutableObjects\JsonSchemaAwareRecordLogic;
use EventEngine\JsonSchema\JsonSchemaAwareRecord;
use TeamBlue\Common\CmsInstall\Language\ValueObject\LanguageId;

class LanguageIdContext implements JsonSchemaAwareRecord
{
    use JsonSchemaAwareRecordLogic;

    private LanguageId $languageId;

    public function languageId(): LanguageId
    {
        return $this->languageId;
    }
}
