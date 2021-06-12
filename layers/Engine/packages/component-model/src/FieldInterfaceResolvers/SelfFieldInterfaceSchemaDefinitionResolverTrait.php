<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FieldInterfaceResolvers;

use PoP\ComponentModel\Facades\Schema\SchemaDefinitionServiceFacade;
use PoP\ComponentModel\FieldInterfaceResolvers\FieldInterfaceSchemaDefinitionResolverInterface;
use PoP\ComponentModel\Resolvers\WithVersionConstraintFieldOrDirectiveResolverTrait;

trait SelfFieldInterfaceSchemaDefinitionResolverTrait
{
    use WithVersionConstraintFieldOrDirectiveResolverTrait;

    /**
     * The object resolves its own schema definition
     */
    public function getSchemaDefinitionResolver(): ?FieldInterfaceSchemaDefinitionResolverInterface
    {
        return $this;
    }

    public function getSchemaFieldType(string $fieldName): string
    {
        $schemaDefinitionService = SchemaDefinitionServiceFacade::getInstance();
        return $schemaDefinitionService->getDefaultType();
    }

    public function getSchemaFieldTypeModifiers(string $fieldName): ?int
    {
        return null;
    }

    public function getSchemaFieldDescription(string $fieldName): ?string
    {
        return null;
    }

    public function getSchemaFieldArgs(string $fieldName): array
    {
        return [];
    }

    abstract protected function hasSchemaFieldVersion(string $fieldName): bool;

    public function getFilteredSchemaFieldArgs(string $fieldName): array
    {
        $schemaFieldArgs = $this->getSchemaFieldArgs($fieldName);
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
        return null;
    }

    public function addSchemaDefinitionForField(array &$schemaDefinition, string $fieldName): void
    {
    }
}
