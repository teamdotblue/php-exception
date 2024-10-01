<?php

declare(strict_types=1);

namespace ADS\Exception\Context;

use ADS\JsonImmutableObjects\JsonSchemaAwareRecordLogic;
use EventEngine\JsonSchema\JsonSchemaAwareRecord;
use TeamBlue\Common\Hcl\WebUser\ValueObject\WebUserName;

class WebUserNameContext implements JsonSchemaAwareRecord
{
    use JsonSchemaAwareRecordLogic;

    private WebUserName $webUserName;

    public function webUserName(): WebUserName
    {
        return $this->webUserName;
    }
}
