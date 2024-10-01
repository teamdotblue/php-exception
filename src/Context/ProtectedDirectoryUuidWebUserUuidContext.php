<?php

declare(strict_types=1);

namespace ADS\Exception\Context;

use ADS\JsonImmutableObjects\JsonSchemaAwareRecordLogic;
use EventEngine\JsonSchema\JsonSchemaAwareRecord;
use TeamBlue\Common\Shaas\ProtectedDirectory\ValueObject\ProtectedDirectoryUuid;
use TeamBlue\Common\Shaas\WebUser\ValueObject\WebUserUuid;

class ProtectedDirectoryUuidWebUserUuidContext implements JsonSchemaAwareRecord
{
    use JsonSchemaAwareRecordLogic;

    private ProtectedDirectoryUuid $protectedDirectoryUuid;

    private WebUserUuid $webUserUuid;

    public function protectedDirectoryUuid(): ProtectedDirectoryUuid
    {
        return $this->protectedDirectoryUuid;
    }

    public function webUserUuid(): WebUserUuid
    {
        return $this->webUserUuid;
    }
}
