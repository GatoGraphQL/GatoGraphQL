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

    public function getSchemaFieldDeprecationDescription(string $fieldName, array $fieldArgs = []): ?string
    {
        return null;
    }

    /**
     * Validate the constraints for a field argument
     *
     * @return string[] Error messages
     */
    public function validateFieldArgument(
        string $fieldName,
        string $fieldArgName,
        mixed $fieldArgValue
    ): array {
        return [];
    }

    public function addSchemaDefinitionForField(array &$schemaDefinition, string $fieldName): void
    {
    }
}
