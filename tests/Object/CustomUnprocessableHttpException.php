<?php

declare(strict_types=1);

namespace TeamBlue\Exception\Tests\Object;

use TeamBlue\Exception\DefaultJsonSchemaException;
use TeamBlue\Exception\HttpException\Status\UnprocessableHttpException;

// phpcs:ignore SlevomatCodingStandard.Classes.SuperfluousExceptionNaming.SuperfluousSuffix
class CustomUnprocessableHttpException extends UnprocessableHttpException
{
    use DefaultJsonSchemaException;

    public static function create(): self
    {
        return self::fromTitleAndDetail('Custom Title', 'Custom Detail');
    }
}
