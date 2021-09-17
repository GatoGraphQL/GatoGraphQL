<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FieldResolvers\ObjectType;

use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;

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
    abstract protected function getAliasedObjectTypeFieldResolverClass(): string;

    /**
     * Aliased `ObjectTypeFieldResolver` instance
     */
    protected function getAliasedObjectTypeFieldResolverInstance(): AbstractObjectTypeFieldResolver
    {
        $instanceManager = InstanceManagerFacade::getInstance();
        return $instanceManager->getInstance(
            $this->getAliasedObjectTypeFieldResolverClass()
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased ObjectTypeFieldResolver,
     * for the aliased $fieldName
     */
    public function isGlobal(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): bool
    {
        $aliasedObjectTypeFieldResolver = $this->getAliasedObjectTypeFieldResolverInstance();
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
        $aliasedObjectTypeFieldResolver = $this->getAliasedObjectTypeFieldResolverInstance();
        return $aliasedObjectTypeFieldResolver->decideCanProcessBasedOnVersionConstraint(
            $objectTypeResolver
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased ObjectTypeFieldResolver,
     * for the aliased $fieldName
     */
    public function resolveCanProcess(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, array $fieldArgs = []): bool
    {
        $aliasedObjectTypeFieldResolver = $this->getAliasedObjectTypeFieldResolverInstance();
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
    public function resolveSchemaValidationErrorDescriptions(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, array $fieldArgs = []): ?array
    {
        $aliasedObjectTypeFieldResolver = $this->getAliasedObjectTypeFieldResolverInstance();
        return $aliasedObjectTypeFieldResolver->resolveSchemaValidationErrorDescriptions(
            $objectTypeResolver,
            $this->getAliasedFieldName($fieldName),
            $fieldArgs
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased ObjectTypeFieldResolver,
     * for the aliased $fieldName
     */
    public function resolveSchemaValidationDeprecationDescriptions(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, array $fieldArgs = []): ?array
    {
        $aliasedObjectTypeFieldResolver = $this->getAliasedObjectTypeFieldResolverInstance();
        return $aliasedObjectTypeFieldResolver->resolveSchemaValidationDeprecationDescriptions(
            $objectTypeResolver,
            $this->getAliasedFieldName($fieldName),
            $fieldArgs
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased ObjectTypeFieldResolver,
     * for the aliased $fieldName
     */
    public function resolveSchemaValidationWarningDescriptions(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, array $fieldArgs = []): ?array
    {
        $aliasedObjectTypeFieldResolver = $this->getAliasedObjectTypeFieldResolverInstance();
        return $aliasedObjectTypeFieldResolver->resolveSchemaValidationWarningDescriptions(
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
        array $fieldArgs = []
    ): bool {
        $aliasedObjectTypeFieldResolver = $this->getAliasedObjectTypeFieldResolverInstance();
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
    public function getValidationErrorDescriptions(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        string $fieldName,
        array $fieldArgs = []
    ): ?array {
        $aliasedObjectTypeFieldResolver = $this->getAliasedObjectTypeFieldResolverInstance();
        return $aliasedObjectTypeFieldResolver->getValidationErrorDescriptions(
            $objectTypeResolver,
            $object,
            $this->getAliasedFieldName($fieldName),
            $fieldArgs
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased ObjectTypeFieldResolver,
     * for the aliased $fieldName
     */
    public function skipAddingToSchemaDefinition(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): bool
    {
        $aliasedObjectTypeFieldResolver = $this->getAliasedObjectTypeFieldResolverInstance();
        return $aliasedObjectTypeFieldResolver->skipAddingToSchemaDefinition(
            $objectTypeResolver,
            $this->getAliasedFieldName($fieldName)
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased ObjectTypeFieldResolver,
     * for the aliased $fieldName
     */
    public function getSchemaFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?int
    {
        $aliasedObjectTypeFieldResolver = $this->getAliasedObjectTypeFieldResolverInstance();
        return $aliasedObjectTypeFieldResolver->getSchemaFieldTypeModifiers(
            $objectTypeResolver,
            $this->getAliasedFieldName($fieldName)
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased ObjectTypeFieldResolver,
     * for the aliased $fieldName
     */
    public function getSchemaFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        $aliasedObjectTypeFieldResolver = $this->getAliasedObjectTypeFieldResolverInstance();
        return $aliasedObjectTypeFieldResolver->getSchemaFieldDescription(
            $objectTypeResolver,
            $this->getAliasedFieldName($fieldName)
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased ObjectTypeFieldResolver,
     * for the aliased $fieldName
     */
    public function getSchemaFieldArgs(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        $aliasedObjectTypeFieldResolver = $this->getAliasedObjectTypeFieldResolverInstance();
        return $aliasedObjectTypeFieldResolver->getSchemaFieldArgs(
            $objectTypeResolver,
            $this->getAliasedFieldName($fieldName)
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased ObjectTypeFieldResolver,
     * for the aliased $fieldName
     */
    public function getSchemaFieldDeprecationDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, array $fieldArgs = []): ?string
    {
        $aliasedObjectTypeFieldResolver = $this->getAliasedObjectTypeFieldResolverInstance();
        return $aliasedObjectTypeFieldResolver->getSchemaFieldDeprecationDescription(
            $objectTypeResolver,
            $this->getAliasedFieldName($fieldName),
            $fieldArgs
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased ObjectTypeFieldResolver,
     * for the aliased $fieldName
     */
    public function validateFieldArgument(
        ObjectTypeResolverInterface $objectTypeResolver,
        string $fieldName,
        string $fieldArgName,
        mixed $fieldArgValue
    ): array {
        $aliasedObjectTypeFieldResolver = $this->getAliasedObjectTypeFieldResolverInstance();
        return $aliasedObjectTypeFieldResolver->validateFieldArgument(
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
    public function addSchemaDefinitionForField(array &$schemaDefinition, ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): void
    {
        $aliasedObjectTypeFieldResolver = $this->getAliasedObjectTypeFieldResolverInstance();
        $aliasedObjectTypeFieldResolver->addSchemaDefinitionForField(
            $schemaDefinition,
            $objectTypeResolver,
            $this->getAliasedFieldName($fieldName)
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased ObjectTypeFieldResolver,
     * for the aliased $fieldName
     */
    public function enableOrderedSchemaFieldArgs(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): bool
    {
        $aliasedObjectTypeFieldResolver = $this->getAliasedObjectTypeFieldResolverInstance();
        return $aliasedObjectTypeFieldResolver->enableOrderedSchemaFieldArgs(
            $objectTypeResolver,
            $this->getAliasedFieldName($fieldName)
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased ObjectTypeFieldResolver,
     * for the aliased $fieldName
     */
    public function getSchemaFieldVersion(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        $aliasedObjectTypeFieldResolver = $this->getAliasedObjectTypeFieldResolverInstance();
        return $aliasedObjectTypeFieldResolver->getSchemaFieldVersion(
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
        array $fieldArgs = [],
        ?array $variables = null,
        ?array $expressions = null,
        array $options = []
    ): mixed {
        $aliasedObjectTypeFieldResolver = $this->getAliasedObjectTypeFieldResolverInstance();
        return $aliasedObjectTypeFieldResolver->resolveValue(
            $objectTypeResolver,
            $object,
            $this->getAliasedFieldName($fieldName),
            $fieldArgs,
            $variables,
            $expressions,
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
        $aliasedObjectTypeFieldResolver = $this->getAliasedObjectTypeFieldResolverInstance();
        return $aliasedObjectTypeFieldResolver->getFieldTypeResolver(
            $objectTypeResolver,
            $this->getAliasedFieldName($fieldName)
        );
    }
}
