<?php

declare(strict_types=1);

namespace PoP\ComponentModel\DirectiveResolvers;

use PoP\ComponentModel\QueryResolution\FieldDataAccessProviderInterface;
use PoP\ComponentModel\Engine\EngineIterationFieldSet;
use PoP\ComponentModel\Feedback\EngineIterationFeedbackStore;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\Directive;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
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
 * The aliased DirectiveResolver is chosen to be of class `AbstractFieldDirectiveResolver`,
 * since it comprises interfaces `FieldDirectiveResolverInterface`
 * and `SchemaFieldDirectiveResolverInterface`, whose functions must be aliased.
 *
 * @author Leonardo Losoviz <leo@getpop.org>
 */
trait AliasSchemaFieldDirectiveResolverTrait
{
    /**
     * The specific `DirectiveResolver` class that is being aliased
     */
    abstract protected function getAliasedFieldDirectiveResolver(): AbstractFieldDirectiveResolver;

    /**
     * Proxy pattern: execute same function on the aliased DirectiveResolver
     */
    public function getDirectiveDescription(RelationalTypeResolverInterface $relationalTypeResolver): ?string
    {
        $aliasedFieldDirectiveResolver = $this->getAliasedFieldDirectiveResolver();
        return $aliasedFieldDirectiveResolver->getDirectiveDescription(
            $relationalTypeResolver
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased DirectiveResolver
     *
     * @return array<string,InputTypeResolverInterface>
     */
    public function getDirectiveArgNameTypeResolvers(RelationalTypeResolverInterface $relationalTypeResolver): array
    {
        $aliasedFieldDirectiveResolver = $this->getAliasedFieldDirectiveResolver();
        return $aliasedFieldDirectiveResolver->getDirectiveArgNameTypeResolvers(
            $relationalTypeResolver
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased DirectiveResolver
     */
    public function getDirectiveArgDescription(RelationalTypeResolverInterface $relationalTypeResolver, string $directiveArgName): ?string
    {
        $aliasedFieldDirectiveResolver = $this->getAliasedFieldDirectiveResolver();
        return $aliasedFieldDirectiveResolver->getDirectiveArgDescription(
            $relationalTypeResolver,
            $directiveArgName
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased DirectiveResolver
     */
    public function getDirectiveArgDefaultValue(RelationalTypeResolverInterface $relationalTypeResolver, string $directiveArgName): mixed
    {
        $aliasedFieldDirectiveResolver = $this->getAliasedFieldDirectiveResolver();
        return $aliasedFieldDirectiveResolver->getDirectiveArgDefaultValue(
            $relationalTypeResolver,
            $directiveArgName
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased DirectiveResolver
     */
    public function getDirectiveArgTypeModifiers(RelationalTypeResolverInterface $relationalTypeResolver, string $directiveArgName): int
    {
        $aliasedFieldDirectiveResolver = $this->getAliasedFieldDirectiveResolver();
        return $aliasedFieldDirectiveResolver->getDirectiveArgTypeModifiers(
            $relationalTypeResolver,
            $directiveArgName
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased DirectiveResolver
     * @return mixed[]
     */
    public function getConsolidatedDirectiveArgNameTypeResolvers(RelationalTypeResolverInterface $relationalTypeResolver): array
    {
        $aliasedFieldDirectiveResolver = $this->getAliasedFieldDirectiveResolver();
        return $aliasedFieldDirectiveResolver->getConsolidatedDirectiveArgNameTypeResolvers(
            $relationalTypeResolver
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased DirectiveResolver
     */
    public function getConsolidatedDirectiveArgDescription(RelationalTypeResolverInterface $relationalTypeResolver, string $directiveArgName): ?string
    {
        $aliasedFieldDirectiveResolver = $this->getAliasedFieldDirectiveResolver();
        return $aliasedFieldDirectiveResolver->getConsolidatedDirectiveArgDescription(
            $relationalTypeResolver,
            $directiveArgName
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased DirectiveResolver
     */
    public function getConsolidatedDirectiveArgDefaultValue(RelationalTypeResolverInterface $relationalTypeResolver, string $directiveArgName): mixed
    {
        $aliasedFieldDirectiveResolver = $this->getAliasedFieldDirectiveResolver();
        return $aliasedFieldDirectiveResolver->getConsolidatedDirectiveArgDefaultValue(
            $relationalTypeResolver,
            $directiveArgName
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased DirectiveResolver
     */
    public function getConsolidatedDirectiveArgTypeModifiers(RelationalTypeResolverInterface $relationalTypeResolver, string $directiveArgName): int
    {
        $aliasedFieldDirectiveResolver = $this->getAliasedFieldDirectiveResolver();
        return $aliasedFieldDirectiveResolver->getConsolidatedDirectiveArgTypeModifiers(
            $relationalTypeResolver,
            $directiveArgName
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased DirectiveResolver
     */
    public function getDirectiveDeprecationMessage(RelationalTypeResolverInterface $relationalTypeResolver): ?string
    {
        $aliasedFieldDirectiveResolver = $this->getAliasedFieldDirectiveResolver();
        return $aliasedFieldDirectiveResolver->getDirectiveDeprecationMessage(
            $relationalTypeResolver
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased DirectiveResolver
     */
    public function isGlobal(RelationalTypeResolverInterface $relationalTypeResolver): bool
    {
        $aliasedFieldDirectiveResolver = $this->getAliasedFieldDirectiveResolver();
        return $aliasedFieldDirectiveResolver->isGlobal(
            $relationalTypeResolver
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased DirectiveResolver
     * @return mixed[]
     */
    public function getFieldNamesToApplyTo(): array
    {
        $aliasedFieldDirectiveResolver = $this->getAliasedFieldDirectiveResolver();
        return $aliasedFieldDirectiveResolver->getFieldNamesToApplyTo();
    }

    /**
     * Proxy pattern: execute same function on the aliased DirectiveResolver
     */
    public function getDirectiveKind(): string
    {
        $aliasedFieldDirectiveResolver = $this->getAliasedFieldDirectiveResolver();
        return $aliasedFieldDirectiveResolver->getDirectiveKind();
    }

    /**
     * Proxy pattern: execute same function on the aliased DirectiveResolver
     */
    public function getPipelinePosition(): string
    {
        $aliasedFieldDirectiveResolver = $this->getAliasedFieldDirectiveResolver();
        return $aliasedFieldDirectiveResolver->getPipelinePosition();
    }

    /**
     * Proxy pattern: execute same function on the aliased DirectiveResolver
     */
    public function isDirectiveEnabled(): bool
    {
        $aliasedFieldDirectiveResolver = $this->getAliasedFieldDirectiveResolver();
        return $aliasedFieldDirectiveResolver->isDirectiveEnabled();
    }

    /**
     * Proxy pattern: execute same function on the aliased DirectiveResolver
     */
    public function resolveCanProcessDirective(
        RelationalTypeResolverInterface $relationalTypeResolver,
        Directive $directive,
    ): bool {
        $aliasedFieldDirectiveResolver = $this->getAliasedFieldDirectiveResolver();
        return $aliasedFieldDirectiveResolver->resolveCanProcessDirective(
            $relationalTypeResolver,
            $directive,
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased DirectiveResolver
     */
    public function isRepeatable(): bool
    {
        $aliasedFieldDirectiveResolver = $this->getAliasedFieldDirectiveResolver();
        return $aliasedFieldDirectiveResolver->isRepeatable();
    }

    /**
     * Proxy pattern: execute same function on the aliased DirectiveResolver
     */
    public function needsSomeIDFieldToExecute(): bool
    {
        $aliasedFieldDirectiveResolver = $this->getAliasedFieldDirectiveResolver();
        return $aliasedFieldDirectiveResolver->needsSomeIDFieldToExecute();
    }

    /**
     * Proxy pattern: execute same function on the aliased DirectiveResolver
     *
     * @param array<string|int,EngineIterationFieldSet> $idFieldSet
     * @param array<array<string|int,EngineIterationFieldSet>> $succeedingPipelineIDFieldSet
     * @param array<FieldDataAccessProviderInterface> $succeedingPipelineFieldDataAccessProviders
     * @param array<string,array<string|int,SplObjectStorage<FieldInterface,mixed>>> $previouslyResolvedIDFieldValues
     * @param array<string|int,SplObjectStorage<FieldInterface,mixed>> $resolvedIDFieldValues
     * @param array<FieldDirectiveResolverInterface> $succeedingPipelineFieldDirectiveResolvers
     * @param array<string|int,object> $idObjects
     * @param array<string,array<string|int,SplObjectStorage<FieldInterface,array<string|int>>>> $unionTypeOutputKeyIDs
     * @param array<string,mixed> $messages
     */
    public function resolveDirective(
        RelationalTypeResolverInterface $relationalTypeResolver,
        array $idFieldSet,
        FieldDataAccessProviderInterface $fieldDataAccessProvider,
        array $succeedingPipelineFieldDirectiveResolvers,
        array $idObjects,
        array $unionTypeOutputKeyIDs,
        array $previouslyResolvedIDFieldValues,
        array &$succeedingPipelineIDFieldSet,
        array &$succeedingPipelineFieldDataAccessProviders,
        array &$resolvedIDFieldValues,
        array &$messages,
        EngineIterationFeedbackStore $engineIterationFeedbackStore,
    ): void {
        $aliasedFieldDirectiveResolver = $this->getAliasedFieldDirectiveResolver();
        $aliasedFieldDirectiveResolver->resolveDirective(
            $relationalTypeResolver,
            $idFieldSet,
            $fieldDataAccessProvider,
            $succeedingPipelineFieldDirectiveResolvers,
            $idObjects,
            $unionTypeOutputKeyIDs,
            $previouslyResolvedIDFieldValues,
            $succeedingPipelineIDFieldSet,
            $succeedingPipelineFieldDataAccessProviders,
            $resolvedIDFieldValues,
            $messages,
            $engineIterationFeedbackStore,
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased DirectiveResolver
     */
    public function skipExposingDirectiveInSchema(RelationalTypeResolverInterface $relationalTypeResolver): bool
    {
        $aliasedFieldDirectiveResolver = $this->getAliasedFieldDirectiveResolver();
        return $aliasedFieldDirectiveResolver->skipExposingDirectiveInSchema($relationalTypeResolver);
    }

    /**
     * Proxy pattern: execute same function on the aliased DirectiveResolver
     */
    public function decideCanProcessBasedOnVersionConstraint(RelationalTypeResolverInterface $relationalTypeResolver): bool
    {
        $aliasedFieldDirectiveResolver = $this->getAliasedFieldDirectiveResolver();
        return $aliasedFieldDirectiveResolver->decideCanProcessBasedOnVersionConstraint(
            $relationalTypeResolver
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased DirectiveResolver
     */
    public function getDirectiveVersion(RelationalTypeResolverInterface $relationalTypeResolver): ?string
    {
        $aliasedFieldDirectiveResolver = $this->getAliasedFieldDirectiveResolver();
        return $aliasedFieldDirectiveResolver->getDirectiveVersion(
            $relationalTypeResolver
        );
    }

    /**
     * Proxy pattern: execute same function on the aliased DirectiveResolver
     */
    public function getDirectiveVersionInputTypeResolver(RelationalTypeResolverInterface $relationalTypeResolver): ?InputTypeResolverInterface
    {
        $aliasedFieldDirectiveResolver = $this->getAliasedFieldDirectiveResolver();
        return $aliasedFieldDirectiveResolver->getDirectiveVersionInputTypeResolver(
            $relationalTypeResolver
        );
    }
}
