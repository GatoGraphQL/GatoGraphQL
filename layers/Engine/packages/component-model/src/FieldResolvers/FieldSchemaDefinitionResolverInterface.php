<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FieldResolvers;

use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;

interface FieldSchemaDefinitionResolverInterface
{
    public function getFieldNamesToResolve(): array;
    public function getAdminFieldNames(): array;
    public function getSchemaFieldType(TypeResolverInterface $typeResolver, string $fieldName): string;
    public function getSchemaFieldTypeModifiers(TypeResolverInterface $typeResolver, string $fieldName): ?int;
    public function getSchemaFieldDescription(TypeResolverInterface $typeResolver, string $fieldName): ?string;
    public function getSchemaFieldArgs(TypeResolverInterface $typeResolver, string $fieldName): array;
    public function getFilteredSchemaFieldArgs(TypeResolverInterface $typeResolver, string $fieldName): array;
    public function getSchemaFieldDeprecationDescription(TypeResolverInterface $typeResolver, string $fieldName, array $fieldArgs = []): ?string;
    public function addSchemaDefinitionForField(array &$schemaDefinition, TypeResolverInterface $typeResolver, string $fieldName): void;
}
