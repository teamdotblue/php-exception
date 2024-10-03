<?php

declare(strict_types=1);

namespace ADS\Exception;

use ADS\JsonImmutableObjects\JsonSchemaAwareRecordLogic;
use ADS\Util\StringUtil;
use LogicException;
use ReflectionClass;
use ReflectionNamedType;
use ReflectionProperty;

use function array_combine;
use function array_diff_key;
use function array_flip;
use function array_map;
use function sprintf;
use function strrchr;
use function substr;

/**
 * @property string $message
 * @method int getStatusCode()
 */
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

    protected string $type;

    protected string $title = 'Exception';
    protected string $detail;

    protected int $status;

    private function init(): void
    {
        $this->status = $this->getStatusCode();
        $this->message = $this->detail;
    }

    public static function fromTitleAndDetail(string $title, string $detail): self
    {
        return self::fromArray(
            [
                'title' => $title,
                'detail' => $detail,
            ],
        );
    }

    public static function fromDetail(string $detail): self
    {
        return self::fromArray(['detail' => $detail]);
    }

    /**
     * @return array<string, string>
     *
     * @phpcsSuppress SlevomatCodingStandard.Classes.UnusedPrivateElements.UnusedMethod
     */
    private static function __defaultProperties(): array
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

    public function type(): string
    {
        return $this->type;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function detail(): string
    {
        return $this->detail;
    }
}
