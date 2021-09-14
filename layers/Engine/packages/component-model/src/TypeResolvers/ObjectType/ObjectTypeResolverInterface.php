<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolvers\ObjectType;

use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;

interface ObjectTypeResolverInterface extends RelationalTypeResolverInterface
{
    public function getSelfFieldTypeResolverClass(): string;
    public function validateFieldArgumentsForSchema(string $field, array $fieldArgs, array &$schemaErrors, array &$schemaWarnings, array &$schemaDeprecations): array;
    /**
     * @return array<string,mixed>|null `null` if there are no ObjectTypeFieldResolvers for the field
     */
    public function getSchemaFieldArgs(string $field): ?array;
    public function enableOrderedSchemaFieldArgs(string $field): bool;
    public function hasObjectTypeFieldResolversForField(string $field): bool;
    public function resolveSchemaValidationErrorDescriptions(string $field, array &$variables = null): array;
    public function resolveSchemaValidationWarningDescriptions(string $field, array &$variables = null): array;
    public function resolveSchemaDeprecationDescriptions(string $field, array &$variables = null): array;
    public function getFieldTypeResolverClass(string $field): ?string;
    public function getFieldMutationResolverClass(string $field): ?string;
    public function isFieldAMutation(string $field): ?bool;
}
