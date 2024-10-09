<?php

declare(strict_types=1);

namespace TeamBlue\Exception\HttpException;

use TeamBlue\Exception\DefaultJsonSchemaException;
use TeamBlue\Exception\HttpException\Metadata\Metadata;

use function array_merge;

trait MetadataAwareExceptionLogic
{
    use DefaultJsonSchemaException {
        __defaultProperties as defaultJsonSchemaExceptionDefaultProperties;
    }

    private Metadata $metadata;

    /** @return array<string, mixed>  */
    public static function __defaultProperties(): array
    {
        $defaultProperties = self::defaultJsonSchemaExceptionDefaultProperties();

        return array_merge(
            $defaultProperties,
            [
                'metadata' => Metadata::fromArray([]),
            ],
        );
    }

    public function metadata(): Metadata
    {
        return $this->metadata;
    }

    public function withMetadata(Metadata $metadata): self
    {
        // Don't use with, we can't clone exceptions.
        $this->setRecordData(['metadata' => $metadata]);

        return $this;
    }
}
