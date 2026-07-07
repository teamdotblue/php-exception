<?php

declare(strict_types=1);

namespace TeamBlue\Exception\HttpException;

use Exception;

abstract class HttpException extends Exception implements HttpExceptionInterface
{
    use HttpExceptionLogic;
}
