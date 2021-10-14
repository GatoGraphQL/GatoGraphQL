<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FieldResolvers\InterfaceType;

use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;

interface InterfaceTypeFieldSchemaDefinitionResolverInterface
{
    public function getFieldNamesToResolve(): array;
    public function getFieldTypeResolver(string $fieldName): ConcreteTypeResolverInterface;
    public function getFieldDescription(string $fieldName): ?string;
    public function getFieldTypeModifiers(string $fieldName): int;
    public function getFieldDeprecationMessage(string $fieldName): ?string;
    /**
     * @return array<string, InputTypeResolverInterface>
     */
    public function getFieldArgNameTypeResolvers(string $fieldName): array;
    public function getFieldArgDescription(string $fieldName, string $fieldArgName): ?string;
    public function getFieldArgDefaultValue(string $fieldName, string $fieldArgName): mixed;
    public function getFieldArgTypeModifiers(string $fieldName, string $fieldArgName): int;
    /**
     * Watch out! The GraphQL spec does not include deprecations for arguments,
     * only for fields and enum values, but here it is added nevertheless.
     * This message is shown on runtime when executing a query with a deprecated field,
     * but it's not shown when doing introspection.
     *
     * @see https://spec.graphql.org/draft/#sec-Schema-Introspection.Schema-Introspection-Schema
     */
    public function getFieldArgDeprecationMessage(string $fieldName, string $fieldArgName): ?string;
    /**
     * @return array<string, InputTypeResolverInterface>
     */
    public function getConsolidatedFieldArgNameTypeResolvers(string $fieldName): array;
    public function getConsolidatedFieldArgDescription(string $fieldName, string $fieldArgName): ?string;
    public function getConsolidatedFieldArgDefaultValue(string $fieldName, string $fieldArgName): mixed;
    public function getConsolidatedFieldArgTypeModifiers(string $fieldName, string $fieldArgName): int;
    public function getConsolidatedFieldArgDeprecationMessage(string $fieldName, string $fieldArgName): ?string;
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
}
