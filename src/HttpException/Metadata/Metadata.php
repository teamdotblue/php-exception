<?php

declare(strict_types=1);

namespace TeamBlue\Exception\HttpException\Metadata;

use EventEngine\JsonSchema\JsonSchemaAwareRecord;
use RuntimeException;
use TeamBlue\JsonImmutableObjects\HasPropertyExamples;
use TeamBlue\JsonImmutableObjects\JsonSchemaAwareRecordLogic;

class Metadata implements JsonSchemaAwareRecord, HasPropertyExamples
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

    /** @inheritdoc  */
    public static function examples(): array
    {
        return [
            'requestId' => '50d02fc7-0420-485d-8904-af2fd8c61cf9',
            'sentryTraceId' => 'fef576',
        ];
    }
}
