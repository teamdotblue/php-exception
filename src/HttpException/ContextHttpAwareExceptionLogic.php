<?php

declare(strict_types=1);

namespace TeamBlue\Exception\HttpException;

use function array_merge;

trait ContextHttpAwareExceptionLogic
{
    use ContextAwareExceptionLogic {
        ContextAwareExceptionLogic::__defaultProperties as contextAwareExceptionDefaultProperties;
        ContextAwareExceptionLogic::__optionaLProperties as contextAwareExceptionOptionalProperties;
        ContextAwareExceptionLogic::init insteadof HttpExceptionLogic;
        ContextAwareExceptionLogic::buildPropTypeMap insteadof HttpExceptionLogic;
    }
    use HttpExceptionLogic {
        HttpExceptionLogic::__defaultProperties as httpAwareExceptionDefaultProperties;
        HttpExceptionLogic::__optionalProperties as httpAwareExceptionOptionalProperties;
    }

    /** @inheritdoc  */
    public static function __defaultProperties(): array
    {
        return array_merge(
            self::contextAwareExceptionDefaultProperties(),
            self::httpAwareExceptionDefaultProperties(),
        );
    }

    /** @inheritdoc  */
    public static function __optionalProperties(): array
    {
        return array_merge(
            self::contextAwareExceptionOptionalProperties(),
            self::httpAwareExceptionOptionalProperties(),
        );
    }
}
