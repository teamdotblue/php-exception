<?php

declare(strict_types=1);

namespace ADS\Exception\Tests\Object;

use ADS\Exception\HttpException\ContextAwareException;
use ADS\Exception\HttpException\ContextAwareExceptionLogic;
use ADS\Exception\HttpException\Status\BadRequestHttpException;
use ADS\Exception\Tests\Object\Context\UuidContext;

// phpcs:ignore SlevomatCodingStandard.Classes.SuperfluousExceptionNaming.SuperfluousSuffix
class ContextAwareBadRequestHttpException extends BadRequestHttpException implements ContextAwareException
{
    use ContextAwareExceptionLogic;

    protected string $title = 'Custom context title';
    private UuidContext $context;

    protected static function template(): string
    {
        return 'This is a test template with {uuid}.';
    }

    public static function createFromUuid(string $uuid): self
    {
        return self::fromContextArray(['uuid' => $uuid]);
    }
}
