<?php

declare(strict_types=1);

namespace PoP\ComponentModel\DirectiveResolvers;

use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;

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
    abstract protected function getAliasedDirectiveResolverClass(): string;

    /**
     * Aliased `DirectiveResolver` instance
     */
    protected function getAliasedDirectiveResolverInstance(): AbstractDirectiveResolver
    {
        $instanceManager = InstanceManagerFacade::getInstance();
        return $instanceManager->getInstance(
            $this->getAliasedDirectiveResolverClass()
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased DirectiveResolver
     */
    public function getSchemaDirectiveDescription(TypeResolverInterface $typeResolver): ?string
    {
        $aliasedDirectiveResolver = $this->getAliasedDirectiveResolverInstance();
        return $aliasedDirectiveResolver->getSchemaDirectiveDescription(
            $typeResolver
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased DirectiveResolver
     */
    public function enableOrderedSchemaDirectiveArgs(TypeResolverInterface $typeResolver): bool
    {
        $aliasedDirectiveResolver = $this->getAliasedDirectiveResolverInstance();
        return $aliasedDirectiveResolver->enableOrderedSchemaDirectiveArgs(
            $typeResolver
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased DirectiveResolver
     */
    public function getSchemaDirectiveArgs(TypeResolverInterface $typeResolver): array
    {
        $aliasedDirectiveResolver = $this->getAliasedDirectiveResolverInstance();
        return $aliasedDirectiveResolver->getSchemaDirectiveArgs(
            $typeResolver
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased DirectiveResolver
     */
    public function getSchemaDirectiveExpressions(TypeResolverInterface $typeResolver): array
    {
        $aliasedDirectiveResolver = $this->getAliasedDirectiveResolverInstance();
        return $aliasedDirectiveResolver->getSchemaDirectiveExpressions(
            $typeResolver
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased DirectiveResolver
     */
    public function getSchemaDirectiveWarningDescription(TypeResolverInterface $typeResolver): ?string
    {
        $aliasedDirectiveResolver = $this->getAliasedDirectiveResolverInstance();
        return $aliasedDirectiveResolver->getSchemaDirectiveWarningDescription(
            $typeResolver
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased DirectiveResolver
     */
    public function getSchemaDirectiveDeprecationDescription(TypeResolverInterface $typeResolver): ?string
    {
        $aliasedDirectiveResolver = $this->getAliasedDirectiveResolverInstance();
        return $aliasedDirectiveResolver->getSchemaDirectiveDeprecationDescription(
            $typeResolver
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased DirectiveResolver
     */
    public function isGlobal(TypeResolverInterface $typeResolver): bool
    {
        $aliasedDirectiveResolver = $this->getAliasedDirectiveResolverInstance();
        return $aliasedDirectiveResolver->isGlobal(
            $typeResolver
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased DirectiveResolver
     */
    public function getFieldNamesToApplyTo(): array
    {
        $aliasedDirectiveResolver = $this->getAliasedDirectiveResolverInstance();
        return $aliasedDirectiveResolver->getFieldNamesToApplyTo();
    }

    /**
     * Proxy pattern: execute same function on the aliased DirectiveResolver
     */
    public function getDirectiveType(): string
    {
        $aliasedDirectiveResolver = $this->getAliasedDirectiveResolverInstance();
        return $aliasedDirectiveResolver->getDirectiveType();
    }

    /**
     * Proxy pattern: execute same function on the aliased DirectiveResolver
     */
    public function dissectAndValidateDirectiveForSchema(
        TypeResolverInterface $typeResolver,
        array &$fieldDirectiveFields,
        array &$variables,
        array &$schemaErrors,
        array &$schemaWarnings,
        array &$schemaDeprecations,
        array &$schemaNotices,
        array &$schemaTraces
    ): array {
        $aliasedDirectiveResolver = $this->getAliasedDirectiveResolverInstance();
        return $aliasedDirectiveResolver->dissectAndValidateDirectiveForSchema(
            $typeResolver,
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
        TypeResolverInterface $typeResolver,
        string $directiveName,
        array $directiveArgs,
        array &$schemaErrors,
        array &$schemaWarnings,
        array &$schemaDeprecations
    ): array {
        $aliasedDirectiveResolver = $this->getAliasedDirectiveResolverInstance();
        return $aliasedDirectiveResolver->validateDirectiveArgumentsForSchema(
            $typeResolver,
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
        $aliasedDirectiveResolver = $this->getAliasedDirectiveResolverInstance();
        return $aliasedDirectiveResolver->getPipelinePosition();
    }

    /**
     * Proxy pattern: execute same function on the aliased DirectiveResolver
     */
    public function resolveCanProcess(
        TypeResolverInterface $typeResolver,
        string $directiveName,
        array $directiveArgs,
        string $field,
        array &$variables
    ): bool {
        $aliasedDirectiveResolver = $this->getAliasedDirectiveResolverInstance();
        return $aliasedDirectiveResolver->resolveCanProcess(
            $typeResolver,
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
        $aliasedDirectiveResolver = $this->getAliasedDirectiveResolverInstance();
        return $aliasedDirectiveResolver->isRepeatable();
    }

    /**
     * Proxy pattern: execute same function on the aliased DirectiveResolver
     */
    public function needsIDsDataFieldsToExecute(): bool
    {
        $aliasedDirectiveResolver = $this->getAliasedDirectiveResolverInstance();
        return $aliasedDirectiveResolver->needsIDsDataFieldsToExecute();
    }

    /**
     * Proxy pattern: execute same function on the aliased DirectiveResolver
     */
    public function resolveDirective(
        TypeResolverInterface $typeResolver,
        array &$idsDataFields,
        array &$succeedingPipelineIDsDataFields,
        array &$succeedingPipelineDirectiveResolverInstances,
        array &$resultIDItems,
        array &$unionDBKeyIDs,
        array &$dbItems,
        array &$previousDBItems,
        array &$variables,
        array &$messages,
        array &$dbErrors,
        array &$dbWarnings,
        array &$dbDeprecations,
        array &$dbNotices,
        array &$dbTraces,
        array &$schemaErrors,
        array &$schemaWarnings,
        array &$schemaDeprecations,
        array &$schemaNotices,
        array &$schemaTraces
    ): void {
        $aliasedDirectiveResolver = $this->getAliasedDirectiveResolverInstance();
        $aliasedDirectiveResolver->resolveDirective(
            $typeResolver,
            $idsDataFields,
            $succeedingPipelineIDsDataFields,
            $succeedingPipelineDirectiveResolverInstances,
            $resultIDItems,
            $unionDBKeyIDs,
            $dbItems,
            $previousDBItems,
            $variables,
            $messages,
            $dbErrors,
            $dbWarnings,
            $dbDeprecations,
            $dbNotices,
            $dbTraces,
            $schemaErrors,
            $schemaWarnings,
            $schemaDeprecations,
            $schemaNotices,
            $schemaTraces
        );
    }

    // /**
    //  * Proxy pattern: execute same function on the aliased DirectiveResolver
    //  */
    // public function getSchemaDefinitionResolver(TypeResolverInterface $typeResolver): ?SchemaDirectiveResolverInterface
    // {
    //     $aliasedDirectiveResolver = $this->getAliasedDirectiveResolverInstance();
    //     return $aliasedDirectiveResolver->getSchemaDefinitionResolver(
    //         $typeResolver
    //     );
    // }

    /**
     * Proxy pattern: execute same function on the aliased DirectiveResolver
     */
    public function skipAddingToSchemaDefinition(): bool
    {
        $aliasedDirectiveResolver = $this->getAliasedDirectiveResolverInstance();
        return $aliasedDirectiveResolver->skipAddingToSchemaDefinition();
    }

    // /**
    //  * Proxy pattern: execute same function on the aliased DirectiveResolver
    //  */
    // public function getSchemaDefinitionForDirective(TypeResolverInterface $typeResolver): array;

    /**
     * Proxy pattern: execute same function on the aliased DirectiveResolver
     */
    public function decideCanProcessBasedOnVersionConstraint(TypeResolverInterface $typeResolver): bool
    {
        $aliasedDirectiveResolver = $this->getAliasedDirectiveResolverInstance();
        return $aliasedDirectiveResolver->decideCanProcessBasedOnVersionConstraint(
            $typeResolver
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased DirectiveResolver
     */
    public function getSchemaDirectiveVersion(TypeResolverInterface $typeResolver): ?string
    {
        $aliasedDirectiveResolver = $this->getAliasedDirectiveResolverInstance();
        return $aliasedDirectiveResolver->getSchemaDirectiveVersion(
            $typeResolver
        );
    }
}
