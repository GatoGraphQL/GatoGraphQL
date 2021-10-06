<?php

declare(strict_types=1);

namespace PoP\ComponentModel\DirectiveResolvers;

use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;

/**
 * Create an alias of a directive, to use when:
 *
 * - the same directive is registered more than once (eg: by different plugins)
 * - want to rename the directive (steps: alias the directive, then remove access to the original)
 *
 * This trait, to be applied on a DirectiveResolver class, uses the Proxy design pattern:
 * every function executed on the aliasing directive executes the same function on the aliased directive.
 *
 * The aliased DirectiveResolver is chosen to be of class `AbstractDirectiveResolver`,
 * since it comprises interfaces `DirectiveResolverInterface`
 * and `SchemaDirectiveResolverInterface`, whose functions must be aliased.
 *
 * @author Leonardo Losoviz <leo@getpop.org>
 */
trait AliasSchemaDirectiveResolverTrait
{
    /**
     * The specific `DirectiveResolver` class that is being aliased
     */
    abstract protected function getAliasedDirectiveResolver(): AbstractDirectiveResolver;

    /**
     * Proxy pattern: execute same function on the aliased DirectiveResolver
     */
    public function getDirectiveDescription(RelationalTypeResolverInterface $relationalTypeResolver): ?string
    {
        $aliasedDirectiveResolver = $this->getAliasedDirectiveResolver();
        return $aliasedDirectiveResolver->getDirectiveDescription(
            $relationalTypeResolver
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased DirectiveResolver
     */
    public function enableOrderedSchemaDirectiveArgs(RelationalTypeResolverInterface $relationalTypeResolver): bool
    {
        $aliasedDirectiveResolver = $this->getAliasedDirectiveResolver();
        return $aliasedDirectiveResolver->enableOrderedSchemaDirectiveArgs(
            $relationalTypeResolver
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased DirectiveResolver
     */
    public function getDirectiveArgNameResolvers(RelationalTypeResolverInterface $relationalTypeResolver): array
    {
        $aliasedDirectiveResolver = $this->getAliasedDirectiveResolver();
        return $aliasedDirectiveResolver->getDirectiveArgNameResolvers(
            $relationalTypeResolver
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased DirectiveResolver
     */
    public function getDirectiveArgDescription(RelationalTypeResolverInterface $relationalTypeResolver, string $directiveArgName): ?string
    {
        $aliasedDirectiveResolver = $this->getAliasedDirectiveResolver();
        return $aliasedDirectiveResolver->getDirectiveArgDescription(
            $relationalTypeResolver,
            $directiveArgName
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased DirectiveResolver
     */
    public function getDirectiveArgDeprecationDescription(RelationalTypeResolverInterface $relationalTypeResolver, string $directiveArgName): ?string
    {
        $aliasedDirectiveResolver = $this->getAliasedDirectiveResolver();
        return $aliasedDirectiveResolver->getDirectiveArgDeprecationDescription(
            $relationalTypeResolver,
            $directiveArgName
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased DirectiveResolver
     */
    public function getDirectiveArgDefaultValue(RelationalTypeResolverInterface $relationalTypeResolver, string $directiveArgName): mixed
    {
        $aliasedDirectiveResolver = $this->getAliasedDirectiveResolver();
        return $aliasedDirectiveResolver->getDirectiveArgDefaultValue(
            $relationalTypeResolver,
            $directiveArgName
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased DirectiveResolver
     */
    public function getDirectiveArgTypeModifiers(RelationalTypeResolverInterface $relationalTypeResolver, string $directiveArgName): int
    {
        $aliasedDirectiveResolver = $this->getAliasedDirectiveResolver();
        return $aliasedDirectiveResolver->getDirectiveArgTypeModifiers(
            $relationalTypeResolver,
            $directiveArgName
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased DirectiveResolver
     */
    public function getDirectiveExpressions(RelationalTypeResolverInterface $relationalTypeResolver): array
    {
        $aliasedDirectiveResolver = $this->getAliasedDirectiveResolver();
        return $aliasedDirectiveResolver->getDirectiveExpressions(
            $relationalTypeResolver
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased DirectiveResolver
     */
    public function getDirectiveWarningDescription(RelationalTypeResolverInterface $relationalTypeResolver): ?string
    {
        $aliasedDirectiveResolver = $this->getAliasedDirectiveResolver();
        return $aliasedDirectiveResolver->getDirectiveWarningDescription(
            $relationalTypeResolver
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased DirectiveResolver
     */
    public function getDirectiveDeprecationDescription(RelationalTypeResolverInterface $relationalTypeResolver): ?string
    {
        $aliasedDirectiveResolver = $this->getAliasedDirectiveResolver();
        return $aliasedDirectiveResolver->getDirectiveDeprecationDescription(
            $relationalTypeResolver
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased DirectiveResolver
     */
    public function isGlobal(RelationalTypeResolverInterface $relationalTypeResolver): bool
    {
        $aliasedDirectiveResolver = $this->getAliasedDirectiveResolver();
        return $aliasedDirectiveResolver->isGlobal(
            $relationalTypeResolver
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased DirectiveResolver
     */
    public function getFieldNamesToApplyTo(): array
    {
        $aliasedDirectiveResolver = $this->getAliasedDirectiveResolver();
        return $aliasedDirectiveResolver->getFieldNamesToApplyTo();
    }

    /**
     * Proxy pattern: execute same function on the aliased DirectiveResolver
     */
    public function getDirectiveType(): string
    {
        $aliasedDirectiveResolver = $this->getAliasedDirectiveResolver();
        return $aliasedDirectiveResolver->getDirectiveType();
    }

    /**
     * Proxy pattern: execute same function on the aliased DirectiveResolver
     */
    public function dissectAndValidateDirectiveForSchema(
        RelationalTypeResolverInterface $relationalTypeResolver,
        array &$fieldDirectiveFields,
        array &$variables,
        array &$schemaErrors,
        array &$schemaWarnings,
        array &$schemaDeprecations,
        array &$schemaNotices,
        array &$schemaTraces
    ): array {
        $aliasedDirectiveResolver = $this->getAliasedDirectiveResolver();
        return $aliasedDirectiveResolver->dissectAndValidateDirectiveForSchema(
            $relationalTypeResolver,
            $fieldDirectiveFields,
            $variables,
            $schemaErrors,
            $schemaWarnings,
            $schemaDeprecations,
            $schemaNotices,
            $schemaTraces
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased DirectiveResolver
     */
    public function validateDirectiveArgumentsForSchema(
        RelationalTypeResolverInterface $relationalTypeResolver,
        string $directiveName,
        array $directiveArgs,
        array &$schemaErrors,
        array &$schemaWarnings,
        array &$schemaDeprecations
    ): array {
        $aliasedDirectiveResolver = $this->getAliasedDirectiveResolver();
        return $aliasedDirectiveResolver->validateDirectiveArgumentsForSchema(
            $relationalTypeResolver,
            $directiveName,
            $directiveArgs,
            $schemaErrors,
            $schemaWarnings,
            $schemaDeprecations
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased DirectiveResolver
     */
    public function getPipelinePosition(): string
    {
        $aliasedDirectiveResolver = $this->getAliasedDirectiveResolver();
        return $aliasedDirectiveResolver->getPipelinePosition();
    }

    /**
     * Proxy pattern: execute same function on the aliased DirectiveResolver
     */
    public function resolveCanProcess(
        RelationalTypeResolverInterface $relationalTypeResolver,
        string $directiveName,
        array $directiveArgs,
        string $field,
        array &$variables
    ): bool {
        $aliasedDirectiveResolver = $this->getAliasedDirectiveResolver();
        return $aliasedDirectiveResolver->resolveCanProcess(
            $relationalTypeResolver,
            $directiveName,
            $directiveArgs,
            $field,
            $variables
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased DirectiveResolver
     */
    public function isRepeatable(): bool
    {
        $aliasedDirectiveResolver = $this->getAliasedDirectiveResolver();
        return $aliasedDirectiveResolver->isRepeatable();
    }

    /**
     * Proxy pattern: execute same function on the aliased DirectiveResolver
     */
    public function needsIDsDataFieldsToExecute(): bool
    {
        $aliasedDirectiveResolver = $this->getAliasedDirectiveResolver();
        return $aliasedDirectiveResolver->needsIDsDataFieldsToExecute();
    }

    /**
     * Proxy pattern: execute same function on the aliased DirectiveResolver
     */
    public function resolveDirective(
        RelationalTypeResolverInterface $relationalTypeResolver,
        array &$idsDataFields,
        array &$succeedingPipelineIDsDataFields,
        array &$succeedingPipelineDirectiveResolverInstances,
        array &$objectIDItems,
        array &$unionDBKeyIDs,
        array &$dbItems,
        array &$previousDBItems,
        array &$variables,
        array &$messages,
        array &$objectErrors,
        array &$objectWarnings,
        array &$objectDeprecations,
        array &$objectNotices,
        array &$objectTraces,
        array &$schemaErrors,
        array &$schemaWarnings,
        array &$schemaDeprecations,
        array &$schemaNotices,
        array &$schemaTraces
    ): void {
        $aliasedDirectiveResolver = $this->getAliasedDirectiveResolver();
        $aliasedDirectiveResolver->resolveDirective(
            $relationalTypeResolver,
            $idsDataFields,
            $succeedingPipelineIDsDataFields,
            $succeedingPipelineDirectiveResolverInstances,
            $objectIDItems,
            $unionDBKeyIDs,
            $dbItems,
            $previousDBItems,
            $variables,
            $messages,
            $objectErrors,
            $objectWarnings,
            $objectDeprecations,
            $objectNotices,
            $objectTraces,
            $schemaErrors,
            $schemaWarnings,
            $schemaDeprecations,
            $schemaNotices,
            $schemaTraces
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased DirectiveResolver
     */
    public function skipAddingToSchemaDefinition(): bool
    {
        $aliasedDirectiveResolver = $this->getAliasedDirectiveResolver();
        return $aliasedDirectiveResolver->skipAddingToSchemaDefinition();
    }

    /**
     * Proxy pattern: execute same function on the aliased DirectiveResolver
     */
    public function decideCanProcessBasedOnVersionConstraint(RelationalTypeResolverInterface $relationalTypeResolver): bool
    {
        $aliasedDirectiveResolver = $this->getAliasedDirectiveResolver();
        return $aliasedDirectiveResolver->decideCanProcessBasedOnVersionConstraint(
            $relationalTypeResolver
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased DirectiveResolver
     */
    public function getDirectiveVersion(RelationalTypeResolverInterface $relationalTypeResolver): ?string
    {
        $aliasedDirectiveResolver = $this->getAliasedDirectiveResolver();
        return $aliasedDirectiveResolver->getDirectiveVersion(
            $relationalTypeResolver
        );
    }
}
