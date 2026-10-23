<?php

declare(strict_types=1);

namespace TeamBlue\Exception\HttpException\Status;

use Symfony\Component\HttpFoundation\Response;
use TeamBlue\Exception\HttpException\HttpException;

abstract class UnauthorizedHttpException extends HttpException
{
    public function getStatusCode(): int
    {
        return Response::HTTP_UNAUTHORIZED;
    }
}
