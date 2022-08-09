<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FieldResolvers\ObjectType;

use PoP\ComponentModel\Checkpoints\CheckpointInterface;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\FieldResolvers\FieldResolverInterface;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;

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
    public function getFieldSchemaDefinition(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array;
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
     */
    public function resolveCanProcess(
        ObjectTypeResolverInterface $objectTypeResolver,
        FieldInterface $field,
    ): bool;
    public function collectFieldValidationDeprecationMessages(
        ObjectTypeResolverInterface $objectTypeResolver,
        FieldInterface $field,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void;
    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): mixed;
    /**
     * Indicate if to validate the type of the response
     */
    public function validateResolvedFieldType(
        ObjectTypeResolverInterface $objectTypeResolver,
        FieldInterface $field,
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
    public function enableOrderedSchemaFieldArgs(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): bool;
    public function validateFieldArgsForObject(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void;
    /**
     * Define if to use the version to decide if to process the field or not
     */
    public function decideCanProcessBasedOnVersionConstraint(ObjectTypeResolverInterface $objectTypeResolver): bool;
    /**
     * Apply customizations to the field data
     *
     * @param array<string,mixed> $fieldArgs
     * @return array<string,mixed>|null null in case of validation error
     */
    public function prepareFieldData(
        array $fieldArgs,
        ObjectTypeResolverInterface $objectTypeResolver,
        FieldInterface $field,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): ?array;
    /**
     * @param array<string,mixed> $fieldDataForObject
     * @return array<string,mixed>
     */
    public function prepareFieldDataForObject(
        array $fieldDataForObject,
        ObjectTypeResolverInterface $objectTypeResolver,
        FieldInterface $field,
        object $object,
    ): array;
    /**
     * This method is executed AFTER the casting of the fieldArgs
     * has taken place! Then, it can further add elements to the
     * input which are not in the Schema definition of the input.
     *
     * It's use is with nested mutations, as to set the missing
     * "id" value that comes from the object, and is not provided
     * via an input to the mutation.
     *
     * @param array<string,mixed> $fieldDataForMutationForObject
     * @return array<string,mixed>
     */
    public function prepareFieldDataForMutationForObject(
        array $fieldDataForMutationForObject,
        ObjectTypeResolverInterface $objectTypeResolver,
        FieldInterface $field,
        object $object,
    ): array;
    /**
     * Indicate: if the field has a single field argument, which is of type InputObject,
     * then retrieve the value for its input fields?
     *
     * By default, that's the case with mutations, as they pass a single input
     * under name "input".
     */
    public function extractInputObjectFieldForMutation(
        ObjectTypeResolverInterface $objectTypeResolver,
        string $fieldName,
    ): bool;
    /**
     * If the field has a single argument, which is of type InputObject,
     * then retrieve the value for its input fields.
     */
    public function getFieldDataInputObjectSubpropertyName(
        ObjectTypeResolverInterface $objectTypeResolver,
        FieldInterface $field,
    ): ?string;
    /**
     * Custom validations
     */
    public function validateFieldKeyValues(
        ObjectTypeResolverInterface $objectTypeResolver,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void;
    /**
     * @return CheckpointInterface[]
     */
    public function getValidationCheckpoints(
        ObjectTypeResolverInterface $objectTypeResolver,
        FieldDataAccessorInterface $fieldDataAccessor,
        object $object,
    ): array;
}
