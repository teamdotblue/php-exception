<?php

declare(strict_types=1);

namespace TeamBlue\Exception\Tests\Object;

use TeamBlue\Exception\HttpException\MetadataAwareException;
use TeamBlue\Exception\HttpException\MetadataAwareExceptionLogic;
use TeamBlue\Exception\HttpException\Status\ForbiddenHttpException;

// phpcs:ignore SlevomatCodingStandard.Classes.SuperfluousExceptionNaming.SuperfluousSuffix
class MetadataAwareForbiddenHttpException extends ForbiddenHttpException implements MetadataAwareException
{
    use MetadataAwareExceptionLogic;

    protected string $title = 'Custom metadata title';
}
