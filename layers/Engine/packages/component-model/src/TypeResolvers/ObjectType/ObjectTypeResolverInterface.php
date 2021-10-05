<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolvers\ObjectType;

use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;

interface ObjectTypeResolverInterface extends RelationalTypeResolverInterface
{
    public function validateFieldArgumentsForSchema(string $field, array $fieldArgs, array &$schemaErrors, array &$schemaWarnings, array &$schemaDeprecations): array;
    /**
     * @return array<string,mixed>|null `null` if there are no ObjectTypeFieldResolvers for the field
     */
    public function getSchemaFieldArgs(string $field): ?array;
    public function enableOrderedSchemaFieldArgs(string $field): bool;
    public function getFieldSchemaDefinition(string $field): ?array;
    public function hasObjectTypeFieldResolversForField(string $field): bool;
    public function resolveFieldValidationErrorDescriptions(string $field, array &$variables = null): array;
    public function resolveFieldValidationWarningDescriptions(string $field, array &$variables = null): array;
    public function resolveFieldDeprecationDescriptions(string $field, array &$variables = null): array;
    public function getFieldTypeResolver(string $field): ?ConcreteTypeResolverInterface;
    public function getFieldMutationResolver(string $field): ?MutationResolverInterface;
    public function isFieldAMutation(string $field): ?bool;
    public function getAllMandatoryDirectivesForFields(): array;
}
