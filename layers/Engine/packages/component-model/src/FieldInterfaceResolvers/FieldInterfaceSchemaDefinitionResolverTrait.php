<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FieldInterfaceResolvers;

use PoP\ComponentModel\Facades\Schema\SchemaDefinitionServiceFacade;
use PoP\ComponentModel\FieldInterfaceResolvers\FieldInterfaceSchemaDefinitionResolverInterface;
use PoP\ComponentModel\Resolvers\WithVersionConstraintFieldOrDirectiveResolverTrait;

trait FieldInterfaceSchemaDefinitionResolverTrait
{
    use WithVersionConstraintFieldOrDirectiveResolverTrait;

    /**
     * Return the object implementing the schema definition for this fieldResolver
     */
    public function getSchemaDefinitionResolver(): ?FieldInterfaceSchemaDefinitionResolverInterface
    {
        return null;
    }

    public function getSchemaFieldType(string $fieldName): string
    {
        if ($schemaDefinitionResolver = $this->getSchemaDefinitionResolver()) {
            return $schemaDefinitionResolver->getSchemaFieldType($fieldName);
        }
        
        $schemaDefinitionService = SchemaDefinitionServiceFacade::getInstance();
        return $schemaDefinitionService->getDefaultType();
    }

    public function getSchemaFieldTypeModifiers(string $fieldName): ?int
    {
        if ($schemaDefinitionResolver = $this->getSchemaDefinitionResolver()) {
            return $schemaDefinitionResolver->getSchemaFieldTypeModifiers($fieldName);
        }
        return null;
    }

    public function getSchemaFieldDescription(string $fieldName): ?string
    {
        if ($schemaDefinitionResolver = $this->getSchemaDefinitionResolver()) {
            return $schemaDefinitionResolver->getSchemaFieldDescription($fieldName);
        }
        return null;
    }

    public function getSchemaFieldArgs(string $fieldName): array
    {
        if ($schemaDefinitionResolver = $this->getSchemaDefinitionResolver()) {
            return $schemaDefinitionResolver->getSchemaFieldArgs($fieldName);
        }
        return [];
    }

    abstract protected function hasSchemaFieldVersion(string $fieldName): bool;

    public function getFilteredSchemaFieldArgs(string $fieldName): array
    {
        if ($schemaDefinitionResolver = $this->getSchemaDefinitionResolver()) {
            $schemaFieldArgs = $schemaDefinitionResolver->getSchemaFieldArgs($fieldName);
        } else {
            $schemaFieldArgs = [];
        }

        /**
         * Add the "versionConstraint" param. Add it at the end, so it doesn't affect the order of params for "orderedSchemaFieldArgs"
         */
        $this->maybeAddVersionConstraintSchemaFieldOrDirectiveArg(
            $schemaFieldArgs,
            $this->hasSchemaFieldVersion($fieldName)
        );
        return $schemaFieldArgs;
    }

    public function getSchemaFieldDeprecationDescription(string $fieldName, array $fieldArgs = []): ?string
    {
        if ($schemaDefinitionResolver = $this->getSchemaDefinitionResolver()) {
            return $schemaDefinitionResolver->getSchemaFieldDeprecationDescription($fieldName, $fieldArgs);
        }
        return null;
    }

    public function addSchemaDefinitionForField(array &$schemaDefinition, string $fieldName): void
    {
        if ($schemaDefinitionResolver = $this->getSchemaDefinitionResolver()) {
            $schemaDefinitionResolver->addSchemaDefinitionForField($schemaDefinition, $fieldName);
        }
    }
}
