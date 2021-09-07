<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Resolvers;

use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
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
    public function __construct(protected FieldInterfaceResolverInterface $fieldInterfaceResolver)
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

    public function getSchemaFieldType(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): string
    {
        return $this->fieldInterfaceResolver->getSchemaFieldType($fieldName);
    }

    public function getSchemaFieldTypeModifiers(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): ?int
    {
        return $this->fieldInterfaceResolver->getSchemaFieldTypeModifiers($fieldName);
    }

    public function getSchemaFieldDescription(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): ?string
    {
        return $this->fieldInterfaceResolver->getSchemaFieldDescription($fieldName);
    }

    public function getSchemaFieldArgs(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): array
    {
        return $this->fieldInterfaceResolver->getSchemaFieldArgs($fieldName);
    }

    public function getSchemaFieldDeprecationDescription(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName, array $fieldArgs = []): ?string
    {
        return $this->fieldInterfaceResolver->getSchemaFieldDeprecationDescription($fieldName, $fieldArgs);
    }

    public function getFieldTypeResolverClass(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): ?string
    {
        return $this->fieldInterfaceResolver->getFieldTypeResolverClass($fieldName);
    }

    public function validateFieldArgument(
        RelationalTypeResolverInterface $relationalTypeResolver,
        string $fieldName,
        string $fieldArgName,
        mixed $fieldArgValue
    ): array {
        return $this->fieldInterfaceResolver->validateFieldArgument($fieldName, $fieldArgName, $fieldArgValue);
    }

    public function addSchemaDefinitionForField(array &$schemaDefinition, RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): void
    {
        $this->fieldInterfaceResolver->addSchemaDefinitionForField($schemaDefinition, $fieldName);
    }
}
