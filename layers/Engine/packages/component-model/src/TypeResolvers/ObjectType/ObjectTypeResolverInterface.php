<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolvers\ObjectType;

use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\FieldResolvers\ObjectType\ObjectTypeFieldResolverInterface;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\OutputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;

interface ObjectTypeResolverInterface extends RelationalTypeResolverInterface, OutputTypeResolverInterface
{
    public function getFieldSchemaDefinition(FieldInterface $field): ?array;
    public function hasObjectTypeFieldResolversForField(FieldInterface $field): bool;
    public function collectFieldValidationErrors(
        FieldInterface $field,
        array $variables,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore
    ): void;
    public function collectFieldValidationWarnings(
        FieldInterface $field,
        array $variables,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void;
    public function collectFieldDeprecations(
        FieldInterface $field,
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
    public function isFieldAMutation(string $fieldName): ?bool;
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
}
