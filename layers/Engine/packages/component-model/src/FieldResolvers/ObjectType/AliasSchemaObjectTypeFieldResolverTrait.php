<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FieldResolvers\ObjectType;

use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

/**
 * Create an alias of a fieldName (or fieldNames), to use when:
 *
 * - the same fieldName is registered more than once (eg: by different plugins)
 * - want to rename the field (steps: alias the field, then remove access to the original)
 *
 * This trait, to be applied on a ObjectTypeFieldResolver class, uses the Proxy design pattern:
 * every function executed on the aliasing field executes the same function on the aliased field.
 *
 * The aliased ObjectTypeFieldResolver must indicate which specific `ObjectTypeFieldResolver` class
 * it is aliasing, because if the field is duplicated, then just using
 * the $fieldName to obtain the ObjectTypeFieldResolver is ambiguous.
 *
 * @author Leonardo Losoviz <leo@getpop.org>
 */
trait AliasSchemaObjectTypeFieldResolverTrait
{
    /**
     * The fieldName that is being aliased
     */
    abstract protected function getAliasedFieldName(string $fieldName): string;

    /**
     * The specific `ObjectTypeFieldResolver` class that is being aliased
     */
    abstract protected function getAliasedObjectTypeFieldResolver(): AbstractObjectTypeFieldResolver;

    /**
     * Proxy pattern: execute same function on the aliased ObjectTypeFieldResolver,
     * for the aliased $fieldName
     */
    public function isGlobal(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): bool
    {
        $aliasedObjectTypeFieldResolver = $this->getAliasedObjectTypeFieldResolver();
        return $aliasedObjectTypeFieldResolver->isGlobal(
            $objectTypeResolver,
            $this->getAliasedFieldName($fieldName)
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased ObjectTypeFieldResolver,
     * for the aliased $fieldName
     */
    public function decideCanProcessBasedOnVersionConstraint(ObjectTypeResolverInterface $objectTypeResolver): bool
    {
        $aliasedObjectTypeFieldResolver = $this->getAliasedObjectTypeFieldResolver();
        return $aliasedObjectTypeFieldResolver->decideCanProcessBasedOnVersionConstraint(
            $objectTypeResolver
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased ObjectTypeFieldResolver,
     * for the aliased $fieldName
     */
    public function resolveCanProcess(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, array $fieldArgs): bool
    {
        $aliasedObjectTypeFieldResolver = $this->getAliasedObjectTypeFieldResolver();
        return $aliasedObjectTypeFieldResolver->resolveCanProcess(
            $objectTypeResolver,
            $this->getAliasedFieldName($fieldName),
            $fieldArgs
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased ObjectTypeFieldResolver,
     * for the aliased $fieldName
     */
    public function collectFieldValidationErrorDescriptions(
        ObjectTypeResolverInterface $objectTypeResolver,
        string $fieldName,
        array $fieldArgs,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        $aliasedObjectTypeFieldResolver = $this->getAliasedObjectTypeFieldResolver();
        $aliasedObjectTypeFieldResolver->collectFieldValidationErrorDescriptions(
            $objectTypeResolver,
            $this->getAliasedFieldName($fieldName),
            $fieldArgs,
            $objectTypeFieldResolutionFeedbackStore,
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased ObjectTypeFieldResolver,
     * for the aliased $fieldName
     */
    public function collectFieldValidationDeprecationMessages(
        ObjectTypeResolverInterface $objectTypeResolver,
        string $fieldName,
        array $fieldArgs,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        $aliasedObjectTypeFieldResolver = $this->getAliasedObjectTypeFieldResolver();
        $aliasedObjectTypeFieldResolver->collectFieldValidationDeprecationMessages(
            $objectTypeResolver,
            $this->getAliasedFieldName($fieldName),
            $fieldArgs,
            $objectTypeFieldResolutionFeedbackStore,
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased ObjectTypeFieldResolver,
     * for the aliased $fieldName
     */
    public function resolveFieldValidationWarnings(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, array $fieldArgs): array
    {
        $aliasedObjectTypeFieldResolver = $this->getAliasedObjectTypeFieldResolver();
        return $aliasedObjectTypeFieldResolver->resolveFieldValidationWarnings(
            $objectTypeResolver,
            $this->getAliasedFieldName($fieldName),
            $fieldArgs
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased ObjectTypeFieldResolver,
     * for the aliased $fieldName
     */
    /**
     * @param array<string, mixed> $fieldArgs
     */
    public function resolveCanProcessObject(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        string $fieldName,
        array $fieldArgs
    ): bool {
        $aliasedObjectTypeFieldResolver = $this->getAliasedObjectTypeFieldResolver();
        return $aliasedObjectTypeFieldResolver->resolveCanProcessObject(
            $objectTypeResolver,
            $object,
            $this->getAliasedFieldName($fieldName),
            $fieldArgs
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased ObjectTypeFieldResolver,
     * for the aliased $fieldName
     *
     * @param array<string, mixed> $fieldArgs
     */
    public function collectValidationErrorDescriptions(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        string $fieldName,
        array $fieldArgs,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        $aliasedObjectTypeFieldResolver = $this->getAliasedObjectTypeFieldResolver();
        $aliasedObjectTypeFieldResolver->collectValidationErrorDescriptions(
            $objectTypeResolver,
            $object,
            $this->getAliasedFieldName($fieldName),
            $fieldArgs,
            $objectTypeFieldResolutionFeedbackStore,
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased ObjectTypeFieldResolver,
     * for the aliased $fieldName
     */
    public function skipExposingFieldInSchema(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): bool
    {
        $aliasedObjectTypeFieldResolver = $this->getAliasedObjectTypeFieldResolver();
        return $aliasedObjectTypeFieldResolver->skipExposingFieldInSchema(
            $objectTypeResolver,
            $this->getAliasedFieldName($fieldName)
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased ObjectTypeFieldResolver,
     * for the aliased $fieldName
     */
    public function getFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): int
    {
        $aliasedObjectTypeFieldResolver = $this->getAliasedObjectTypeFieldResolver();
        return $aliasedObjectTypeFieldResolver->getFieldTypeModifiers(
            $objectTypeResolver,
            $this->getAliasedFieldName($fieldName)
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased ObjectTypeFieldResolver,
     * for the aliased $fieldName
     */
    public function getFieldArgNameTypeResolvers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        $aliasedObjectTypeFieldResolver = $this->getAliasedObjectTypeFieldResolver();
        return $aliasedObjectTypeFieldResolver->getFieldArgNameTypeResolvers(
            $objectTypeResolver,
            $this->getAliasedFieldName($fieldName)
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased ObjectTypeFieldResolver,
     * for the aliased $fieldName
     */
    public function getAdminFieldArgNames(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        $aliasedObjectTypeFieldResolver = $this->getAliasedObjectTypeFieldResolver();
        return $aliasedObjectTypeFieldResolver->getAdminFieldArgNames(
            $objectTypeResolver,
            $this->getAliasedFieldName($fieldName)
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased ObjectTypeFieldResolver,
     * for the aliased $fieldName
     */
    public function getFieldArgDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): ?string
    {
        $aliasedObjectTypeFieldResolver = $this->getAliasedObjectTypeFieldResolver();
        return $aliasedObjectTypeFieldResolver->getFieldArgDescription(
            $objectTypeResolver,
            $this->getAliasedFieldName($fieldName),
            $fieldArgName
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased ObjectTypeFieldResolver,
     * for the aliased $fieldName
     */
    public function getFieldArgDefaultValue(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): mixed
    {
        $aliasedObjectTypeFieldResolver = $this->getAliasedObjectTypeFieldResolver();
        return $aliasedObjectTypeFieldResolver->getFieldArgDefaultValue(
            $objectTypeResolver,
            $this->getAliasedFieldName($fieldName),
            $fieldArgName
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased ObjectTypeFieldResolver,
     * for the aliased $fieldName
     */
    public function getFieldArgTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): int
    {
        $aliasedObjectTypeFieldResolver = $this->getAliasedObjectTypeFieldResolver();
        return $aliasedObjectTypeFieldResolver->getFieldArgTypeModifiers(
            $objectTypeResolver,
            $this->getAliasedFieldName($fieldName),
            $fieldArgName
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased ObjectTypeFieldResolver,
     * for the aliased $fieldName
     */
    public function getConsolidatedFieldArgNameTypeResolvers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        $aliasedObjectTypeFieldResolver = $this->getAliasedObjectTypeFieldResolver();
        return $aliasedObjectTypeFieldResolver->getConsolidatedFieldArgNameTypeResolvers(
            $objectTypeResolver,
            $this->getAliasedFieldName($fieldName)
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased ObjectTypeFieldResolver,
     * for the aliased $fieldName
     */
    public function getConsolidatedAdminFieldArgNames(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        $aliasedObjectTypeFieldResolver = $this->getAliasedObjectTypeFieldResolver();
        return $aliasedObjectTypeFieldResolver->getConsolidatedAdminFieldArgNames(
            $objectTypeResolver,
            $this->getAliasedFieldName($fieldName)
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased ObjectTypeFieldResolver,
     * for the aliased $fieldName
     */
    public function getConsolidatedFieldArgDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): ?string
    {
        $aliasedObjectTypeFieldResolver = $this->getAliasedObjectTypeFieldResolver();
        return $aliasedObjectTypeFieldResolver->getConsolidatedFieldArgDescription(
            $objectTypeResolver,
            $this->getAliasedFieldName($fieldName),
            $fieldArgName
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased ObjectTypeFieldResolver,
     * for the aliased $fieldName
     */
    public function getConsolidatedFieldArgDefaultValue(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): mixed
    {
        $aliasedObjectTypeFieldResolver = $this->getAliasedObjectTypeFieldResolver();
        return $aliasedObjectTypeFieldResolver->getConsolidatedFieldArgDefaultValue(
            $objectTypeResolver,
            $this->getAliasedFieldName($fieldName),
            $fieldArgName
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased ObjectTypeFieldResolver,
     * for the aliased $fieldName
     */
    public function getConsolidatedFieldArgTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): int
    {
        $aliasedObjectTypeFieldResolver = $this->getAliasedObjectTypeFieldResolver();
        return $aliasedObjectTypeFieldResolver->getConsolidatedFieldArgTypeModifiers(
            $objectTypeResolver,
            $this->getAliasedFieldName($fieldName),
            $fieldArgName
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased ObjectTypeFieldResolver,
     * for the aliased $fieldName
     */
    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        $aliasedObjectTypeFieldResolver = $this->getAliasedObjectTypeFieldResolver();
        return $aliasedObjectTypeFieldResolver->getFieldDescription(
            $objectTypeResolver,
            $this->getAliasedFieldName($fieldName)
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased ObjectTypeFieldResolver,
     * for the aliased $fieldName
     */
    public function getFieldDeprecationMessage(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        $aliasedObjectTypeFieldResolver = $this->getAliasedObjectTypeFieldResolver();
        return $aliasedObjectTypeFieldResolver->getFieldDeprecationMessage(
            $objectTypeResolver,
            $this->getAliasedFieldName($fieldName)
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased ObjectTypeFieldResolver,
     * for the aliased $fieldName
     */
    public function getConsolidatedFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        $aliasedObjectTypeFieldResolver = $this->getAliasedObjectTypeFieldResolver();
        return $aliasedObjectTypeFieldResolver->getConsolidatedFieldDescription(
            $objectTypeResolver,
            $this->getAliasedFieldName($fieldName)
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased ObjectTypeFieldResolver,
     * for the aliased $fieldName
     */
    public function getConsolidatedFieldDeprecationMessage(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        $aliasedObjectTypeFieldResolver = $this->getAliasedObjectTypeFieldResolver();
        return $aliasedObjectTypeFieldResolver->getConsolidatedFieldDeprecationMessage(
            $objectTypeResolver,
            $this->getAliasedFieldName($fieldName)
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased ObjectTypeFieldResolver,
     * for the aliased $fieldName
     */
    public function validateFieldArgValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        string $fieldName,
        string $fieldArgName,
        mixed $fieldArgValue
    ): array {
        $aliasedObjectTypeFieldResolver = $this->getAliasedObjectTypeFieldResolver();
        return $aliasedObjectTypeFieldResolver->validateFieldArgValue(
            $objectTypeResolver,
            $this->getAliasedFieldName($fieldName),
            $fieldArgName,
            $fieldArgValue
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased ObjectTypeFieldResolver,
     * for the aliased $fieldName
     */
    public function enableOrderedSchemaFieldArgs(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): bool
    {
        $aliasedObjectTypeFieldResolver = $this->getAliasedObjectTypeFieldResolver();
        return $aliasedObjectTypeFieldResolver->enableOrderedSchemaFieldArgs(
            $objectTypeResolver,
            $this->getAliasedFieldName($fieldName)
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased ObjectTypeFieldResolver,
     * for the aliased $fieldName
     */
    public function getFieldVersion(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        $aliasedObjectTypeFieldResolver = $this->getAliasedObjectTypeFieldResolver();
        return $aliasedObjectTypeFieldResolver->getFieldVersion(
            $objectTypeResolver,
            $this->getAliasedFieldName($fieldName)
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased ObjectTypeFieldResolver,
     * for the aliased $fieldName
     */
    public function getFieldVersionInputTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?InputTypeResolverInterface
    {
        $aliasedObjectTypeFieldResolver = $this->getAliasedObjectTypeFieldResolver();
        return $aliasedObjectTypeFieldResolver->getFieldVersionInputTypeResolver(
            $objectTypeResolver,
            $this->getAliasedFieldName($fieldName)
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased ObjectTypeFieldResolver,
     * for the aliased $fieldName
     */
    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        $object,
        string $fieldName,
        array $fieldArgs,
        array $variables,
        array $expressions,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
        array $options = []
    ): mixed {
        $aliasedObjectTypeFieldResolver = $this->getAliasedObjectTypeFieldResolver();
        return $aliasedObjectTypeFieldResolver->resolveValue(
            $objectTypeResolver,
            $object,
            $this->getAliasedFieldName($fieldName),
            $fieldArgs,
            $variables,
            $expressions,
            $objectTypeFieldResolutionFeedbackStore,
            $options
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased ObjectTypeFieldResolver,
     * for the aliased $fieldName
     */
    public function getFieldTypeResolver(
        ObjectTypeResolverInterface $objectTypeResolver,
        string $fieldName
    ): ConcreteTypeResolverInterface {
        $aliasedObjectTypeFieldResolver = $this->getAliasedObjectTypeFieldResolver();
        return $aliasedObjectTypeFieldResolver->getFieldTypeResolver(
            $objectTypeResolver,
            $this->getAliasedFieldName($fieldName)
        );
    }
}
