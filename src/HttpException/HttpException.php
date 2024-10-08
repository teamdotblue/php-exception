<?php

declare(strict_types=1);

namespace ADS\Exception\HttpException;

use ADS\JsonImmutableObjects\JsonSchemaAwareRecordLogic;
use Exception;

abstract class HttpException extends Exception implements HttpExceptionInterface
{
    use JsonSchemaAwareRecordLogic;

    protected string $type;
    protected string $title = 'Exception';
    protected string $detail;
    protected int $status;

    public static function fromTitleAndDetail(string $title, string $detail): static
    {
        return static::fromArray(
            [
                'title' => $title,
                'detail' => $detail,
            ],
        );
    }

    public static function fromDetail(string $detail): static
    {
        return static::fromArray(['detail' => $detail]);
    }

    public function type(): string
    {
        return $this->type;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function detail(): string
    {
        return $this->detail;
    }

    public function status(): int
    {
        return $this->status;
    }

    /** @return array<mixed> */
    public function getHeaders(): array
    {
        return [];
    }
}
