<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Resolvers;

use PoP\ComponentModel\FieldResolvers\InterfaceType\InterfaceTypeFieldSchemaDefinitionResolverInterface;
use PoP\ComponentModel\FieldResolvers\ObjectType\ObjectTypeFieldSchemaDefinitionResolverInterface;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

/**
 * A TypeResolver may be useful when retrieving the schema from a ObjectTypeFieldResolver,
 * but it cannot be used with a InterfaceTypeFieldResolver.
 * Hence, this adapter receives function calls to resolve the schema
 * containing a TypeResolver, strips this param, and then calls
 * the corresponding InterfaceTypeFieldResolver.
 */
class InterfaceSchemaDefinitionResolverAdapter implements ObjectTypeFieldSchemaDefinitionResolverInterface
{
    public function __construct(protected InterfaceTypeFieldSchemaDefinitionResolverInterface $interfaceTypeFieldSchemaDefinitionResolver)
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

    public function getSchemaFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?int
    {
        return $this->interfaceTypeFieldSchemaDefinitionResolver->getSchemaFieldTypeModifiers($fieldName);
    }

    public function getSchemaFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return $this->interfaceTypeFieldSchemaDefinitionResolver->getSchemaFieldDescription($fieldName);
    }

    public function getFieldArgNameResolvers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        return $this->interfaceTypeFieldSchemaDefinitionResolver->getFieldArgNameResolvers($fieldName);
    }

    public function getFieldArgDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): ?string
    {
        return $this->interfaceTypeFieldSchemaDefinitionResolver->getFieldArgDescription($fieldName, $fieldArgName);
    }

    public function getFieldArgDefaultValue(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): mixed
    {
        return $this->interfaceTypeFieldSchemaDefinitionResolver->getFieldArgDefaultValue($fieldName, $fieldArgName);
    }

    public function getFieldArgTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): int
    {
        return $this->interfaceTypeFieldSchemaDefinitionResolver->getFieldArgTypeModifiers($fieldName, $fieldArgName);
    }

    public function getFieldDeprecationDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, array $fieldArgs = []): ?string
    {
        return $this->interfaceTypeFieldSchemaDefinitionResolver->getFieldDeprecationDescription($fieldName, $fieldArgs);
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->interfaceTypeFieldSchemaDefinitionResolver->getFieldTypeResolver($fieldName);
    }

    public function validateFieldArgument(
        ObjectTypeResolverInterface $objectTypeResolver,
        string $fieldName,
        string $fieldArgName,
        mixed $fieldArgValue
    ): array {
        return $this->interfaceTypeFieldSchemaDefinitionResolver->validateFieldArgument($fieldName, $fieldArgName, $fieldArgValue);
    }

    public function addSchemaDefinitionForField(array &$schemaDefinition, ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): void
    {
        $this->interfaceTypeFieldSchemaDefinitionResolver->addSchemaDefinitionForField($schemaDefinition, $fieldName);
    }
}
