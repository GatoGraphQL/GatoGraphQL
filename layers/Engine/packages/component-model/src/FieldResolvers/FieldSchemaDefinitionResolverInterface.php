<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FieldResolvers;

use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;

interface FieldSchemaDefinitionResolverInterface
{
    public function getFieldNamesToResolve(): array;
    public function getAdminFieldNames(): array;
    public function getSchemaFieldType(RelationalTypeResolverInterface $typeResolver, string $fieldName): string;
    public function getSchemaFieldTypeModifiers(RelationalTypeResolverInterface $typeResolver, string $fieldName): ?int;
    public function getSchemaFieldDescription(RelationalTypeResolverInterface $typeResolver, string $fieldName): ?string;
    public function getSchemaFieldArgs(RelationalTypeResolverInterface $typeResolver, string $fieldName): array;
    public function getSchemaFieldDeprecationDescription(RelationalTypeResolverInterface $typeResolver, string $fieldName, array $fieldArgs = []): ?string;
    /**
     * Validate the constraints for a field argument
     *
     * @return string[] Error messages
     */
    public function validateFieldArgument(
        RelationalTypeResolverInterface $typeResolver,
        string $fieldName,
        string $fieldArgName,
        mixed $fieldArgValue
    ): array;
    public function addSchemaDefinitionForField(array &$schemaDefinition, RelationalTypeResolverInterface $typeResolver, string $fieldName): void;
}
