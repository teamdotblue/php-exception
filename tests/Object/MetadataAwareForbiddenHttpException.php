<?php

declare(strict_types=1);

namespace ADS\Exception\Tests\Object;

use ADS\Exception\HttpException\MetadataAwareException;
use ADS\Exception\HttpException\MetadataAwareExceptionLogic;
use ADS\Exception\HttpException\Status\ForbiddenHttpException;

// phpcs:ignore SlevomatCodingStandard.Classes.SuperfluousExceptionNaming.SuperfluousSuffix
class MetadataAwareForbiddenHttpException extends ForbiddenHttpException implements MetadataAwareException
{
    use MetadataAwareExceptionLogic;

    protected string $title = 'Custom metadata title';
}
