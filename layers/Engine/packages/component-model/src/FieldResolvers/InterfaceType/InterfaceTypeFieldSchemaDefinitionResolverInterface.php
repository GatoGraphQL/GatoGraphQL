<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FieldResolvers\InterfaceType;

use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;

interface InterfaceTypeFieldSchemaDefinitionResolverInterface
{
    public function getFieldNamesToResolve(): array;
    public function getSchemaFieldTypeModifiers(string $fieldName): ?int;
    public function getSchemaFieldDescription(string $fieldName): ?string;
    /**
     * @return array<string, InputTypeResolverInterface>
     */
    public function getFieldArgNameResolvers(string $fieldName): array;
    public function getFieldArgDescription(string $fieldName, string $fieldArgName): ?string;
    public function getFieldArgDefaultValue(string $fieldName, string $fieldArgName): mixed;
    public function getFieldArgTypeModifiers(string $fieldName, string $fieldArgName): int;
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
