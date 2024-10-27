<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FieldResolvers\InterfaceType;

use PoP\ComponentModel\TypeResolvers\InterfaceType\InterfaceTypeResolverInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InterfaceType\IdentifiableObjectInterfaceTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\IDScalarTypeResolver;

class IdentifiableObjectInterfaceTypeFieldResolver extends AbstractInterfaceTypeFieldResolver
{
    private ?IDScalarTypeResolver $idScalarTypeResolver = null;

    final protected function getIDScalarTypeResolver(): IDScalarTypeResolver
    {
        if ($this->idScalarTypeResolver === null) {
            /** @var IDScalarTypeResolver */
            $idScalarTypeResolver = $this->instanceManager->getInstance(IDScalarTypeResolver::class);
            $this->idScalarTypeResolver = $idScalarTypeResolver;
        }
        return $this->idScalarTypeResolver;
    }

    /**
     * @return array<class-string<InterfaceTypeResolverInterface>>
     */
    public function getInterfaceTypeResolverClassesToAttachTo(): array
    {
        return [
            IdentifiableObjectInterfaceTypeResolver::class,
        ];
    }

    /**
     * @return string[]
     */
    public function getFieldNamesToImplement(): array
    {
        return [
            'id',
            'globalID',
        ];
    }

    public function getFieldTypeResolver(string $fieldName): ConcreteTypeResolverInterface
    {
        return match ($fieldName) {
            'id',
            'globalID' => $this->getIDScalarTypeResolver(),
            default => parent::getFieldTypeResolver($fieldName),
        };
    }

    public function getFieldTypeModifiers(string $fieldName): int
    {
        return match ($fieldName) {
            'id',
            'globalID' => SchemaTypeModifiers::NON_NULLABLE,
            default => parent::getFieldTypeModifiers($fieldName),
        };
    }

    public function getFieldDescription(string $fieldName): ?string
    {
        return match ($fieldName) {
            'id' => $this->__('The object\'s unique identifier for its type', 'component-model'),
            'globalID' => $this->__('The object\'s globally unique identifier', 'component-model'),
            default => parent::getFieldDescription($fieldName),
        };
    }
}
