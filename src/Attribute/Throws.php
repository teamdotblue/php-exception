<?php

declare(strict_types=1);

namespace TeamBlue\Exception\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class Throws
{
    /** @param class-string[] $exceptions */
    public function __construct(
        private readonly array $exceptions,
    ) {
    }

    /** @return class-string[] */
    public function exceptions(): array
    {
        return $this->exceptions;
    }
}
