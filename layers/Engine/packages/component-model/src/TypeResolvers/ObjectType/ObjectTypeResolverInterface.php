<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolvers\ObjectType;

use PoP\ComponentModel\FieldResolvers\ObjectType\ObjectTypeFieldResolverInterface;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;

interface ObjectTypeResolverInterface extends RelationalTypeResolverInterface
{
    public function validateFieldArgumentsForSchema(string $field, array $fieldArgs, array &$schemaErrors, array &$schemaWarnings, array &$schemaDeprecations): array;
    public function getFieldSchemaDefinition(string $field): ?array;
    public function hasObjectTypeFieldResolversForField(string $field): bool;
    public function resolveFieldValidationErrorQualifiedEntries(string $field, array &$variables = null): array;
    public function resolveFieldValidationWarningQualifiedEntries(string $field, array &$variables = null): array;
    public function resolveFieldDeprecationQualifiedEntries(string $field, array &$variables = null): array;
    public function getFieldTypeResolver(string $field): ?ConcreteTypeResolverInterface;
    public function getFieldMutationResolver(string $field): ?MutationResolverInterface;
    public function isFieldAMutation(string $field): ?bool;
    public function getAllMandatoryDirectivesForFields(): array;
    /**
     * The "executable" FieldResolver is the first one in the list
     * for each field, as according to their priority.
     *
     * @return array<string, ObjectTypeFieldResolverInterface> Key: fieldName, Value: FieldResolver
     */
    public function getExecutableObjectTypeFieldResolversByField(bool $global): array;
    /**
     * The list of all the FieldResolvers that resolve each field, for
     * every fieldName
     *
     * @return array<string, ObjectTypeFieldResolverInterface[]> Key: fieldName, Value: List of FieldResolvers
     */
    public function getObjectTypeFieldResolversByField(bool $global): array;
}
