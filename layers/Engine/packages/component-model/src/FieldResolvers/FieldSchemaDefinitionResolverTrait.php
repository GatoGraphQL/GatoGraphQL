<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FieldResolvers;

use PoP\ComponentModel\Facades\Schema\SchemaDefinitionServiceFacade;
use PoP\ComponentModel\FieldResolvers\FieldSchemaDefinitionResolverInterface;
use PoP\ComponentModel\Resolvers\WithVersionConstraintFieldOrDirectiveResolverTrait;
use PoP\ComponentModel\TypeResolvers\ObjectTypeResolverInterface;

trait FieldSchemaDefinitionResolverTrait
{
    use WithVersionConstraintFieldOrDirectiveResolverTrait;

    /**
     * Return the object implementing the schema definition for this fieldResolver
     */
    public function getSchemaDefinitionResolver(ObjectTypeResolverInterface $typeResolver): ?FieldSchemaDefinitionResolverInterface
    {
        return null;
    }

    public function getSchemaFieldType(ObjectTypeResolverInterface $typeResolver, string $fieldName): string
    {
        if ($schemaDefinitionResolver = $this->getSchemaDefinitionResolver($typeResolver)) {
            return $schemaDefinitionResolver->getSchemaFieldType($typeResolver, $fieldName);
        }
        $schemaDefinitionService = SchemaDefinitionServiceFacade::getInstance();
        return $schemaDefinitionService->getDefaultType();
    }

    public function getSchemaFieldTypeModifiers(ObjectTypeResolverInterface $typeResolver, string $fieldName): ?int
    {
        if ($schemaDefinitionResolver = $this->getSchemaDefinitionResolver($typeResolver)) {
            return $schemaDefinitionResolver->getSchemaFieldTypeModifiers($typeResolver, $fieldName);
        }
        return null;
    }

    public function getSchemaFieldDescription(ObjectTypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        if ($schemaDefinitionResolver = $this->getSchemaDefinitionResolver($typeResolver)) {
            return $schemaDefinitionResolver->getSchemaFieldDescription($typeResolver, $fieldName);
        }
        return null;
    }

    public function getSchemaFieldArgs(ObjectTypeResolverInterface $typeResolver, string $fieldName): array
    {
        if ($schemaDefinitionResolver = $this->getSchemaDefinitionResolver($typeResolver)) {
            return $schemaDefinitionResolver->getSchemaFieldArgs($typeResolver, $fieldName);
        }
        return [];
    }

    public function getSchemaFieldDeprecationDescription(ObjectTypeResolverInterface $typeResolver, string $fieldName, array $fieldArgs = []): ?string
    {
        if ($schemaDefinitionResolver = $this->getSchemaDefinitionResolver($typeResolver)) {
            return $schemaDefinitionResolver->getSchemaFieldDeprecationDescription($typeResolver, $fieldName, $fieldArgs);
        }
        return null;
    }

    /**
     * Validate the constraints for a field argument
     *
     * @return string[] Error messages
     */
    public function validateFieldArgument(
        ObjectTypeResolverInterface $typeResolver,
        string $fieldName,
        string $fieldArgName,
        mixed $fieldArgValue
    ): array {
        if ($schemaDefinitionResolver = $this->getSchemaDefinitionResolver($typeResolver)) {
            return $schemaDefinitionResolver->validateFieldArgument($typeResolver, $fieldName, $fieldArgName, $fieldArgValue);
        }
        return null;
    }

    public function addSchemaDefinitionForField(array &$schemaDefinition, ObjectTypeResolverInterface $typeResolver, string $fieldName): void
    {
        if ($schemaDefinitionResolver = $this->getSchemaDefinitionResolver($typeResolver)) {
            $schemaDefinitionResolver->addSchemaDefinitionForField($schemaDefinition, $typeResolver, $fieldName);
        }
    }
}
