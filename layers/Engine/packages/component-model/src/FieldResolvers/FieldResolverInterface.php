<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FieldResolvers;

use PoP\ComponentModel\AttachableExtensions\AttachableExtensionInterface;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\ComponentModel\FieldResolvers\FieldSchemaDefinitionResolverInterface;

interface FieldResolverInterface extends AttachableExtensionInterface
{
    /**
     * Get an array with the fieldNames that this fieldResolver resolves
     */
    public function getFieldNamesToResolve(): array;
    /**
     * Those fieldNames to be enabled for the "Admin" schema only
     */
    public function getAdminFieldNames(): array;
    /**
     * A list of classes of all the (GraphQL-style) interfaces the fieldResolver implements
     */
    public function getImplementedFieldInterfaceResolverClasses(): array;
    /**
     * Obtain the fieldNames from all implemented interfaces
     */
    public function getFieldNamesFromInterfaces(): array;
    /**
     * Get an instance of the object defining the schema for this fieldResolver
     */
    public function getSchemaDefinitionResolver(RelationalTypeResolverInterface $relationalTypeResolver): ?FieldSchemaDefinitionResolverInterface;
    /**
     * Fields may not be directly visible in the schema,
     * eg: because they are used only by the application, and must not
     * be exposed to the user (eg: "accessControlLists")
     */
    public function skipAddingToSchemaDefinition(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): bool;
    public function getSchemaDefinitionResolverForField(RelationalTypeResolverInterface $relationalTypeResolver, string $field): ?FieldSchemaDefinitionResolverInterface;
    public function getSchemaDefinitionForField(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName, array $fieldArgs = []): array;
    public function getSchemaFieldVersion(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): ?string;
    /**
     * Indicate if the fields are global (i.e. they apply to all typeResolvers)
     */
    public function isGlobal(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): bool;

    /**
     * Indicates if the fieldResolver can process this combination of fieldName and fieldArgs
     * It is required to support a multiverse of fields: different fieldResolvers can resolve the field, based on the required version (passed through $fieldArgs['branch'])
     *
     * @param array<string, mixed> $fieldArgs
     */
    public function resolveCanProcess(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName, array $fieldArgs = []): bool;
    public function resolveSchemaValidationErrorDescriptions(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName, array $fieldArgs = []): ?array;
    public function resolveSchemaValidationDeprecationDescriptions(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName, array $fieldArgs = []): ?array;
    /**
     * @param array<string, mixed> $fieldArgs
     * @param array<string, mixed>|null $variables
     * @param array<string, mixed>|null $expressions
     * @param array<string, mixed> $options
     */
    public function resolveValue(
        RelationalTypeResolverInterface $relationalTypeResolver,
        object $resultItem,
        string $fieldName,
        array $fieldArgs = [],
        ?array $variables = null,
        ?array $expressions = null,
        array $options = []
    ): mixed;
    /**
     * The mutation can be validated either on the schema (`false`)
     * on on the resultItem (`true`)
     */
    public function validateMutationOnResultItem(
        RelationalTypeResolverInterface $relationalTypeResolver,
        string $fieldName
    ): bool;
    public function resolveFieldTypeResolverClass(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): ?string;
    public function resolveFieldMutationResolverClass(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): ?string;
    public function resolveSchemaValidationWarningDescriptions(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName, array $fieldArgs = []): ?array;
    /**
     * @param array<string, mixed> $fieldArgs
     */
    public function resolveCanProcessResultItem(
        RelationalTypeResolverInterface $relationalTypeResolver,
        object $resultItem,
        string $fieldName,
        array $fieldArgs = []
    ): bool;
    public function enableOrderedSchemaFieldArgs(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): bool;
    /**
     * @param array<string, mixed> $fieldArgs
     */
    public function getValidationErrorDescriptions(
        RelationalTypeResolverInterface $relationalTypeResolver,
        object $resultItem,
        string $fieldName,
        array $fieldArgs = []
    ): ?array;
    /**
     * Define if to use the version to decide if to process the field or not
     */
    public function decideCanProcessBasedOnVersionConstraint(RelationalTypeResolverInterface $relationalTypeResolver): bool;
}
