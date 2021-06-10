<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Resolvers;

use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\FieldResolvers\FieldSchemaDefinitionResolverInterface;
use PoP\ComponentModel\FieldInterfaceResolvers\FieldInterfaceResolverInterface;

/**
 * A TypeResolver may be useful when retrieving the schema from a FieldResolver,
 * but it cannot be used with a FieldInterfaceResolver.
 * Hence, this adapter receives function calls to resolve the schema
 * containing a TypeResolver, strips this param, and then calls
 * the corresponding FieldInterfaceResolver.
 */
class InterfaceSchemaDefinitionResolverAdapter implements FieldSchemaDefinitionResolverInterface
{
    public function __construct(private FieldInterfaceResolverInterface $fieldInterfaceResolver)
    {
    }

    /**
     * This function will never be called for the Adapter,
     * but must be implemented to satisfy the interface
     */
    public function getFieldNamesToResolve(): array
    {
        return [];
    }

    /**
     * This function will never be called for the Adapter,
     * but must be implemented to satisfy the interface
     */
    public function getAdminFieldNames(): array
    {
        return [];
    }

    public function getSchemaFieldType(TypeResolverInterface $typeResolver, string $fieldName): string
    {
        return $this->fieldInterfaceResolver->getSchemaFieldType($fieldName);
    }

    public function isSchemaFieldResponseNonNullable(TypeResolverInterface $typeResolver, string $fieldName): bool
    {
        return $this->fieldInterfaceResolver->isSchemaFieldResponseNonNullable($fieldName);
    }

    public function getSchemaFieldDescription(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        return $this->fieldInterfaceResolver->getSchemaFieldDescription($fieldName);
    }

    public function getSchemaFieldArgs(TypeResolverInterface $typeResolver, string $fieldName): array
    {
        return $this->fieldInterfaceResolver->getSchemaFieldArgs($fieldName);
    }

    public function getFilteredSchemaFieldArgs(TypeResolverInterface $typeResolver, string $fieldName): array
    {
        return $this->fieldInterfaceResolver->getFilteredSchemaFieldArgs($fieldName);
    }

    public function getSchemaFieldDeprecationDescription(TypeResolverInterface $typeResolver, string $fieldName, array $fieldArgs = []): ?string
    {
        return $this->fieldInterfaceResolver->getSchemaFieldDeprecationDescription($fieldName, $fieldArgs);
    }

    public function addSchemaDefinitionForField(array &$schemaDefinition, TypeResolverInterface $typeResolver, string $fieldName): void
    {
        $this->fieldInterfaceResolver->addSchemaDefinitionForField($schemaDefinition, $fieldName);
    }
}
