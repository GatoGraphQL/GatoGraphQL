<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FieldResolvers;

use PoP\ComponentModel\TypeResolvers\ObjectTypeResolverInterface;

interface FieldSchemaDefinitionResolverInterface
{
    public function getFieldNamesToResolve(): array;
    public function getAdminFieldNames(): array;
    public function getSchemaFieldType(ObjectTypeResolverInterface $typeResolver, string $fieldName): string;
    public function getSchemaFieldTypeModifiers(ObjectTypeResolverInterface $typeResolver, string $fieldName): ?int;
    public function getSchemaFieldDescription(ObjectTypeResolverInterface $typeResolver, string $fieldName): ?string;
    public function getSchemaFieldArgs(ObjectTypeResolverInterface $typeResolver, string $fieldName): array;
    public function getSchemaFieldDeprecationDescription(ObjectTypeResolverInterface $typeResolver, string $fieldName, array $fieldArgs = []): ?string;
    /**
     * Validate the constraints for a field argument
     *
     * @return string[] Error messages
     */
    public function validateFieldArgument(
        ObjectTypeResolverInterface $typeResolver,
        string $fieldName,
        string $fieldArgName,
        mixed $fieldArgValue
    ): array;
    public function addSchemaDefinitionForField(array &$schemaDefinition, ObjectTypeResolverInterface $typeResolver, string $fieldName): void;
}
