<?php

declare(strict_types=1);

namespace ADS\Exception\HttpException\Metadata;

use ADS\JsonImmutableObjects\JsonSchemaAwareRecordLogic;
use EventEngine\JsonSchema\JsonSchemaAwareRecord;
use RuntimeException;

class Metadata implements JsonSchemaAwareRecord
{
    use JsonSchemaAwareRecordLogic;

    private string|null $requestId = null;
    private string|null $sentryTraceId = null;

    public function requestId(): string|null
    {
        return $this->requestId;
    }

    public function needRequestId(): string
    {
        if ($this->requestId === null) {
            throw new RuntimeException('Request id not found in the request headers.');
        }

        return $this->requestId;
    }

    public function sentryTraceId(): string|null
    {
        return $this->sentryTraceId;
    }

    public function needSentryTraceId(): string
    {
        if ($this->sentryTraceId === null) {
            throw new RuntimeException('Sentry trace id not found.');
        }

        return $this->sentryTraceId;
    }
}
