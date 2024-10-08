<?php

declare(strict_types=1);

namespace TeamBlue\Exception\HttpException;

use EventEngine\JsonSchema\JsonSchemaAwareRecord;

interface ContextAwareException extends HttpExceptionInterface
{
    public function context(): JsonSchemaAwareRecord;
}
