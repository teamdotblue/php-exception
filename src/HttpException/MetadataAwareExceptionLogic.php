<?php

declare(strict_types=1);

namespace ADS\Exception\HttpException;

use ADS\Exception\DefaultJsonSchemaException;
use ADS\Exception\HttpException\Metadata\Metadata;

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
        return $this->with(['metadata' => $metadata]);
    }
}
