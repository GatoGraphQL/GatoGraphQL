<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolvers\ObjectType;

use PoP\ComponentModel\Feedback\EngineIterationFeedbackStore;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\FieldResolvers\ObjectType\ObjectTypeFieldResolverInterface;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\OutputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\Directive;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use SplObjectStorage;

interface ObjectTypeResolverInterface extends RelationalTypeResolverInterface, OutputTypeResolverInterface
{
    public function getFieldSchemaDefinition(FieldInterface $field): ?array;
    public function hasObjectTypeFieldResolversForField(FieldInterface $field): bool;
    public function collectFieldValidationWarnings(
        FieldDataAccessorInterface $fieldDataAccessor,
        array $variables,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void;
    public function collectFieldDeprecations(
        FieldDataAccessorInterface $fieldDataAccessor,
        array $variables,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void;
    public function getFieldTypeResolver(
        FieldInterface $field,
        array $variables,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): ?ConcreteTypeResolverInterface;
    public function getFieldTypeModifiers(
        FieldInterface $field,
        array $variables,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): ?int;
    public function getFieldMutationResolver(
        FieldInterface $field,
        array $variables,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): ?MutationResolverInterface;
    public function isFieldAMutation(FieldInterface|string $fieldOrFieldName): ?bool;
    /**
     * @return array<string,Directive[]>
     */
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
    /**
     * Get the first FieldResolver that resolves the field
     */
    public function getExecutableObjectTypeFieldResolverForField(FieldInterface|string $fieldOrFieldName): ?ObjectTypeFieldResolverInterface;
    /**
     * @param array<string,mixed> $fieldData
     */
    public function createFieldDataAccessor(
        FieldInterface $field,
        array $fieldData,
    ): FieldDataAccessorInterface;
    /**
     * Handle case:
     *
     * 1. Data from a Field in an ObjectTypeResolver: a single instance of the
     *    FieldArgs will satisfy all queried objects, since the same schema applies
     *    to all of them.
     *
     * @return SplObjectStorage<ObjectTypeResolverInterface,SplObjectStorage<object,array<string,mixed>>>|null null if there was an error casting the fieldArgs
     */
    public function getWildcardObjectTypeResolverObjectFieldData(
        FieldInterface $field,
        EngineIterationFeedbackStore $engineIterationFeedbackStore,
    ): ?SplObjectStorage;
    /**
     * Handle case:
     *
     * 3. Data for a specific object: When executing nested mutations, the FieldArgs
     *    for each object will be different, as it will contain implicit information
     *    belonging to the object.
     *    For instance, when querying `mutation { posts { update(title: "New title") { id } } }`,
     *    the value for the `$postID` is injected into the FieldArgs for each object,
     *    and the validation of the FieldArgs must also be executed for each object.
     *
     * @param array<string|int> $objectIDs
     * @param array<string|int,object> $idObjects
     * @return SplObjectStorage<ObjectTypeResolverInterface,SplObjectStorage<object,array<string,mixed>>>|null null if there was an error casting the fieldArgs
     */
    public function getIndependentObjectTypeResolverObjectFieldData(
        FieldInterface $field,
        array $objectIDs,
        array $idObjects,
        EngineIterationFeedbackStore $engineIterationFeedbackStore,
    ): ?SplObjectStorage;
}
