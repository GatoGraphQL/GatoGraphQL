<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FieldResolvers\InterfaceType;

use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;

interface InterfaceTypeFieldSchemaDefinitionResolverInterface
{
    public function getFieldNamesToResolve(): array;
    public function getSchemaFieldTypeModifiers(string $fieldName): ?int;
    public function getSchemaFieldDescription(string $fieldName): ?string;
    /**
     * @return array<string, ConcreteTypeResolverInterface>
     */
    public function getSchemaFieldArgNameResolvers(string $fieldName): array;
    public function getSchemaFieldArgDescription(string $fieldName, string $fieldArgName): ?string;
    public function getSchemaFieldArgDefaultValue(string $fieldName, string $fieldArgName): mixed;
    public function getSchemaFieldArgTypeModifiers(string $fieldName, string $fieldArgName): ?int;
    public function getSchemaFieldDeprecationDescription(string $fieldName, array $fieldArgs = []): ?string;
    public function getFieldTypeResolver(string $fieldName): ConcreteTypeResolverInterface;
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
