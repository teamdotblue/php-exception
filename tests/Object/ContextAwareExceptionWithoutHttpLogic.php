<?php

declare(strict_types=1);

namespace TeamBlue\Exception\Tests\Object;

use Exception;
use TeamBlue\Exception\HttpException\ContextAwareException;
use TeamBlue\Exception\HttpException\ContextAwareExceptionLogic;
use TeamBlue\Exception\Tests\Object\Context\UuidContext;

class ContextAwareExceptionWithoutHttpLogic extends Exception implements ContextAwareException
{
    use ContextAwareExceptionLogic;

    protected string $title = 'Context aware exception title';

    protected UuidContext $context;

    protected static function template(): string
    {
        return 'Context aware exception template with {uuid}.';
    }

    public static function createFromUuid(string $uuid): self
    {
        return self::fromContextArray(['uuid' => $uuid]);
    }

    public function getStatusCode(): int
    {
        return 422;
    }
}
