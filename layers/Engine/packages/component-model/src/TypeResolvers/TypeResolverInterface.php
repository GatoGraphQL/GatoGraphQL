<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolvers;

use PoP\ComponentModel\DirectivePipeline\DirectivePipelineDecorator;

interface TypeResolverInterface
{
    // Only these 4 functions must be implemented by a new Type class...
    public function getID(object $resultItem): string | int;
    public function getTypeName(): string;
    public function getNamespace(): string;
    public function getNamespacedTypeName(): string;
    public function getMaybeNamespacedTypeName(): string;
    public function getTypeOutputName(): string;
    public function getTypeDataLoaderClass(): string;
    public function getSchemaTypeDescription(): ?string;

    // ... because all functions below have already been implemented in the Abstract base class
    public function getAllImplementedInterfaceClasses(): array;
    /**
     * @return FieldInterfaceResolverInterface[]
     */
    public function getAllImplementedInterfaceResolverInstances(): array;
    /**
     * @param $dbObjectIDOrIDs string|int|array<string|int>
     * @return string|int|array<string|int>
     */
    public function getQualifiedDBObjectIDOrIDs(string | int | array $dbObjectIDOrIDs): string | int | array;
    public function getIdFieldTypeResolverClass(): string;
    /**
     * @return array<string,DirectiveResolverInterface[]>
     */
    public function getDirectiveNameResolvers(): array;
    public function validateFieldArgumentsForSchema(string $field, array $fieldArgs, array &$schemaErrors, array &$schemaWarnings, array &$schemaDeprecations): array;
    public function enqueueFillingResultItemsFromIDs(array $ids_data_fields): void;
    public function fillResultItems(
        array $ids_data_fields,
        array &$unionDBKeyIDs,
        array &$dbItems,
        array &$previousDBItems,
        array &$variables,
        array &$messages,
        array &$dbErrors,
        array &$dbWarnings,
        array &$dbDeprecations,
        array &$dbNotices,
        array &$dbTraces,
        array &$schemaErrors,
        array &$schemaWarnings,
        array &$schemaDeprecations,
        array &$schemaNotices,
        array &$schemaTraces
    ): array;
    public function resolveSchemaValidationErrorDescriptions(string $field, array &$variables = null): array;
    public function resolveSchemaValidationWarningDescriptions(string $field, array &$variables = null): array;
    public function resolveSchemaDeprecationDescriptions(string $field, array &$variables = null): array;
    public function getSchemaFieldArgs(string $field): array;
    public function enableOrderedSchemaFieldArgs(string $field): bool;
    public function resolveFieldTypeResolverClass(string $field): ?string;
    /**
     * @param array<string, mixed>|null $variables
     * @param array<string, mixed>|null $expressions
     * @param array<string, mixed> $options
     */
    public function resolveValue(
        object $resultItem,
        string $field,
        ?array $variables = null,
        ?array $expressions = null,
        array $options = []
    ): mixed;
    public function getSchemaDefinition(array $stackMessages, array &$generalMessages, array $options = []): array;
    public function hasFieldResolversForField(string $field): bool;
    /**
     * Validate and resolve the fieldDirectives into an array, each item containing:
     * 1. the directiveResolverInstance
     * 2. its fieldDirective
     * 3. the fields it affects
     */
    public function resolveDirectivesIntoPipelineData(
        array $fieldDirectives,
        array &$fieldDirectiveFields,
        bool $areNestedDirectives,
        array &$variables,
        array &$schemaErrors,
        array &$schemaWarnings,
        array &$schemaDeprecations,
        array &$schemaNotices,
        array &$schemaTraces
    ): array;
    public function getDirectivePipeline(array $directiveResolverInstances): DirectivePipelineDecorator;
    public function getDirectiveResolverInstanceForDirective(string $fieldDirective, array $fieldDirectiveFields, array &$variables): ?array;
}
