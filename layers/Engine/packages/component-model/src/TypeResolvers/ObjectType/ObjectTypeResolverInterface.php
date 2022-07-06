<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolvers\ObjectType;

use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\FieldResolvers\ObjectType\ObjectTypeFieldResolverInterface;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\OutputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\Directive;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;

interface ObjectTypeResolverInterface extends RelationalTypeResolverInterface, OutputTypeResolverInterface
{
    public function getFieldSchemaDefinition(FieldInterface $field): ?array;
    public function hasObjectTypeFieldResolversForField(FieldInterface $field): bool;
    public function collectFieldValidationErrors(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore
    ): void;
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
     * Add the default Argument values to the field, coerce them,
     * and allow to apply customizations
     *
     * @param array<string,mixed> $fieldData
     */
    public function prepareFieldData(
        array &$fieldData,
        FieldInterface $field,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void;
    /**
     * Get the first FieldResolver that resolves the field
     */
    public function getExecutableObjectTypeFieldResolverForField(FieldInterface|string $fieldOrFieldName): ?ObjectTypeFieldResolverInterface;
    /**
     * Extract the FieldArgs into its corresponding FieldDataAccessor, which integrates
     * within the default values and coerces them according to the schema.
     *
     * @return array<string,mixed>
     */
    public function getFieldData(
        FieldInterface $field,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): array;
    /**
     * @param array<string,mixed> $fieldData
     */
    public function createFieldDataAccessor(
        FieldInterface $field,
        array $fieldData,
    ): FieldDataAccessorInterface;
}
