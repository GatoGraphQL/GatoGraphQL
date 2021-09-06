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
     * Return the object implementing the schema definition for this fieldResolver
     */
    public function getSchemaDefinitionResolver(RelationalTypeResolverInterface $relationalTypeResolver): ?FieldSchemaDefinitionResolverInterface
    {
        return null;
    }

    public function getSchemaFieldType(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): string
    {
        if ($schemaDefinitionResolver = $this->getSchemaDefinitionResolver($relationalTypeResolver)) {
            return $schemaDefinitionResolver->getSchemaFieldType($relationalTypeResolver, $fieldName);
        }
        $schemaDefinitionService = SchemaDefinitionServiceFacade::getInstance();
        return $schemaDefinitionService->getDefaultType();
    }

    public function getSchemaFieldTypeModifiers(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): ?int
    {
        if ($schemaDefinitionResolver = $this->getSchemaDefinitionResolver($relationalTypeResolver)) {
            return $schemaDefinitionResolver->getSchemaFieldTypeModifiers($relationalTypeResolver, $fieldName);
        }
        return null;
    }

    public function getSchemaFieldDescription(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): ?string
    {
        if ($schemaDefinitionResolver = $this->getSchemaDefinitionResolver($relationalTypeResolver)) {
            return $schemaDefinitionResolver->getSchemaFieldDescription($relationalTypeResolver, $fieldName);
        }
        return null;
    }

    public function getSchemaFieldArgs(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): array
    {
        if ($schemaDefinitionResolver = $this->getSchemaDefinitionResolver($relationalTypeResolver)) {
            return $schemaDefinitionResolver->getSchemaFieldArgs($relationalTypeResolver, $fieldName);
        }
        return [];
    }

    public function getSchemaFieldDeprecationDescription(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName, array $fieldArgs = []): ?string
    {
        if ($schemaDefinitionResolver = $this->getSchemaDefinitionResolver($relationalTypeResolver)) {
            return $schemaDefinitionResolver->getSchemaFieldDeprecationDescription($relationalTypeResolver, $fieldName, $fieldArgs);
        }
        return null;
    }

    public function resolveFieldTypeResolverClass(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): ?string
    {
        if ($schemaDefinitionResolver = $this->getSchemaDefinitionResolver($relationalTypeResolver)) {
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
        if ($schemaDefinitionResolver = $this->getSchemaDefinitionResolver($relationalTypeResolver)) {
            return $schemaDefinitionResolver->validateFieldArgument($relationalTypeResolver, $fieldName, $fieldArgName, $fieldArgValue);
        }
        return null;
    }

    public function addSchemaDefinitionForField(array &$schemaDefinition, RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): void
    {
        if ($schemaDefinitionResolver = $this->getSchemaDefinitionResolver($relationalTypeResolver)) {
            $schemaDefinitionResolver->addSchemaDefinitionForField($schemaDefinition, $relationalTypeResolver, $fieldName);
        }
    }
}
