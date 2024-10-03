<?php

declare(strict_types=1);

namespace ADS\Exception\Tests\Unit;

use ADS\Exception\Tests\Object\CustomHttpException\CustomUnprocessableHttpException;
use PHPUnit\Framework\TestCase;

class HttpExceptionTest extends TestCase
{
    public function testCustomUnprocessableHttpException(): void
    {
        $exception = CustomUnprocessableHttpException::create();

        $this->assertInstanceOf(CustomUnprocessableHttpException::class, $exception);
        $this->assertEquals('Custom Detail', $exception->getMessage());
        $this->assertEquals('/problem/custom-unprocessable-http-exception', $exception->type());
        $this->assertEquals('Custom Title', $exception->title());
        $this->assertEquals('Custom Detail', $exception->detail());
    }
}
