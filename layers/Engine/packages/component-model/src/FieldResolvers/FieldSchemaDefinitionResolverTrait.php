<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FieldResolvers;

use PoP\ComponentModel\Facades\Schema\SchemaDefinitionServiceFacade;
use PoP\ComponentModel\FieldResolvers\FieldSchemaDefinitionResolverInterface;
use PoP\ComponentModel\Resolvers\WithVersionConstraintFieldOrDirectiveResolverTrait;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;

trait FieldSchemaDefinitionResolverTrait
{
    use WithVersionConstraintFieldOrDirectiveResolverTrait;

    /**
     * Return the object implementing the schema definition for this fieldResolver.
     * By default, it is this same object
     */
    public function getSchemaDefinitionResolver(RelationalTypeResolverInterface $relationalTypeResolver): ?FieldSchemaDefinitionResolverInterface
    {
        return $this;
    }

    public function getSchemaFieldType(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): string
    {
        $schemaDefinitionResolver = $this->getSchemaDefinitionResolver($relationalTypeResolver);
        if ($schemaDefinitionResolver !== null && $schemaDefinitionResolver !== $this) {
            return $schemaDefinitionResolver->getSchemaFieldType($relationalTypeResolver, $fieldName);
        }
        $schemaDefinitionService = SchemaDefinitionServiceFacade::getInstance();
        return $schemaDefinitionService->getDefaultType();
    }

    public function getSchemaFieldTypeModifiers(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): ?int
    {
        $schemaDefinitionResolver = $this->getSchemaDefinitionResolver($relationalTypeResolver);
        if ($schemaDefinitionResolver !== null && $schemaDefinitionResolver !== $this) {
            return $schemaDefinitionResolver->getSchemaFieldTypeModifiers($relationalTypeResolver, $fieldName);
        }
        return null;
    }

    public function getSchemaFieldDescription(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): ?string
    {
        $schemaDefinitionResolver = $this->getSchemaDefinitionResolver($relationalTypeResolver);
        if ($schemaDefinitionResolver !== null && $schemaDefinitionResolver !== $this) {
            return $schemaDefinitionResolver->getSchemaFieldDescription($relationalTypeResolver, $fieldName);
        }
        return null;
    }

    public function getSchemaFieldArgs(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): array
    {
        $schemaDefinitionResolver = $this->getSchemaDefinitionResolver($relationalTypeResolver);
        if ($schemaDefinitionResolver !== null && $schemaDefinitionResolver !== $this) {
            return $schemaDefinitionResolver->getSchemaFieldArgs($relationalTypeResolver, $fieldName);
        }
        return [];
    }

    public function getSchemaFieldDeprecationDescription(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName, array $fieldArgs = []): ?string
    {
        $schemaDefinitionResolver = $this->getSchemaDefinitionResolver($relationalTypeResolver);
        if ($schemaDefinitionResolver !== null && $schemaDefinitionResolver !== $this) {
            return $schemaDefinitionResolver->getSchemaFieldDeprecationDescription($relationalTypeResolver, $fieldName, $fieldArgs);
        }
        return null;
    }

    public function resolveFieldTypeResolverClass(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): ?string
    {
        $schemaDefinitionResolver = $this->getSchemaDefinitionResolver($relationalTypeResolver);
        if ($schemaDefinitionResolver !== null && $schemaDefinitionResolver !== $this) {
            return $schemaDefinitionResolver->resolveFieldTypeResolverClass($relationalTypeResolver, $fieldName);
        }
        return null;
    }

    /**
     * Validate the constraints for a field argument
     *
     * @return string[] Error messages
     */
    public function validateFieldArgument(
        RelationalTypeResolverInterface $relationalTypeResolver,
        string $fieldName,
        string $fieldArgName,
        mixed $fieldArgValue
    ): array {
        $schemaDefinitionResolver = $this->getSchemaDefinitionResolver($relationalTypeResolver);
        if ($schemaDefinitionResolver !== null && $schemaDefinitionResolver !== $this) {
            return $schemaDefinitionResolver->validateFieldArgument($relationalTypeResolver, $fieldName, $fieldArgName, $fieldArgValue);
        }
        return null;
    }

    public function addSchemaDefinitionForField(array &$schemaDefinition, RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): void
    {
        $schemaDefinitionResolver = $this->getSchemaDefinitionResolver($relationalTypeResolver);
        if ($schemaDefinitionResolver !== null && $schemaDefinitionResolver !== $this) {
            $schemaDefinitionResolver->addSchemaDefinitionForField($schemaDefinition, $relationalTypeResolver, $fieldName);
        }
    }
}
