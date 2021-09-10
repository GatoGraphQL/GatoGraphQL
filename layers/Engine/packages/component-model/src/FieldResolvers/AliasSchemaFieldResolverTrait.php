<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FieldResolvers;

use PoP\ComponentModel\TypeResolvers\Object\ObjectTypeResolverInterface;
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
 * The aliased FieldResolver must indicate which specific `FieldResolver` class
 * it is aliasing, because if the field is duplicated, then just using
 * the $fieldName to obtain the FieldResolver is ambiguous.
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
    protected function getAliasedFieldResolverInstance(): AbstractObjectTypeFieldResolver
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
    public function isGlobal(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): bool
    {
        $aliasedFieldResolver = $this->getAliasedFieldResolverInstance();
        return $aliasedFieldResolver->isGlobal(
            $objectTypeResolver,
            $this->getAliasedFieldName($fieldName)
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased FieldResolver,
     * for the aliased $fieldName
     */
    public function decideCanProcessBasedOnVersionConstraint(ObjectTypeResolverInterface $objectTypeResolver): bool
    {
        $aliasedFieldResolver = $this->getAliasedFieldResolverInstance();
        return $aliasedFieldResolver->decideCanProcessBasedOnVersionConstraint(
            $objectTypeResolver
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased FieldResolver,
     * for the aliased $fieldName
     */
    public function resolveCanProcess(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, array $fieldArgs = []): bool
    {
        $aliasedFieldResolver = $this->getAliasedFieldResolverInstance();
        return $aliasedFieldResolver->resolveCanProcess(
            $objectTypeResolver,
            $this->getAliasedFieldName($fieldName),
            $fieldArgs
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased FieldResolver,
     * for the aliased $fieldName
     */
    public function resolveSchemaValidationErrorDescriptions(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, array $fieldArgs = []): ?array
    {
        $aliasedFieldResolver = $this->getAliasedFieldResolverInstance();
        return $aliasedFieldResolver->resolveSchemaValidationErrorDescriptions(
            $objectTypeResolver,
            $this->getAliasedFieldName($fieldName),
            $fieldArgs
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased FieldResolver,
     * for the aliased $fieldName
     */
    public function resolveSchemaValidationDeprecationDescriptions(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, array $fieldArgs = []): ?array
    {
        $aliasedFieldResolver = $this->getAliasedFieldResolverInstance();
        return $aliasedFieldResolver->resolveSchemaValidationDeprecationDescriptions(
            $objectTypeResolver,
            $this->getAliasedFieldName($fieldName),
            $fieldArgs
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased FieldResolver,
     * for the aliased $fieldName
     */
    public function resolveSchemaValidationWarningDescriptions(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, array $fieldArgs = []): ?array
    {
        $aliasedFieldResolver = $this->getAliasedFieldResolverInstance();
        return $aliasedFieldResolver->resolveSchemaValidationWarningDescriptions(
            $objectTypeResolver,
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
        ObjectTypeResolverInterface $objectTypeResolver,
        object $resultItem,
        string $fieldName,
        array $fieldArgs = []
    ): bool {
        $aliasedFieldResolver = $this->getAliasedFieldResolverInstance();
        return $aliasedFieldResolver->resolveCanProcessResultItem(
            $objectTypeResolver,
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
        ObjectTypeResolverInterface $objectTypeResolver,
        object $resultItem,
        string $fieldName,
        array $fieldArgs = []
    ): ?array {
        $aliasedFieldResolver = $this->getAliasedFieldResolverInstance();
        return $aliasedFieldResolver->getValidationErrorDescriptions(
            $objectTypeResolver,
            $resultItem,
            $this->getAliasedFieldName($fieldName),
            $fieldArgs
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased FieldResolver,
     * for the aliased $fieldName
     */
    public function skipAddingToSchemaDefinition(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): bool
    {
        $aliasedFieldResolver = $this->getAliasedFieldResolverInstance();
        return $aliasedFieldResolver->skipAddingToSchemaDefinition(
            $objectTypeResolver,
            $this->getAliasedFieldName($fieldName)
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased FieldResolver,
     * for the aliased $fieldName
     */
    public function getSchemaFieldType(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): string
    {
        $aliasedFieldResolver = $this->getAliasedFieldResolverInstance();
        return $aliasedFieldResolver->getSchemaFieldType(
            $objectTypeResolver,
            $this->getAliasedFieldName($fieldName)
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased FieldResolver,
     * for the aliased $fieldName
     */
    public function getSchemaFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?int
    {
        $aliasedFieldResolver = $this->getAliasedFieldResolverInstance();
        return $aliasedFieldResolver->getSchemaFieldTypeModifiers(
            $objectTypeResolver,
            $this->getAliasedFieldName($fieldName)
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased FieldResolver,
     * for the aliased $fieldName
     */
    public function getSchemaFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        $aliasedFieldResolver = $this->getAliasedFieldResolverInstance();
        return $aliasedFieldResolver->getSchemaFieldDescription(
            $objectTypeResolver,
            $this->getAliasedFieldName($fieldName)
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased FieldResolver,
     * for the aliased $fieldName
     */
    public function getSchemaFieldArgs(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        $aliasedFieldResolver = $this->getAliasedFieldResolverInstance();
        return $aliasedFieldResolver->getSchemaFieldArgs(
            $objectTypeResolver,
            $this->getAliasedFieldName($fieldName)
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased FieldResolver,
     * for the aliased $fieldName
     */
    public function getSchemaFieldDeprecationDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, array $fieldArgs = []): ?string
    {
        $aliasedFieldResolver = $this->getAliasedFieldResolverInstance();
        return $aliasedFieldResolver->getSchemaFieldDeprecationDescription(
            $objectTypeResolver,
            $this->getAliasedFieldName($fieldName),
            $fieldArgs
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased FieldResolver,
     * for the aliased $fieldName
     */
    public function validateFieldArgument(
        ObjectTypeResolverInterface $objectTypeResolver,
        string $fieldName,
        string $fieldArgName,
        mixed $fieldArgValue
    ): array {
        $aliasedFieldResolver = $this->getAliasedFieldResolverInstance();
        return $aliasedFieldResolver->validateFieldArgument(
            $objectTypeResolver,
            $this->getAliasedFieldName($fieldName),
            $fieldArgName,
            $fieldArgValue
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased FieldResolver,
     * for the aliased $fieldName
     */
    public function addSchemaDefinitionForField(array &$schemaDefinition, ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): void
    {
        $aliasedFieldResolver = $this->getAliasedFieldResolverInstance();
        $aliasedFieldResolver->addSchemaDefinitionForField(
            $schemaDefinition,
            $objectTypeResolver,
            $this->getAliasedFieldName($fieldName)
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased FieldResolver,
     * for the aliased $fieldName
     */
    public function enableOrderedSchemaFieldArgs(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): bool
    {
        $aliasedFieldResolver = $this->getAliasedFieldResolverInstance();
        return $aliasedFieldResolver->enableOrderedSchemaFieldArgs(
            $objectTypeResolver,
            $this->getAliasedFieldName($fieldName)
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased FieldResolver,
     * for the aliased $fieldName
     */
    public function getSchemaFieldVersion(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        $aliasedFieldResolver = $this->getAliasedFieldResolverInstance();
        return $aliasedFieldResolver->getSchemaFieldVersion(
            $objectTypeResolver,
            $this->getAliasedFieldName($fieldName)
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased FieldResolver,
     * for the aliased $fieldName
     */
    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        $resultItem,
        string $fieldName,
        array $fieldArgs = [],
        ?array $variables = null,
        ?array $expressions = null,
        array $options = []
    ): mixed {
        $aliasedFieldResolver = $this->getAliasedFieldResolverInstance();
        return $aliasedFieldResolver->resolveValue(
            $objectTypeResolver,
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
    public function getFieldTypeResolverClass(
        ObjectTypeResolverInterface $objectTypeResolver,
        string $fieldName
    ): ?string {
        $aliasedFieldResolver = $this->getAliasedFieldResolverInstance();
        return $aliasedFieldResolver->getFieldTypeResolverClass(
            $objectTypeResolver,
            $this->getAliasedFieldName($fieldName)
        );
    }
}
