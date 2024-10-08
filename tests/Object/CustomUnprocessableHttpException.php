<?php

declare(strict_types=1);

namespace ADS\Exception\Tests\Object;

use ADS\Exception\DefaultJsonSchemaException;
use ADS\Exception\HttpException\Status\UnprocessableHttpException;

// phpcs:ignore SlevomatCodingStandard.Classes.SuperfluousExceptionNaming.SuperfluousSuffix
class CustomUnprocessableHttpException extends UnprocessableHttpException
{
    use DefaultJsonSchemaException;

    public static function create(): self
    {
        return self::fromTitleAndDetail('Custom Title', 'Custom Detail');
    }
}
