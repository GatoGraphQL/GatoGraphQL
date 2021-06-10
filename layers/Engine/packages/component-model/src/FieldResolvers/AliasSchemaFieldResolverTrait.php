<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FieldResolvers;

use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;

/**
 * Create an alias of a fieldName (or fieldNames), to use when:
 *
 * - the same fieldName is registered more than once (eg: by different plugins)
 * - want to rename the field (steps: alias the field, then remove access to the original)
 *
 * This trait, to be applied on a FieldResolver class, uses the Proxy design pattern:
 * every function executed on the aliasing field executes the same function on the aliased field.
 *
 * The aliased FieldResolver is chosen to be of class `AbstractSchemaFieldResolver`,
 * since this is the highest level comprising the base `AbstractFieldResolver`
 * and the interface `FieldSchemaDefinitionResolverInterface`.
 *
 * It must indicate which specific `FieldResolver` class it is aliasing,
 * because if the field is duplicated, then just using the $fieldName
 * to obtain the FieldResolver is ambiguous.
 *
 * @author Leonardo Losoviz <leo@getpop.org>
 */
trait AliasSchemaFieldResolverTrait
{
    /**
     * The fieldName that is being aliased
     */
    abstract protected function getAliasedFieldName(string $fieldName): string;

    /**
     * The specific `FieldResolver` class that is being aliased
     */
    abstract protected function getAliasedFieldResolverClass(): string;

    /**
     * Aliased `FieldResolver` instance
     */
    protected function getAliasedFieldResolverInstance(): AbstractSchemaFieldResolver
    {
        $instanceManager = InstanceManagerFacade::getInstance();
        return $instanceManager->getInstance(
            $this->getAliasedFieldResolverClass()
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased FieldResolver,
     * for the aliased $fieldName
     */
    public function isGlobal(TypeResolverInterface $typeResolver, string $fieldName): bool
    {
        $aliasedFieldResolver = $this->getAliasedFieldResolverInstance();
        return $aliasedFieldResolver->isGlobal(
            $typeResolver,
            $this->getAliasedFieldName($fieldName)
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased FieldResolver,
     * for the aliased $fieldName
     */
    public function decideCanProcessBasedOnVersionConstraint(TypeResolverInterface $typeResolver): bool
    {
        $aliasedFieldResolver = $this->getAliasedFieldResolverInstance();
        return $aliasedFieldResolver->decideCanProcessBasedOnVersionConstraint(
            $typeResolver
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased FieldResolver,
     * for the aliased $fieldName
     */
    public function resolveCanProcess(TypeResolverInterface $typeResolver, string $fieldName, array $fieldArgs = []): bool
    {
        $aliasedFieldResolver = $this->getAliasedFieldResolverInstance();
        return $aliasedFieldResolver->resolveCanProcess(
            $typeResolver,
            $this->getAliasedFieldName($fieldName),
            $fieldArgs
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased FieldResolver,
     * for the aliased $fieldName
     */
    public function resolveSchemaValidationErrorDescriptions(TypeResolverInterface $typeResolver, string $fieldName, array $fieldArgs = []): ?array
    {
        $aliasedFieldResolver = $this->getAliasedFieldResolverInstance();
        return $aliasedFieldResolver->resolveSchemaValidationErrorDescriptions(
            $typeResolver,
            $this->getAliasedFieldName($fieldName),
            $fieldArgs
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased FieldResolver,
     * for the aliased $fieldName
     */
    public function resolveSchemaValidationDeprecationDescriptions(TypeResolverInterface $typeResolver, string $fieldName, array $fieldArgs = []): ?array
    {
        $aliasedFieldResolver = $this->getAliasedFieldResolverInstance();
        return $aliasedFieldResolver->resolveSchemaValidationDeprecationDescriptions(
            $typeResolver,
            $this->getAliasedFieldName($fieldName),
            $fieldArgs
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased FieldResolver,
     * for the aliased $fieldName
     */
    public function resolveSchemaValidationWarningDescriptions(TypeResolverInterface $typeResolver, string $fieldName, array $fieldArgs = []): ?array
    {
        $aliasedFieldResolver = $this->getAliasedFieldResolverInstance();
        return $aliasedFieldResolver->resolveSchemaValidationWarningDescriptions(
            $typeResolver,
            $this->getAliasedFieldName($fieldName),
            $fieldArgs
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased FieldResolver,
     * for the aliased $fieldName
     */
    /**
     * @param array<string, mixed> $fieldArgs
     */
    public function resolveCanProcessResultItem(
        TypeResolverInterface $typeResolver,
        object $resultItem,
        string $fieldName,
        array $fieldArgs = []
    ): bool {
        $aliasedFieldResolver = $this->getAliasedFieldResolverInstance();
        return $aliasedFieldResolver->resolveCanProcessResultItem(
            $typeResolver,
            $resultItem,
            $this->getAliasedFieldName($fieldName),
            $fieldArgs
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased FieldResolver,
     * for the aliased $fieldName
     *
     * @param array<string, mixed> $fieldArgs
     */
    public function getValidationErrorDescriptions(
        TypeResolverInterface $typeResolver,
        object $resultItem,
        string $fieldName,
        array $fieldArgs = []
    ): ?array {
        $aliasedFieldResolver = $this->getAliasedFieldResolverInstance();
        return $aliasedFieldResolver->getValidationErrorDescriptions(
            $typeResolver,
            $resultItem,
            $this->getAliasedFieldName($fieldName),
            $fieldArgs
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased FieldResolver,
     * for the aliased $fieldName
     */
    public function skipAddingToSchemaDefinition(TypeResolverInterface $typeResolver, string $fieldName): bool
    {
        $aliasedFieldResolver = $this->getAliasedFieldResolverInstance();
        return $aliasedFieldResolver->skipAddingToSchemaDefinition(
            $typeResolver,
            $this->getAliasedFieldName($fieldName)
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased FieldResolver,
     * for the aliased $fieldName
     */
    public function getSchemaFieldType(TypeResolverInterface $typeResolver, string $fieldName): string
    {
        $aliasedFieldResolver = $this->getAliasedFieldResolverInstance();
        return $aliasedFieldResolver->getSchemaFieldType(
            $typeResolver,
            $this->getAliasedFieldName($fieldName)
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased FieldResolver,
     * for the aliased $fieldName
     */
    public function getSchemaFieldTypeModifiers(TypeResolverInterface $typeResolver, string $fieldName): ?int
    {
        $aliasedFieldResolver = $this->getAliasedFieldResolverInstance();
        return $aliasedFieldResolver->getSchemaFieldTypeModifiers(
            $typeResolver,
            $this->getAliasedFieldName($fieldName)
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased FieldResolver,
     * for the aliased $fieldName
     */
    public function getSchemaFieldDescription(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $aliasedFieldResolver = $this->getAliasedFieldResolverInstance();
        return $aliasedFieldResolver->getSchemaFieldDescription(
            $typeResolver,
            $this->getAliasedFieldName($fieldName)
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased FieldResolver,
     * for the aliased $fieldName
     */
    public function getSchemaFieldArgs(TypeResolverInterface $typeResolver, string $fieldName): array
    {
        $aliasedFieldResolver = $this->getAliasedFieldResolverInstance();
        return $aliasedFieldResolver->getSchemaFieldArgs(
            $typeResolver,
            $this->getAliasedFieldName($fieldName)
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased FieldResolver,
     * for the aliased $fieldName
     */
    public function getFilteredSchemaFieldArgs(TypeResolverInterface $typeResolver, string $fieldName): array
    {
        $aliasedFieldResolver = $this->getAliasedFieldResolverInstance();
        return $aliasedFieldResolver->getFilteredSchemaFieldArgs(
            $typeResolver,
            $this->getAliasedFieldName($fieldName)
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased FieldResolver,
     * for the aliased $fieldName
     */
    public function getSchemaFieldDeprecationDescription(TypeResolverInterface $typeResolver, string $fieldName, array $fieldArgs = []): ?string
    {
        $aliasedFieldResolver = $this->getAliasedFieldResolverInstance();
        return $aliasedFieldResolver->getSchemaFieldDeprecationDescription(
            $typeResolver,
            $this->getAliasedFieldName($fieldName),
            $fieldArgs
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased FieldResolver,
     * for the aliased $fieldName
     */
    public function addSchemaDefinitionForField(array &$schemaDefinition, TypeResolverInterface $typeResolver, string $fieldName): void
    {
        $aliasedFieldResolver = $this->getAliasedFieldResolverInstance();
        $aliasedFieldResolver->addSchemaDefinitionForField(
            $schemaDefinition,
            $typeResolver,
            $this->getAliasedFieldName($fieldName)
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased FieldResolver,
     * for the aliased $fieldName
     */
    public function enableOrderedSchemaFieldArgs(TypeResolverInterface $typeResolver, string $fieldName): bool
    {
        $aliasedFieldResolver = $this->getAliasedFieldResolverInstance();
        return $aliasedFieldResolver->enableOrderedSchemaFieldArgs(
            $typeResolver,
            $this->getAliasedFieldName($fieldName)
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased FieldResolver,
     * for the aliased $fieldName
     */
    public function getSchemaFieldVersion(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $aliasedFieldResolver = $this->getAliasedFieldResolverInstance();
        return $aliasedFieldResolver->getSchemaFieldVersion(
            $typeResolver,
            $this->getAliasedFieldName($fieldName)
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased FieldResolver,
     * for the aliased $fieldName
     */
    public function resolveValue(
        TypeResolverInterface $typeResolver,
        $resultItem,
        string $fieldName,
        array $fieldArgs = [],
        ?array $variables = null,
        ?array $expressions = null,
        array $options = []
    ): mixed {
        $aliasedFieldResolver = $this->getAliasedFieldResolverInstance();
        return $aliasedFieldResolver->resolveValue(
            $typeResolver,
            $resultItem,
            $this->getAliasedFieldName($fieldName),
            $fieldArgs,
            $variables,
            $expressions,
            $options
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased FieldResolver,
     * for the aliased $fieldName
     *
     * @param array<string, mixed> $fieldArgs
     */
    public function resolveFieldTypeResolverClass(
        TypeResolverInterface $typeResolver,
        string $fieldName
    ): ?string {
        $aliasedFieldResolver = $this->getAliasedFieldResolverInstance();
        return $aliasedFieldResolver->resolveFieldTypeResolverClass(
            $typeResolver,
            $this->getAliasedFieldName($fieldName)
        );
    }
}
