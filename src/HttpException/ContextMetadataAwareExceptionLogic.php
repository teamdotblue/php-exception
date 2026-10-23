<?php

declare(strict_types=1);

namespace TeamBlue\Exception\HttpException;

use function array_merge;

trait ContextMetadataAwareExceptionLogic
{
    use ContextAwareExceptionLogic {
        ContextAwareExceptionLogic::__defaultProperties as contextAwareExceptionDefaultProperties;
        ContextAwareExceptionLogic::init insteadof MetadataAwareExceptionLogic;
        ContextAwareExceptionLogic::buildPropTypeMap insteadof MetadataAwareExceptionLogic;
    }
    use MetadataAwareExceptionLogic {
        MetadataAwareExceptionLogic::__defaultProperties as metadataAwareExceptionDefaultProperties;
    }

    /** @inheritdoc  */
    public static function __defaultProperties(): array
    {
        return array_merge(
            self::contextAwareExceptionDefaultProperties(),
            self::metadataAwareExceptionDefaultProperties(),
        );
    }
}
