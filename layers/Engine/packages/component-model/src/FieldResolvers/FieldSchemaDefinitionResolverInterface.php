<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FieldResolvers;

use PoP\ComponentModel\TypeResolvers\Object\ObjectTypeResolverInterface;

interface FieldSchemaDefinitionResolverInterface
{
    public function getFieldNamesToResolve(): array;
    public function getAdminFieldNames(): array;
    public function getSchemaFieldType(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): string;
    public function getSchemaFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?int;
    public function getSchemaFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string;
    public function getSchemaFieldArgs(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array;
    public function getSchemaFieldDeprecationDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, array $fieldArgs = []): ?string;
    public function getFieldTypeResolverClass(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string;
    /**
     * Validate the constraints for a field argument
     *
     * @return string[] Error messages
     */
    public function validateFieldArgument(
        ObjectTypeResolverInterface $objectTypeResolver,
        string $fieldName,
        string $fieldArgName,
        mixed $fieldArgValue
    ): array;
    public function addSchemaDefinitionForField(array &$schemaDefinition, ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): void;
}
