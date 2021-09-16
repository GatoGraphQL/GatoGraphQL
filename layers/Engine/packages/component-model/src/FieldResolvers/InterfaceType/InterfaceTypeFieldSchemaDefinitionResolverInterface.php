<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FieldResolvers\InterfaceType;

interface InterfaceTypeFieldSchemaDefinitionResolverInterface
{
    public function getFieldNamesToResolve(): array;
    public function getSchemaFieldType(string $fieldName): string;
    public function getSchemaFieldTypeModifiers(string $fieldName): ?int;
    public function getSchemaFieldDescription(string $fieldName): ?string;
    public function getSchemaFieldArgs(string $fieldName): array;
    public function getSchemaFieldDeprecationDescription(string $fieldName, array $fieldArgs = []): ?string;
    public function getFieldTypeResolverClass(string $fieldName): string;
    /**
     * Validate the constraints for a field argument
     *
     * @return string[] Error messages
     */
    public function validateFieldArgument(
        string $fieldName,
        string $fieldArgName,
        mixed $fieldArgValue
    ): array;
    public function addSchemaDefinitionForField(array &$schemaDefinition, string $fieldName): void;
}
