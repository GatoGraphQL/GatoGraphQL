<?php

declare(strict_types=1);

namespace PoPSchema\Meta\FieldResolvers\InterfaceType;

use PoP\ComponentModel\FieldResolvers\InterfaceType\AbstractInterfaceTypeFieldResolver;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\Engine\TypeResolvers\ScalarType\AnyBuiltInScalarScalarTypeResolver;
use PoP\Engine\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoPSchema\Meta\TypeResolvers\InterfaceType\WithMetaInterfaceTypeResolver;

class WithMetaInterfaceTypeFieldResolver extends AbstractInterfaceTypeFieldResolver
{
    private ?AnyBuiltInScalarScalarTypeResolver $anyBuiltInScalarScalarTypeResolver = null;
    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;

    final public function setAnyBuiltInScalarScalarTypeResolver(AnyBuiltInScalarScalarTypeResolver $anyBuiltInScalarScalarTypeResolver): void
    {
        $this->anyBuiltInScalarScalarTypeResolver = $anyBuiltInScalarScalarTypeResolver;
    }
    final protected function getAnyBuiltInScalarScalarTypeResolver(): AnyBuiltInScalarScalarTypeResolver
    {
        return $this->anyBuiltInScalarScalarTypeResolver ??= $this->instanceManager->getInstance(AnyBuiltInScalarScalarTypeResolver::class);
    }
    final public function setStringScalarTypeResolver(StringScalarTypeResolver $stringScalarTypeResolver): void
    {
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
    }
    final protected function getStringScalarTypeResolver(): StringScalarTypeResolver
    {
        return $this->stringScalarTypeResolver ??= $this->instanceManager->getInstance(StringScalarTypeResolver::class);
    }

    public function getInterfaceTypeResolverClassesToAttachTo(): array
    {
        return [
            WithMetaInterfaceTypeResolver::class,
        ];
    }
    public function getFieldNamesToImplement(): array
    {
        return [
            'metaValue',
            'metaValues',
        ];
    }

    public function getFieldTypeResolver(string $fieldName): ConcreteTypeResolverInterface
    {
        return match ($fieldName) {
            'metaValue' => $this->getAnyBuiltInScalarScalarTypeResolver(),
            'metaValues' => $this->getAnyBuiltInScalarScalarTypeResolver(),
            default => parent::getFieldTypeResolver($fieldName),
        };
    }

    public function getFieldTypeModifiers(string $fieldName): int
    {
        return match ($fieldName) {
            'metaValues' => SchemaTypeModifiers::IS_ARRAY,
            default => parent::getFieldTypeModifiers($fieldName),
        };
    }

    public function getFieldArgNameTypeResolvers(string $fieldName): array
    {
        return match ($fieldName) {
            'metaValue',
            'metaValues' => [
                'key' => $this->getStringScalarTypeResolver(),
            ],
            default => parent::getFieldArgNameTypeResolvers($fieldName),
        };
    }

    public function getFieldArgDescription(string $fieldName, string $fieldArgName): ?string
    {
        return match ($fieldArgName) {
            'key' => $this->__('The meta key', 'meta'),
            default => parent::getFieldArgDescription($fieldName, $fieldArgName),
        };
    }

    public function getFieldArgTypeModifiers(string $fieldName, string $fieldArgName): int
    {
        return match ($fieldArgName) {
            'key' => SchemaTypeModifiers::MANDATORY,
            default => parent::getFieldArgTypeModifiers($fieldName, $fieldArgName),
        };
    }

    public function getFieldDescription(string $fieldName): ?string
    {
        return match ($fieldName) {
            'metaValue' => $this->__('Single meta value. If the key is not allowed, it returns an error; if the key is non-existent, or the value is empty, it returns `null`; otherwise, it returns the meta value.', 'custompostmeta'),
            'metaValues' => $this->__('List of meta values. If the key is not allowed, it returns an error; if the key is non-existent, or the value is empty, it returns `null`; otherwise, it returns the meta value.', 'custompostmeta'),
            default => parent::getFieldDescription($fieldName),
        };
    }
}
