<?php

declare(strict_types=1);

namespace ADS\Exception\HttpException;

use InvalidArgumentException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

use function is_array;
use function json_decode;

/** @deprecated  */
// phpcs:disable SlevomatCodingStandard.Classes.SuperfluousExceptionNaming.SuperfluousSuffix
class PassThroughContextException extends HttpException
{
    /**
     * @param array<string, mixed>  $context
     * @param array<string, string> $headers
     */
    public function __construct(
        private string $title,
        private array $context,
        private string $type,
        int $statusCode = 500,
        string $message = '',
        Throwable|null $previous = null,
        array $headers = [],
        int $code = 0,
    ) {
        parent::__construct($statusCode, $message, $previous, $headers, $code);
    }

    public static function canPassThrough(Throwable $exception): bool
    {
        $message = $exception->getMessage();
        $json = json_decode($message, true);

        if (! is_array($json)) {
            return false;
        }

        return isset($json['title'], $json['context'], $json['type'], $json['detail']);
    }

    public static function fromThrowable(Throwable $exception): self
    {
        $message = $exception->getMessage();
        $json = json_decode($message, true);

        if (! is_array($json) || ! self::canPassThrough($exception)) {
            throw new InvalidArgumentException('Invalid JSON in exception message');
        }

        return new self(
            $json['title'] ?? 'Internal Server Error',
            $json['context'] ?? [],
            $json['type'] ?? 'about:blank',
            $exception->getCode(),
            $json['detail'] ?? '',
        );
    }

    public function title(): string
    {
        return $this->title;
    }

    /** @return array<string, mixed> */
    public function context(): array
    {
        return $this->context;
    }

    public function type(): string
    {
        return $this->type;
    }
}
