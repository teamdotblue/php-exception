<?php

declare(strict_types=1);

namespace ADS\Exception\Context;

use ADS\JsonImmutableObjects\JsonSchemaAwareRecordLogic;
use EventEngine\JsonSchema\JsonSchemaAwareRecord;
use TeamBlue\Common\Mysql\DatabaseUser\ValueObject\DatabaseUserName;

class DatabaseUserNameContext implements JsonSchemaAwareRecord
{
    use JsonSchemaAwareRecordLogic;

    private DatabaseUserName $databaseUsername;

    public function databaseUsername(): DatabaseUserName
    {
        return $this->databaseUsername;
    }
}
