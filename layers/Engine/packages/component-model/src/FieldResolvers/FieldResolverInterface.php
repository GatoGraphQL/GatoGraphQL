<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FieldResolvers;

use PoP\ComponentModel\AttachableExtensions\AttachableExtensionInterface;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\FieldResolvers\FieldSchemaDefinitionResolverInterface;

interface FieldResolverInterface extends AttachableExtensionInterface
{
    /**
     * Get an array with the fieldNames that this fieldResolver resolves
     */
    public function getFieldNamesToResolve(): array;
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
    public function getSchemaDefinitionResolver(TypeResolverInterface $typeResolver): ?FieldSchemaDefinitionResolverInterface;
    /**
     * Fields may not be directly visible in the schema,
     * eg: because they are used only by the application, and must not
     * be exposed to the user (eg: "accessControlLists")
     */
    public function skipAddingToSchemaDefinition(TypeResolverInterface $typeResolver, string $fieldName): bool;
    public function getSchemaDefinitionForField(TypeResolverInterface $typeResolver, string $fieldName, array $fieldArgs = []): array;
    public function getSchemaFieldVersion(TypeResolverInterface $typeResolver, string $fieldName): ?string;
    /**
     * Indicate if the fields are global (i.e. they apply to all typeResolvers)
     */
    public function isGlobal(TypeResolverInterface $typeResolver, string $fieldName): bool;

    /**
     * Indicates if the fieldResolver can process this combination of fieldName and fieldArgs
     * It is required to support a multiverse of fields: different fieldResolvers can resolve the field, based on the required version (passed through $fieldArgs['branch'])
     *
     * @param array<string, mixed> $fieldArgs
     */
    public function resolveCanProcess(TypeResolverInterface $typeResolver, string $fieldName, array $fieldArgs = []): bool;
    public function resolveSchemaValidationErrorDescriptions(TypeResolverInterface $typeResolver, string $fieldName, array $fieldArgs = []): ?array;
    public function resolveSchemaValidationDeprecationDescriptions(TypeResolverInterface $typeResolver, string $fieldName, array $fieldArgs = []): ?array;
    /**
     * @param array<string, mixed> $fieldArgs
     * @param array<string, mixed>|null $variables
     * @param array<string, mixed>|null $expressions
     * @param array<string, mixed> $options
     */
    public function resolveValue(
        TypeResolverInterface $typeResolver,
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
        TypeResolverInterface $typeResolver,
        string $fieldName
    ): bool;
    public function resolveFieldTypeResolverClass(TypeResolverInterface $typeResolver, string $fieldName): ?string;
    public function resolveFieldMutationResolverClass(TypeResolverInterface $typeResolver, string $fieldName): ?string;
    public function resolveSchemaValidationWarningDescriptions(TypeResolverInterface $typeResolver, string $fieldName, array $fieldArgs = []): ?array;
    /**
     * @param array<string, mixed> $fieldArgs
     */
    public function resolveCanProcessResultItem(
        TypeResolverInterface $typeResolver,
        object $resultItem,
        string $fieldName,
        array $fieldArgs = []
    ): bool;
    public function enableOrderedSchemaFieldArgs(TypeResolverInterface $typeResolver, string $fieldName): bool;
    /**
     * @param array<string, mixed> $fieldArgs
     */
    public function getValidationErrorDescriptions(
        TypeResolverInterface $typeResolver,
        object $resultItem,
        string $fieldName,
        array $fieldArgs = []
    ): ?array;
    /**
     * Define if to use the version to decide if to process the field or not
     */
    public function decideCanProcessBasedOnVersionConstraint(TypeResolverInterface $typeResolver): bool;
}
