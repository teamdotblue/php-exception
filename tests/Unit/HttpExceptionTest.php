<?php

declare(strict_types=1);

namespace TeamBlue\Exception\Tests\Unit;

use PHPUnit\Framework\TestCase;
use ReflectionClass;
use TeamBlue\Exception\HttpException\Metadata\Metadata;
use TeamBlue\Exception\Tests\Object\ContextAwareBadRequestHttpException;
use TeamBlue\Exception\Tests\Object\ContextAwareExceptionWithoutHttpLogic;
use TeamBlue\Exception\Tests\Object\ContextMetadataAwareHttpException;
use TeamBlue\Exception\Tests\Object\CustomUnprocessableHttpException;
use TeamBlue\Exception\Tests\Object\MetadataAwareForbiddenHttpException;

use function array_column;
use function sprintf;

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

    public function testThrowCustomUnprocessableHttpException(): void
    {
        $this->expectException(CustomUnprocessableHttpException::class);
        $this->expectExceptionMessage('Custom Detail');

        throw CustomUnprocessableHttpException::create();
    }

    public function testCustomContextAwareBadRequestHttpException(): void
    {
        $exception = ContextAwareBadRequestHttpException::createFromUuid('3d2378ed-3684-46d9-a818-34dbe403b576');

        $this->assertInstanceOf(ContextAwareBadRequestHttpException::class, $exception);
        $this->assertEquals('/problem/custom-context-aware-bad-request-http-exception', $exception->type());
        $this->assertEquals('Custom context title', $exception->title());
        $this->assertEquals(
            'This is a test template with 3d2378ed-3684-46d9-a818-34dbe403b576.',
            $exception->getMessage(),
        );
        $this->assertEquals(
            'This is a test template with 3d2378ed-3684-46d9-a818-34dbe403b576.',
            $exception->detail(),
        );
        $this->assertEquals(['uuid' => '3d2378ed-3684-46d9-a818-34dbe403b576'], $exception->context()->toArray());
    }

    public function testThrowCustomContextAwareBadRequestHttpException(): void
    {
        $this->expectException(ContextAwareBadRequestHttpException::class);
        $this->expectExceptionMessage('This is a test template with 3d2378ed-3684-46d9-a818-34dbe403b576.');

        throw ContextAwareBadRequestHttpException::createFromUuid('3d2378ed-3684-46d9-a818-34dbe403b576');
    }

    public function testCustomMetadataAwareForbiddenHttpException(): void
    {
        $exception = MetadataAwareForbiddenHttpException::fromDetail('test detail');

        $this->assertInstanceOf(MetadataAwareForbiddenHttpException::class, $exception);
        $this->assertEquals('/problem/custom-metadata-aware-forbidden-http-exception', $exception->type());
        $this->assertEquals('Custom metadata title', $exception->title());
        $this->assertEquals(
            'test detail',
            $exception->getMessage(),
        );
        $this->assertEquals(
            'test detail',
            $exception->detail(),
        );
        $this->assertInstanceOf(Metadata::class, $exception->metadata());
    }

    public function testCustomContextMetadataAwareNotFoundHttpException(): void
    {
        $exception = ContextMetadataAwareHttpException::createFromUuid(
            '3d2378ed-3684-46d9-a818-34dbe403b577',
        );

        $this->assertInstanceOf(ContextMetadataAwareHttpException::class, $exception);
        $this->assertEquals('/problem/custom-context-metadata-aware-not-found-http-exception', $exception->type());
        $this->assertEquals('Custom context metadata title', $exception->title());
        $this->assertEquals(
            'This is a test metadata template with 3d2378ed-3684-46d9-a818-34dbe403b577.',
            $exception->getMessage(),
        );
        $this->assertEquals(
            'This is a test metadata template with 3d2378ed-3684-46d9-a818-34dbe403b577.',
            $exception->detail(),
        );

        $this->assertInstanceOf(Metadata::class, $exception->metadata());
        $this->assertEquals(['uuid' => '3d2378ed-3684-46d9-a818-34dbe403b577'], $exception->context()->toArray());
    }

    public function testContextAwareExceptionWithoutHttpLogicDoesNotCreateDynamicProperties(): void
    {
        $exception = ContextAwareExceptionWithoutHttpLogic::createFromUuid(
            '3d2378ed-3684-46d9-a818-34dbe403b578',
        );

        $this->assertInstanceOf(ContextAwareExceptionWithoutHttpLogic::class, $exception);
        $this->assertEquals(
            'Context aware exception template with 3d2378ed-3684-46d9-a818-34dbe403b578.',
            $exception->getMessage(),
        );
        $this->assertEquals(['uuid' => '3d2378ed-3684-46d9-a818-34dbe403b578'], $exception->context()->toArray());

        $properties = array_column((new ReflectionClass($exception))->getProperties(), 'name');
        foreach (['type', 'detail', 'status'] as $property) {
            $this->assertContains(
                $property,
                $properties,
                sprintf(
                    'ContextAwareExceptionLogic must declare $%s, so no dynamic property is created when the trait ' .
                    'is used without HttpExceptionLogic.',
                    $property,
                ),
            );
        }
    }
}
