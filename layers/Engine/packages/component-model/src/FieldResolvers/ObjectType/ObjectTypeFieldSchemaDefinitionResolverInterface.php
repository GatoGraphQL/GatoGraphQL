<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FieldResolvers\ObjectType;

use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

interface ObjectTypeFieldSchemaDefinitionResolverInterface
{
    public function getFieldNamesToResolve(): array;
    public function getAdminFieldNames(): array;
    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface;
    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string;
    public function getFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): int;
    public function getFieldDeprecationDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, array $fieldArgs = []): ?string;
    /**
     * Define Schema Field Arguments
     *
     * @return array<string, InputTypeResolverInterface>
     */
    public function getFieldArgNameResolvers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array;
    public function getFieldArgDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): ?string;
    public function getFieldArgDefaultValue(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): mixed;
    public function getFieldArgTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): int;
    /**
     * Invoke Schema Field Arguments
     *
     * @return array<string, InputTypeResolverInterface>
     */
    public function getConsolidatedFieldArgNameResolvers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array;
    public function getConsolidatedFieldArgDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): ?string;
    public function getConsolidatedFieldArgDefaultValue(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): mixed;
    public function getConsolidatedFieldArgTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): int;
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
    public function addFieldSchemaDefinition(array &$schemaDefinition, ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): void;
}
