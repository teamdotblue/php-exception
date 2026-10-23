<?php

declare(strict_types=1);

namespace TeamBlue\Exception;

use LogicException;
use ReflectionClass;
use ReflectionNamedType;
use ReflectionProperty;
use TeamBlue\JsonImmutableObjects\JsonSchemaAwareRecordLogic;
use TeamBlue\Util\StringUtil;

use function array_combine;
use function array_diff_key;
use function array_flip;
use function array_intersect_key;
use function array_map;
use function array_merge;
use function sprintf;
use function strrchr;
use function substr;

trait DefaultJsonSchemaException
{
    use JsonSchemaAwareRecordLogic {
        init as parentInit;
    }

    private const PROPERTY_ORDER = [
        'type',
        'title',
        'detail',
        'context',
        'status',
        'metadata',
    ];

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
        // add the default properties. @see __defaultProperties
        $this->parentInit();

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
                '/problem/%s%s',
                self::typePrefix(),
                StringUtil::decamelize(substr($strrchr, 1), '-', ' '),
            ),
            'status' => (new ReflectionClass(self::class))->newInstanceWithoutConstructor()->getStatusCode(),
        ];
    }

    private static function typePrefix(): string
    {
        return '';
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
                self::isPropertySensitive($property),
            ];
        }

        $sortedPropertyTypeMap = array_merge(
            array_flip(self::PROPERTY_ORDER),
            $propertyTypeMap,
        );

        return array_intersect_key($sortedPropertyTypeMap, $propertyTypeMap);
    }

    /**
     * @return array<string>
     *
     * @inheritDoc
     */
    private static function __optionalProperties(): array
    {
        return [];
    }
}
