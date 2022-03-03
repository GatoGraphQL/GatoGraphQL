<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FieldResolvers\ObjectType;

use PoP\ComponentModel\Feedback\FeedbackItemResolution;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\FieldResolvers\FieldResolverInterface;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

interface ObjectTypeFieldResolverInterface extends FieldResolverInterface, ObjectTypeFieldSchemaDefinitionResolverInterface
{
    /**
     * The classes of the ObjectTypeResolvers this ObjectTypeFieldResolver adds fields to.
     * The list can contain both concrete and abstract classes (in which case all classes
     * extending from them will be selected)
     *
     * @return string[]
     */
    public function getObjectTypeResolverClassesToAttachTo(): array;
    /**
     * Obtain the fieldNames from all implemented interfaces
     */
    public function getFieldNamesFromInterfaces(): array;
    /**
     * Fields may not be directly visible in the schema,
     * eg: because they are used only by the application, and must not
     * be exposed to the user (eg: "accessControlLists")
     */
    public function skipExposingFieldInSchema(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): bool;
    public function skipExposingFieldArgInSchema(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): bool;
    /**
     * @return array<string, mixed>
     */
    public function getFieldSchemaDefinition(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, array $fieldArgs): array;
    public function getFieldVersion(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string;
    public function hasFieldVersion(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): bool;
    public function getFieldVersionInputTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?InputTypeResolverInterface;
    /**
     * Indicate if the fields are global (i.e. they apply to all typeResolvers)
     */
    public function isGlobal(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): bool;

    /**
     * Indicates if the fieldResolver can process this combination of fieldName and fieldArgs
     * It is required to support a multiverse of fields: different fieldResolvers can resolve the field, based on the required version (passed through $fieldArgs['branch'])
     *
     * @param array<string, mixed> $fieldArgs
     */
    public function resolveCanProcess(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, array $fieldArgs): bool;
    public function collectFieldValidationErrorDescriptions(
        ObjectTypeResolverInterface $objectTypeResolver,
        string $fieldName,
        array $fieldArgs,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void;
    public function collectFieldValidationDeprecationMessages(
        ObjectTypeResolverInterface $objectTypeResolver,
        string $fieldName,
        array $fieldArgs,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void;
    /**
     * @param array<string, mixed> $fieldArgs
     * @param array<string, mixed> $variables
     * @param array<string, mixed> $expressions
     * @param array<string, mixed> $options
     */
    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        string $fieldName,
        array $fieldArgs,
        array $variables,
        array $expressions,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
        array $options = []
    ): mixed;
    /**
     * Indicate if to validate the type of the response
     */
    public function validateResolvedFieldType(
        ObjectTypeResolverInterface $objectTypeResolver,
        string $fieldName,
        array $fieldArgs,
    ): bool;
    /**
     * The mutation can be validated either on the schema (`false`)
     * on on the object (`true`)
     */
    public function validateMutationOnObject(
        ObjectTypeResolverInterface $objectTypeResolver,
        string $fieldName
    ): bool;
    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface;
    public function getFieldMutationResolver(
        ObjectTypeResolverInterface $objectTypeResolver,
        string $fieldName
    ): ?MutationResolverInterface;
    /**
     * @return FeedbackItemResolution[]
     */
    public function resolveFieldValidationWarnings(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, array $fieldArgs): array;
    /**
     * @param array<string, mixed> $fieldArgs
     */
    public function resolveCanProcessObject(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        string $fieldName,
        array $fieldArgs
    ): bool;
    public function enableOrderedSchemaFieldArgs(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): bool;
    /**
     * @param array<string, mixed> $fieldArgs
     */
    public function collectValidationErrorDescriptions(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        string $fieldName,
        array $fieldArgs,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void;
    /**
     * Define if to use the version to decide if to process the field or not
     */
    public function decideCanProcessBasedOnVersionConstraint(ObjectTypeResolverInterface $objectTypeResolver): bool;
}
