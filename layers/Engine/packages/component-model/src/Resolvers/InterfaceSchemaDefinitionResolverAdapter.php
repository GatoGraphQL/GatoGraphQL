<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Resolvers;

use PoP\Root\Feedback\FeedbackItemResolution;
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

    public function getFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): int
    {
        return $this->interfaceTypeFieldSchemaDefinitionResolver->getFieldTypeModifiers($fieldName);
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return $this->interfaceTypeFieldSchemaDefinitionResolver->getFieldDescription($fieldName);
    }

    public function getFieldArgNameTypeResolvers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        return $this->interfaceTypeFieldSchemaDefinitionResolver->getFieldArgNameTypeResolvers($fieldName);
    }

    public function getAdminFieldArgNames(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        return $this->interfaceTypeFieldSchemaDefinitionResolver->getAdminFieldArgNames($fieldName);
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

    public function getConsolidatedFieldArgNameTypeResolvers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        return $this->interfaceTypeFieldSchemaDefinitionResolver->getConsolidatedFieldArgNameTypeResolvers($fieldName);
    }

    public function getConsolidatedAdminFieldArgNames(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        return $this->interfaceTypeFieldSchemaDefinitionResolver->getConsolidatedAdminFieldArgNames($fieldName);
    }

    public function getConsolidatedFieldArgDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): ?string
    {
        return $this->interfaceTypeFieldSchemaDefinitionResolver->getConsolidatedFieldArgDescription($fieldName, $fieldArgName);
    }

    public function getConsolidatedFieldArgDefaultValue(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): mixed
    {
        return $this->interfaceTypeFieldSchemaDefinitionResolver->getConsolidatedFieldArgDefaultValue($fieldName, $fieldArgName);
    }

    public function getConsolidatedFieldArgTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): int
    {
        return $this->interfaceTypeFieldSchemaDefinitionResolver->getConsolidatedFieldArgTypeModifiers($fieldName, $fieldArgName);
    }

    public function getFieldDeprecationMessage(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return $this->interfaceTypeFieldSchemaDefinitionResolver->getFieldDeprecationMessage($fieldName);
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->interfaceTypeFieldSchemaDefinitionResolver->getFieldTypeResolver($fieldName);
    }

    /**
     * Validate the constraints for a field argument
     *
     * @return FeedbackItemResolution[] Errors
     */
    public function validateFieldArgValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        string $fieldName,
        string $fieldArgName,
        mixed $fieldArgValue
    ): array {
        return $this->interfaceTypeFieldSchemaDefinitionResolver->validateFieldArgValue($fieldName, $fieldArgName, $fieldArgValue);
    }
}
