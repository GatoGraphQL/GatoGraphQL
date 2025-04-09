<?php

declare(strict_types=1);

namespace PoPCMSSchema\Meta\FieldResolvers\InterfaceType;

use PoPCMSSchema\Meta\TypeResolvers\InputObjectType\MetaKeysFilterInputObjectTypeResolver;
use PoPCMSSchema\Meta\TypeResolvers\InterfaceType\WithMetaInterfaceTypeResolver;
use PoPSchema\ExtendedSchemaCommons\TypeResolvers\ScalarType\ListValueJSONObjectScalarTypeResolver;
use PoP\ComponentModel\FieldResolvers\InterfaceType\AbstractInterfaceTypeFieldResolver;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InterfaceType\InterfaceTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\AnyScalarScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;

class WithMetaInterfaceTypeFieldResolver extends AbstractInterfaceTypeFieldResolver
{
    private ?AnyScalarScalarTypeResolver $anyScalarScalarTypeResolver = null;
    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;
    private ?ListValueJSONObjectScalarTypeResolver $listValueJSONObjectScalarTypeResolver = null;
    private ?MetaKeysFilterInputObjectTypeResolver $metaKeysFilterInputObjectTypeResolver = null;

    final protected function getAnyScalarScalarTypeResolver(): AnyScalarScalarTypeResolver
    {
        if ($this->anyScalarScalarTypeResolver === null) {
            /** @var AnyScalarScalarTypeResolver */
            $anyScalarScalarTypeResolver = $this->instanceManager->getInstance(AnyScalarScalarTypeResolver::class);
            $this->anyScalarScalarTypeResolver = $anyScalarScalarTypeResolver;
        }
        return $this->anyScalarScalarTypeResolver;
    }
    final protected function getStringScalarTypeResolver(): StringScalarTypeResolver
    {
        if ($this->stringScalarTypeResolver === null) {
            /** @var StringScalarTypeResolver */
            $stringScalarTypeResolver = $this->instanceManager->getInstance(StringScalarTypeResolver::class);
            $this->stringScalarTypeResolver = $stringScalarTypeResolver;
        }
        return $this->stringScalarTypeResolver;
    }
    final protected function getListValueJSONObjectScalarTypeResolver(): ListValueJSONObjectScalarTypeResolver
    {
        if ($this->listValueJSONObjectScalarTypeResolver === null) {
            /** @var ListValueJSONObjectScalarTypeResolver */
            $listValueJSONObjectScalarTypeResolver = $this->instanceManager->getInstance(ListValueJSONObjectScalarTypeResolver::class);
            $this->listValueJSONObjectScalarTypeResolver = $listValueJSONObjectScalarTypeResolver;
        }
        return $this->listValueJSONObjectScalarTypeResolver;
    }
    final protected function getMetaKeysFilterInputObjectTypeResolver(): MetaKeysFilterInputObjectTypeResolver
    {
        if ($this->metaKeysFilterInputObjectTypeResolver === null) {
            /** @var MetaKeysFilterInputObjectTypeResolver */
            $metaKeysFilterInputObjectTypeResolver = $this->instanceManager->getInstance(MetaKeysFilterInputObjectTypeResolver::class);
            $this->metaKeysFilterInputObjectTypeResolver = $metaKeysFilterInputObjectTypeResolver;
        }
        return $this->metaKeysFilterInputObjectTypeResolver;
    }

    /**
     * @return array<class-string<InterfaceTypeResolverInterface>>
     */
    public function getInterfaceTypeResolverClassesToAttachTo(): array
    {
        return [
            WithMetaInterfaceTypeResolver::class,
        ];
    }
    /**
     * @return string[]
     */
    public function getFieldNamesToImplement(): array
    {
        return [
            'metaKeys',
            'metaValue',
            'metaValues',
            'meta',
        ];
    }

    public function getFieldTypeResolver(string $fieldName): ConcreteTypeResolverInterface
    {
        return match ($fieldName) {
            'metaKeys'
                => $this->getStringScalarTypeResolver(),
            'metaValue',
            'metaValues'
                => $this->getAnyScalarScalarTypeResolver(),
            'meta'
                => $this->getListValueJSONObjectScalarTypeResolver(),
            default
                => parent::getFieldTypeResolver($fieldName),
        };
    }

    public function getFieldTypeModifiers(string $fieldName): int
    {
        return match ($fieldName) {
            'metaKeys',
            'metaValues'
                => SchemaTypeModifiers::IS_ARRAY,
            'meta'
                => SchemaTypeModifiers::NON_NULLABLE,
            default
                => parent::getFieldTypeModifiers($fieldName),
        };
    }

    /**
     * @return array<string,InputTypeResolverInterface>
     */
    public function getFieldArgNameTypeResolvers(string $fieldName): array
    {
        return match ($fieldName) {
            'metaKeys' => [
                'filter' => $this->getMetaKeysFilterInputObjectTypeResolver(),
            ],
            'metaValue',
            'metaValues' => [
                'key' => $this->getStringScalarTypeResolver(),
            ],
            'meta' => [
                'keys' => $this->getStringScalarTypeResolver(),
            ],
            default => parent::getFieldArgNameTypeResolvers($fieldName),
        };
    }

    public function getFieldArgDescription(string $fieldName, string $fieldArgName): ?string
    {
        return match ([$fieldName => $fieldArgName]) {
            ['metaKeys' => 'filter']
                => $this->__('Input to filter meta keys', 'gatographql'),
            ['metaValue' => 'key'],
            ['metaValues' => 'key']
                => $this->__('The meta key', 'meta'),
            ['meta' => 'keys']
                => $this->__('The meta keys', 'meta'),
            default
                => parent::getFieldArgDescription($fieldName, $fieldArgName),
        };
    }

    public function getFieldArgTypeModifiers(string $fieldName, string $fieldArgName): int
    {
        return match ([$fieldName => $fieldArgName]) {
            ['metaValue' => 'key'],
            ['metaValues' => 'key']
                => SchemaTypeModifiers::MANDATORY,
            ['meta' => 'keys']
                => SchemaTypeModifiers::MANDATORY | SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY,
            default
                => parent::getFieldArgTypeModifiers($fieldName, $fieldArgName),
        };
    }

    public function getFieldDescription(string $fieldName): ?string
    {
        return match ($fieldName) {
            'metaKeys' => $this->__('List of allowed meta keys set on the entity.', 'custompostmeta'),
            'metaValue' => $this->__('Single meta value. If the key is not allowed, it returns an error; if the key is non-existent, or the value is empty, it returns `null`; otherwise, it returns the meta value.', 'custompostmeta'),
            'metaValues' => $this->__('List of meta values. If the key is not allowed, it returns an error; if the key is non-existent, or the value is empty, it returns `null`; otherwise, it returns the meta value.', 'custompostmeta'),
            'meta' => $this->__('JSON object, with key the meta key, and value an array of values (a scalar value is returned as an array with 1 item).', 'custompostmeta'),
            default => parent::getFieldDescription($fieldName),
        };
    }
}
