<?php

declare(strict_types=1);

namespace ADS\Exception\HttpException;

use EventEngine\JsonSchema\JsonSchemaAwareRecord;

// phpcs:ignore SlevomatCodingStandard.Classes.SuperfluousInterfaceNaming.SuperfluousSuffix
interface HttpExceptionInterface extends
    \Symfony\Component\HttpKernel\Exception\HttpExceptionInterface,
    JsonSchemaAwareRecord
{
    public function type(): string;

    public function title(): string;

    public function detail(): string;

    public function status(): int;
}
