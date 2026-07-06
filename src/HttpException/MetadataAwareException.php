<?php

declare(strict_types=1);

namespace TeamBlue\Exception\HttpException;

use TeamBlue\Exception\HttpException\Metadata\Metadata;

interface MetadataAwareException
{
    public function metadata(): Metadata;

    public function withMetadata(Metadata $metadata): self;
}
