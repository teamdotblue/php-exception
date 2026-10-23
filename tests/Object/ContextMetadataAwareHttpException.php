<?php

declare(strict_types=1);

namespace TeamBlue\Exception\Tests\Object;

use TeamBlue\Exception\HttpException\ContextAwareException;
use TeamBlue\Exception\HttpException\ContextMetadataAwareExceptionLogic;
use TeamBlue\Exception\HttpException\MetadataAwareException;
use TeamBlue\Exception\HttpException\Status\NotFoundHttpException;
use TeamBlue\Exception\Tests\Object\Context\UuidContext;

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
