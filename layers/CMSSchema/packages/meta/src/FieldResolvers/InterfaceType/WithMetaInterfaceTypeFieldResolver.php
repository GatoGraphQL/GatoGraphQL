<?php

declare(strict_types=1);

namespace PoPCMSSchema\Meta\FieldResolvers\InterfaceType;

use PoPCMSSchema\Meta\TypeResolvers\InterfaceType\WithMetaInterfaceTypeResolver;
use PoP\ComponentModel\FieldResolvers\InterfaceType\AbstractInterfaceTypeFieldResolver;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InterfaceType\InterfaceTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\AnyBuiltInScalarScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoP\Engine\TypeResolvers\ScalarType\JSONObjectScalarTypeResolver;

class WithMetaInterfaceTypeFieldResolver extends AbstractInterfaceTypeFieldResolver
{
    private ?AnyBuiltInScalarScalarTypeResolver $anyBuiltInScalarScalarTypeResolver = null;
    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;
    private ?JSONObjectScalarTypeResolver $jsonObjectScalarTypeResolver = null;

    final protected function getAnyBuiltInScalarScalarTypeResolver(): AnyBuiltInScalarScalarTypeResolver
    {
        if ($this->anyBuiltInScalarScalarTypeResolver === null) {
            /** @var AnyBuiltInScalarScalarTypeResolver */
            $anyBuiltInScalarScalarTypeResolver = $this->instanceManager->getInstance(AnyBuiltInScalarScalarTypeResolver::class);
            $this->anyBuiltInScalarScalarTypeResolver = $anyBuiltInScalarScalarTypeResolver;
        }
        return $this->anyBuiltInScalarScalarTypeResolver;
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
    final protected function getJSONObjectScalarTypeResolver(): JSONObjectScalarTypeResolver
    {
        if ($this->jsonObjectScalarTypeResolver === null) {
            /** @var JSONObjectScalarTypeResolver */
            $jsonObjectScalarTypeResolver = $this->instanceManager->getInstance(JSONObjectScalarTypeResolver::class);
            $this->jsonObjectScalarTypeResolver = $jsonObjectScalarTypeResolver;
        }
        return $this->jsonObjectScalarTypeResolver;
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
            'jsonMeta',
        ];
    }

    public function getFieldTypeResolver(string $fieldName): ConcreteTypeResolverInterface
    {
        return match ($fieldName) {
            'metaKeys',
            'metaValue' =>
                $this->getStringScalarTypeResolver(),
            'metaValues'
                => $this->getAnyBuiltInScalarScalarTypeResolver(),
            'jsonMeta'
                => $this->getJSONObjectScalarTypeResolver(),
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
            'metaValue',
            'metaValues' => [
                'key' => $this->getStringScalarTypeResolver(),
            ],
            'jsonMeta' => [
                'keys' => $this->getStringScalarTypeResolver(),
            ],
            default => parent::getFieldArgNameTypeResolvers($fieldName),
        };
    }

    public function getFieldArgDescription(string $fieldName, string $fieldArgName): ?string
    {
        return match ($fieldArgName) {
            'key' => $this->__('The meta key', 'meta'),
            'keys' => $this->__('The meta keys', 'meta'),
            default => parent::getFieldArgDescription($fieldName, $fieldArgName),
        };
    }

    public function getFieldArgTypeModifiers(string $fieldName, string $fieldArgName): int
    {
        return match ($fieldArgName) {
            'key' => SchemaTypeModifiers::MANDATORY,
            'keys' => SchemaTypeModifiers::MANDATORY | SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY,
            default => parent::getFieldArgTypeModifiers($fieldName, $fieldArgName),
        };
    }

    public function getFieldDescription(string $fieldName): ?string
    {
        return match ($fieldName) {
            'metaKeys' => $this->__('List of allowed meta keys set on the entity.', 'custompostmeta'),
            'metaValue' => $this->__('Single meta value. If the key is not allowed, it returns an error; if the key is non-existent, or the value is empty, it returns `null`; otherwise, it returns the meta value.', 'custompostmeta'),
            'metaValues' => $this->__('List of meta values. If the key is not allowed, it returns an error; if the key is non-existent, or the value is empty, it returns `null`; otherwise, it returns the meta value.', 'custompostmeta'),
            'jsonMeta' => $this->__('JSON object, with all allowed meta entries.', 'custompostmeta'),
            default => parent::getFieldDescription($fieldName),
        };
    }
}
