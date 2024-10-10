<?php

declare(strict_types=1);

namespace TeamBlue\Exception;

use ADS\Util\StringUtil;
use LogicException;
use ReflectionClass;
use ReflectionNamedType;
use ReflectionProperty;
use TeamBlue\JsonImmutableObjects\JsonSchemaAwareRecordLogic;

use function array_combine;
use function array_diff_key;
use function array_flip;
use function array_map;
use function sprintf;
use function strrchr;
use function substr;

trait DefaultJsonSchemaException
{
    use JsonSchemaAwareRecordLogic;

    private const PROPERTIES_TO_IGNORE = [
        'statusCode',
        'headers',
        'message',
        'code',
        'file',
        'line',
        '__useMaxValues',
        '__encryptedSensitiveData',
        '__propTypeMap',
        '__schema',
        '__arrayPropItemTypeMap',
    ];

    private function init(): void
    {
        $this->status = $this->getStatusCode();
        $this->message = $this->detail;
    }

    /**
     * @return array<string, string>
     *
     * @phpcsSuppress SlevomatCodingStandard.Classes.UnusedPrivateElements.UnusedMethod
     */
    public static function __defaultProperties(): array
    {
        $strrchr = strrchr(self::class, '\\');

        if ($strrchr === false) {
            throw new LogicException('Class name does not contain a backslash');
        }

        return [
            'type' => sprintf(
                '/problem/%s',
                StringUtil::decamelize(substr($strrchr, 1), '-', ' '),
            ),
        ];
    }

    /** @return array<string, array{0: string, 1: bool, 2: bool}> */
    private static function buildPropTypeMap(): array
    {
        $refObj = new ReflectionClass(self::class);
        $properties = $refObj->getProperties();
        $propertiesByName = array_combine(
            array_map(
                static fn (ReflectionProperty $property): string => $property->getName(),
                $properties,
            ),
            $properties,
        );
        $propertiesByName = array_diff_key($propertiesByName, array_flip(self::PROPERTIES_TO_IGNORE));
        $propertyTypeMap = [];

        foreach ($propertiesByName as $propertyName => $property) {
            /** @var ReflectionNamedType $type */
            $type = self::typeFromProperty($property, $refObj);

            $propertyTypeMap[$propertyName] = [
                $type->getName(),
                self::isScalarType($type->getName()),
                $type->allowsNull(),
            ];
        }

        return $propertyTypeMap;
    }
}
