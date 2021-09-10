<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolvers;

use PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface;
use PoP\ComponentModel\TypeResolvers\InterfaceType\InterfaceTypeResolverInterface;

interface RelationalTypeResolverInterface extends TypeResolverInterface
{
    /**
     * All objects MUST have an ID. `null` is supported for the UnionTypeResolver,
     * when it cannot find a resolver to handle the object.
     *
     * @return string|int|null the ID of the passed object, or `null` if there is no resolver to handle it (for the UnionTypeResolver)
     */
    public function getID(object $resultItem): string | int | null;
    public function getRelationalTypeDataLoaderClass(): string;
    /**
     * @return string[]
     */
    public function getAllImplementedInterfaceTypeResolverClasses(): array;
    /**
     * @return InterfaceTypeResolverInterface[]
     */
    public function getAllImplementedInterfaceTypeResolvers(): array;
    /**
     * @param string|int|array<string|int> $dbObjectIDOrIDs
     * @return string|int|array<string|int>
     */
    public function getQualifiedDBObjectIDOrIDs(string | int | array $dbObjectIDOrIDs): string | int | array;
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
    public function getFieldTypeResolverClass(string $field): ?string;
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
    /**
     * @return array<string,DirectiveResolverInterface>|null
     */
    public function getDirectiveResolverInstancesForDirective(string $fieldDirective, array $fieldDirectiveFields, array &$variables): ?array;
}
