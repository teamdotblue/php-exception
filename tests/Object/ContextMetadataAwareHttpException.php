<?php

declare(strict_types=1);

namespace ADS\Exception\Tests\Object;

use ADS\Exception\HttpException\ContextAwareException;
use ADS\Exception\HttpException\ContextMetadataAwareExceptionLogic;
use ADS\Exception\HttpException\MetadataAwareException;
use ADS\Exception\HttpException\Status\NotFoundHttpException;
use ADS\Exception\Tests\Object\Context\UuidContext;

// phpcs:ignore SlevomatCodingStandard.Classes.SuperfluousExceptionNaming.SuperfluousSuffix
class ContextMetadataAwareHttpException extends NotFoundHttpException implements
    ContextAwareException,
    MetadataAwareException
{
    use ContextMetadataAwareExceptionLogic;

    protected string $title = 'Custom context metadata title';
    private UuidContext $context;

    protected static function template(): string
    {
        return 'This is a test metadata template with {uuid}.';
    }

    public static function createFromUuid(string $uuid): self
    {
        return self::fromContextArray(['uuid' => $uuid]);
    }
}
