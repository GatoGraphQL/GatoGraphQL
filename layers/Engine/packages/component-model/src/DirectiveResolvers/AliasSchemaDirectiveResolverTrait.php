<?php

declare(strict_types=1);

namespace PoP\ComponentModel\DirectiveResolvers;

use PoP\ComponentModel\Engine\EngineIterationFieldSet;
use PoP\ComponentModel\Feedback\EngineIterationFeedbackStore;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\Directive;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\Root\Feedback\FeedbackItemResolution;
use SplObjectStorage;

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
    public function getDirectiveArgNameTypeResolvers(RelationalTypeResolverInterface $relationalTypeResolver): array
    {
        $aliasedDirectiveResolver = $this->getAliasedDirectiveResolver();
        return $aliasedDirectiveResolver->getDirectiveArgNameTypeResolvers(
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
    public function getConsolidatedDirectiveArgNameTypeResolvers(RelationalTypeResolverInterface $relationalTypeResolver): array
    {
        $aliasedDirectiveResolver = $this->getAliasedDirectiveResolver();
        return $aliasedDirectiveResolver->getConsolidatedDirectiveArgNameTypeResolvers(
            $relationalTypeResolver
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased DirectiveResolver
     */
    public function getConsolidatedDirectiveArgDescription(RelationalTypeResolverInterface $relationalTypeResolver, string $directiveArgName): ?string
    {
        $aliasedDirectiveResolver = $this->getAliasedDirectiveResolver();
        return $aliasedDirectiveResolver->getConsolidatedDirectiveArgDescription(
            $relationalTypeResolver,
            $directiveArgName
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased DirectiveResolver
     */
    public function getConsolidatedDirectiveArgDefaultValue(RelationalTypeResolverInterface $relationalTypeResolver, string $directiveArgName): mixed
    {
        $aliasedDirectiveResolver = $this->getAliasedDirectiveResolver();
        return $aliasedDirectiveResolver->getConsolidatedDirectiveArgDefaultValue(
            $relationalTypeResolver,
            $directiveArgName
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased DirectiveResolver
     */
    public function getConsolidatedDirectiveArgTypeModifiers(RelationalTypeResolverInterface $relationalTypeResolver, string $directiveArgName): int
    {
        $aliasedDirectiveResolver = $this->getAliasedDirectiveResolver();
        return $aliasedDirectiveResolver->getConsolidatedDirectiveArgTypeModifiers(
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
    public function getDirectiveWarning(RelationalTypeResolverInterface $relationalTypeResolver): ?FeedbackItemResolution
    {
        $aliasedDirectiveResolver = $this->getAliasedDirectiveResolver();
        return $aliasedDirectiveResolver->getDirectiveWarning(
            $relationalTypeResolver
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased DirectiveResolver
     */
    public function getDirectiveDeprecationMessage(RelationalTypeResolverInterface $relationalTypeResolver): ?string
    {
        $aliasedDirectiveResolver = $this->getAliasedDirectiveResolver();
        return $aliasedDirectiveResolver->getDirectiveDeprecationMessage(
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
    public function getDirectiveKind(): string
    {
        $aliasedDirectiveResolver = $this->getAliasedDirectiveResolver();
        return $aliasedDirectiveResolver->getDirectiveKind();
    }

    /**
     * Proxy pattern: execute same function on the aliased DirectiveResolver
     *
     * @param SplObjectStorage<Directive,FieldInterface[]> $directiveFields
     */
    public function dissectAndValidateDirectiveForSchema(
        RelationalTypeResolverInterface $relationalTypeResolver,
        SplObjectStorage $directiveFields,
        array &$variables,
        EngineIterationFeedbackStore $engineIterationFeedbackStore,
    ): array {
        $aliasedDirectiveResolver = $this->getAliasedDirectiveResolver();
        return $aliasedDirectiveResolver->dissectAndValidateDirectiveForSchema(
            $relationalTypeResolver,
            $directiveFields,
            $variables,
            $engineIterationFeedbackStore,
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased DirectiveResolver
     */
    public function validateDirectiveArgumentsForSchema(
        RelationalTypeResolverInterface $relationalTypeResolver,
        string $directiveName,
        array $directiveArgs,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): array {
        $aliasedDirectiveResolver = $this->getAliasedDirectiveResolver();
        return $aliasedDirectiveResolver->validateDirectiveArgumentsForSchema(
            $relationalTypeResolver,
            $directiveName,
            $directiveArgs,
            $objectTypeFieldResolutionFeedbackStore,
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
    public function isDirectiveEnabled(): bool
    {
        $aliasedDirectiveResolver = $this->getAliasedDirectiveResolver();
        return $aliasedDirectiveResolver->isDirectiveEnabled();
    }

    /**
     * Proxy pattern: execute same function on the aliased DirectiveResolver
     */
    public function resolveCanProcess(
        RelationalTypeResolverInterface $relationalTypeResolver,
        Directive $directive,
        FieldInterface $field,
        array &$variables
    ): bool {
        $aliasedDirectiveResolver = $this->getAliasedDirectiveResolver();
        return $aliasedDirectiveResolver->resolveCanProcess(
            $relationalTypeResolver,
            $directive,
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
    public function needsSomeIDFieldToExecute(): bool
    {
        $aliasedDirectiveResolver = $this->getAliasedDirectiveResolver();
        return $aliasedDirectiveResolver->needsSomeIDFieldToExecute();
    }

    /**
     * Proxy pattern: execute same function on the aliased DirectiveResolver
     *
     * @param array<string|int,EngineIterationFieldSet> $idFieldSet
     * @param array<array<string|int,EngineIterationFieldSet>> $succeedingPipelineIDFieldSet
     * @param array<string,array<string|int,SplObjectStorage<FieldInterface,mixed>>> $previouslyResolvedIDFieldValues
     * @param array<string|int,SplObjectStorage<FieldInterface,mixed>> $resolvedIDFieldValues
     */
    public function resolveDirective(
        RelationalTypeResolverInterface $relationalTypeResolver,
        array $idFieldSet,
        array $succeedingPipelineDirectiveResolvers,
        array $idObjects,
        array $unionTypeOutputKeyIDs,
        array $previouslyResolvedIDFieldValues,
        array &$succeedingPipelineIDFieldSet,
        array &$resolvedIDFieldValues,
        array &$variables,
        array &$messages,
        EngineIterationFeedbackStore $engineIterationFeedbackStore,
    ): void {
        $aliasedDirectiveResolver = $this->getAliasedDirectiveResolver();
        $aliasedDirectiveResolver->resolveDirective(
            $relationalTypeResolver,
            $idFieldSet,
            $succeedingPipelineDirectiveResolvers,
            $idObjects,
            $unionTypeOutputKeyIDs,
            $previouslyResolvedIDFieldValues,
            $succeedingPipelineIDFieldSet,
            $resolvedIDFieldValues,
            $variables,
            $messages,
            $engineIterationFeedbackStore,
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased DirectiveResolver
     */
    public function skipExposingDirectiveInSchema(RelationalTypeResolverInterface $relationalTypeResolver): bool
    {
        $aliasedDirectiveResolver = $this->getAliasedDirectiveResolver();
        return $aliasedDirectiveResolver->skipExposingDirectiveInSchema($relationalTypeResolver);
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

    /**
     * Proxy pattern: execute same function on the aliased DirectiveResolver
     */
    public function getDirectiveVersionInputTypeResolver(RelationalTypeResolverInterface $relationalTypeResolver): ?InputTypeResolverInterface
    {
        $aliasedDirectiveResolver = $this->getAliasedDirectiveResolver();
        return $aliasedDirectiveResolver->getDirectiveVersionInputTypeResolver(
            $relationalTypeResolver
        );
    }
}
