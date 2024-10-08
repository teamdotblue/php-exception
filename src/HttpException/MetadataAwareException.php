<?php

declare(strict_types=1);

namespace ADS\Exception\HttpException;

use ADS\Exception\HttpException\Metadata\Metadata;

interface MetadataAwareException extends HttpExceptionInterface
{
    public function metadata(): Metadata;

    public function withMetadata(Metadata $metadata): self;
}
