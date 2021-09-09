<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Resolvers;

use PoP\ComponentModel\FieldInterfaceResolvers\FieldInterfaceSchemaDefinitionResolverInterface;
use PoP\ComponentModel\FieldResolvers\FieldSchemaDefinitionResolverInterface;
use PoP\ComponentModel\TypeResolvers\Object\ObjectTypeResolverInterface;

/**
 * A TypeResolver may be useful when retrieving the schema from a FieldResolver,
 * but it cannot be used with a FieldInterfaceResolver.
 * Hence, this adapter receives function calls to resolve the schema
 * containing a TypeResolver, strips this param, and then calls
 * the corresponding FieldInterfaceResolver.
 */
class InterfaceSchemaDefinitionResolverAdapter implements FieldSchemaDefinitionResolverInterface
{
    public function __construct(protected FieldInterfaceSchemaDefinitionResolverInterface $fieldInterfaceSchemaDefinitionResolver)
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

    public function getSchemaFieldType(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): string
    {
        return $this->fieldInterfaceSchemaDefinitionResolver->getSchemaFieldType($fieldName);
    }

    public function getSchemaFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?int
    {
        return $this->fieldInterfaceSchemaDefinitionResolver->getSchemaFieldTypeModifiers($fieldName);
    }

    public function getSchemaFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return $this->fieldInterfaceSchemaDefinitionResolver->getSchemaFieldDescription($fieldName);
    }

    public function getSchemaFieldArgs(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        return $this->fieldInterfaceSchemaDefinitionResolver->getSchemaFieldArgs($fieldName);
    }

    public function getSchemaFieldDeprecationDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, array $fieldArgs = []): ?string
    {
        return $this->fieldInterfaceSchemaDefinitionResolver->getSchemaFieldDeprecationDescription($fieldName, $fieldArgs);
    }

    public function getFieldTypeResolverClass(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return $this->fieldInterfaceSchemaDefinitionResolver->getFieldTypeResolverClass($fieldName);
    }

    public function validateFieldArgument(
        ObjectTypeResolverInterface $objectTypeResolver,
        string $fieldName,
        string $fieldArgName,
        mixed $fieldArgValue
    ): array {
        return $this->fieldInterfaceSchemaDefinitionResolver->validateFieldArgument($fieldName, $fieldArgName, $fieldArgValue);
    }

    public function addSchemaDefinitionForField(array &$schemaDefinition, ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): void
    {
        $this->fieldInterfaceSchemaDefinitionResolver->addSchemaDefinitionForField($schemaDefinition, $fieldName);
    }
}
