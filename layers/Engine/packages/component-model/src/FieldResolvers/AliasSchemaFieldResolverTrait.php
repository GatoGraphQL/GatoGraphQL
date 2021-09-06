<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FieldResolvers;

use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
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
    public function isGlobal(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): bool
    {
        $aliasedFieldResolver = $this->getAliasedFieldResolverInstance();
        return $aliasedFieldResolver->isGlobal(
            $relationalTypeResolver,
            $this->getAliasedFieldName($fieldName)
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased FieldResolver,
     * for the aliased $fieldName
     */
    public function decideCanProcessBasedOnVersionConstraint(RelationalTypeResolverInterface $relationalTypeResolver): bool
    {
        $aliasedFieldResolver = $this->getAliasedFieldResolverInstance();
        return $aliasedFieldResolver->decideCanProcessBasedOnVersionConstraint(
            $relationalTypeResolver
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased FieldResolver,
     * for the aliased $fieldName
     */
    public function resolveCanProcess(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName, array $fieldArgs = []): bool
    {
        $aliasedFieldResolver = $this->getAliasedFieldResolverInstance();
        return $aliasedFieldResolver->resolveCanProcess(
            $relationalTypeResolver,
            $this->getAliasedFieldName($fieldName),
            $fieldArgs
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased FieldResolver,
     * for the aliased $fieldName
     */
    public function resolveSchemaValidationErrorDescriptions(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName, array $fieldArgs = []): ?array
    {
        $aliasedFieldResolver = $this->getAliasedFieldResolverInstance();
        return $aliasedFieldResolver->resolveSchemaValidationErrorDescriptions(
            $relationalTypeResolver,
            $this->getAliasedFieldName($fieldName),
            $fieldArgs
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased FieldResolver,
     * for the aliased $fieldName
     */
    public function resolveSchemaValidationDeprecationDescriptions(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName, array $fieldArgs = []): ?array
    {
        $aliasedFieldResolver = $this->getAliasedFieldResolverInstance();
        return $aliasedFieldResolver->resolveSchemaValidationDeprecationDescriptions(
            $relationalTypeResolver,
            $this->getAliasedFieldName($fieldName),
            $fieldArgs
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased FieldResolver,
     * for the aliased $fieldName
     */
    public function resolveSchemaValidationWarningDescriptions(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName, array $fieldArgs = []): ?array
    {
        $aliasedFieldResolver = $this->getAliasedFieldResolverInstance();
        return $aliasedFieldResolver->resolveSchemaValidationWarningDescriptions(
            $relationalTypeResolver,
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
        RelationalTypeResolverInterface $relationalTypeResolver,
        object $resultItem,
        string $fieldName,
        array $fieldArgs = []
    ): bool {
        $aliasedFieldResolver = $this->getAliasedFieldResolverInstance();
        return $aliasedFieldResolver->resolveCanProcessResultItem(
            $relationalTypeResolver,
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
        RelationalTypeResolverInterface $relationalTypeResolver,
        object $resultItem,
        string $fieldName,
        array $fieldArgs = []
    ): ?array {
        $aliasedFieldResolver = $this->getAliasedFieldResolverInstance();
        return $aliasedFieldResolver->getValidationErrorDescriptions(
            $relationalTypeResolver,
            $resultItem,
            $this->getAliasedFieldName($fieldName),
            $fieldArgs
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased FieldResolver,
     * for the aliased $fieldName
     */
    public function skipAddingToSchemaDefinition(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): bool
    {
        $aliasedFieldResolver = $this->getAliasedFieldResolverInstance();
        return $aliasedFieldResolver->skipAddingToSchemaDefinition(
            $relationalTypeResolver,
            $this->getAliasedFieldName($fieldName)
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased FieldResolver,
     * for the aliased $fieldName
     */
    public function getSchemaFieldType(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): string
    {
        $aliasedFieldResolver = $this->getAliasedFieldResolverInstance();
        return $aliasedFieldResolver->getSchemaFieldType(
            $relationalTypeResolver,
            $this->getAliasedFieldName($fieldName)
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased FieldResolver,
     * for the aliased $fieldName
     */
    public function getSchemaFieldTypeModifiers(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): ?int
    {
        $aliasedFieldResolver = $this->getAliasedFieldResolverInstance();
        return $aliasedFieldResolver->getSchemaFieldTypeModifiers(
            $relationalTypeResolver,
            $this->getAliasedFieldName($fieldName)
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased FieldResolver,
     * for the aliased $fieldName
     */
    public function getSchemaFieldDescription(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): ?string
    {
        $aliasedFieldResolver = $this->getAliasedFieldResolverInstance();
        return $aliasedFieldResolver->getSchemaFieldDescription(
            $relationalTypeResolver,
            $this->getAliasedFieldName($fieldName)
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased FieldResolver,
     * for the aliased $fieldName
     */
    public function getSchemaFieldArgs(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): array
    {
        $aliasedFieldResolver = $this->getAliasedFieldResolverInstance();
        return $aliasedFieldResolver->getSchemaFieldArgs(
            $relationalTypeResolver,
            $this->getAliasedFieldName($fieldName)
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased FieldResolver,
     * for the aliased $fieldName
     */
    public function getSchemaFieldDeprecationDescription(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName, array $fieldArgs = []): ?string
    {
        $aliasedFieldResolver = $this->getAliasedFieldResolverInstance();
        return $aliasedFieldResolver->getSchemaFieldDeprecationDescription(
            $relationalTypeResolver,
            $this->getAliasedFieldName($fieldName),
            $fieldArgs
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased FieldResolver,
     * for the aliased $fieldName
     */
    public function validateFieldArgument(
        RelationalTypeResolverInterface $relationalTypeResolver,
        string $fieldName,
        string $fieldArgName,
        mixed $fieldArgValue
    ): array {
        $aliasedFieldResolver = $this->getAliasedFieldResolverInstance();
        return $aliasedFieldResolver->validateFieldArgument(
            $relationalTypeResolver,
            $this->getAliasedFieldName($fieldName),
            $fieldArgName,
            $fieldArgValue
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased FieldResolver,
     * for the aliased $fieldName
     */
    public function addSchemaDefinitionForField(array &$schemaDefinition, RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): void
    {
        $aliasedFieldResolver = $this->getAliasedFieldResolverInstance();
        $aliasedFieldResolver->addSchemaDefinitionForField(
            $schemaDefinition,
            $relationalTypeResolver,
            $this->getAliasedFieldName($fieldName)
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased FieldResolver,
     * for the aliased $fieldName
     */
    public function enableOrderedSchemaFieldArgs(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): bool
    {
        $aliasedFieldResolver = $this->getAliasedFieldResolverInstance();
        return $aliasedFieldResolver->enableOrderedSchemaFieldArgs(
            $relationalTypeResolver,
            $this->getAliasedFieldName($fieldName)
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased FieldResolver,
     * for the aliased $fieldName
     */
    public function getSchemaFieldVersion(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): ?string
    {
        $aliasedFieldResolver = $this->getAliasedFieldResolverInstance();
        return $aliasedFieldResolver->getSchemaFieldVersion(
            $relationalTypeResolver,
            $this->getAliasedFieldName($fieldName)
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased FieldResolver,
     * for the aliased $fieldName
     */
    public function resolveValue(
        RelationalTypeResolverInterface $relationalTypeResolver,
        $resultItem,
        string $fieldName,
        array $fieldArgs = [],
        ?array $variables = null,
        ?array $expressions = null,
        array $options = []
    ): mixed {
        $aliasedFieldResolver = $this->getAliasedFieldResolverInstance();
        return $aliasedFieldResolver->resolveValue(
            $relationalTypeResolver,
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
     */
    public function resolveFieldTypeResolverClass(
        RelationalTypeResolverInterface $relationalTypeResolver,
        string $fieldName
    ): ?string {
        $aliasedFieldResolver = $this->getAliasedFieldResolverInstance();
        return $aliasedFieldResolver->resolveFieldTypeResolverClass(
            $relationalTypeResolver,
            $this->getAliasedFieldName($fieldName)
        );
    }
}
