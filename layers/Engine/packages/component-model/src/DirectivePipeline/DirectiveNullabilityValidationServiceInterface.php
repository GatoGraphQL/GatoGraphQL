<?php

declare(strict_types=1);

namespace PoP\ComponentModel\DirectivePipeline;

use PoP\ComponentModel\DirectiveResolvers\FieldDirectiveResolverInterface;
use PoP\ComponentModel\Engine\EngineIterationFieldSet;
use PoP\ComponentModel\Feedback\EngineIterationFeedbackStore;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\Directive;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use SplObjectStorage;

/**
 * Validate that a directive does not set a `null` value on
 * a Non-Null response position (eg: `String!` or `[String!]`)
 * without raising an error, as required by the GraphQL spec.
 *
 * @see https://spec.graphql.org/draft/#sec-Handling-Execution-Errors
 */
interface DirectiveNullabilityValidationServiceInterface
{
    /**
     * Indicate if the validation must be executed for the
     * current directive pipeline (and its stages).
     */
    public function mustValidateDirectivePipeline(): bool;

    /**
     * Start tracking the nullability violations for the
     * directive pipeline started by the engine.
     */
    public function startDirectivePipeline(
        EngineIterationFeedbackStore $engineIterationFeedbackStore,
    ): void;

    /**
     * @param array<string|int,EngineIterationFieldSet> $idFieldSet
     * @param array<string|int,object> $idObjects
     * @param array<string|int,SplObjectStorage<FieldInterface,mixed>> $resolvedIDFieldValues
     */
    public function takeSnapshot(
        RelationalTypeResolverInterface $relationalTypeResolver,
        array $idFieldSet,
        array $idObjects,
        array $resolvedIDFieldValues,
        EngineIterationFeedbackStore $engineIterationFeedbackStore,
    ): DirectiveNullabilityValidationSnapshot;

    /**
     * Keep track of every Non-Null response position that the
     * directive has set to `null`, unless the directive
     * already added an error for it.
     *
     * @param array<string|int,EngineIterationFieldSet> $idFieldSet
     * @param array<string|int,object> $idObjects
     * @param array<string|int,SplObjectStorage<FieldInterface,mixed>> $resolvedIDFieldValues
     */
    public function trackNullabilityViolations(
        DirectiveNullabilityValidationSnapshot $snapshot,
        FieldDirectiveResolverInterface $fieldDirectiveResolver,
        Directive $directive,
        RelationalTypeResolverInterface $relationalTypeResolver,
        array $idFieldSet,
        array $idObjects,
        array $resolvedIDFieldValues,
        EngineIterationFeedbackStore $engineIterationFeedbackStore,
    ): void;

    /**
     * Add an error for every Non-Null response position that
     * is still `null` after all the directives in the pipeline
     * have been executed, and for which no error was added.
     *
     * @param array<string|int,object> $idObjects
     * @param array<string|int,SplObjectStorage<FieldInterface,mixed>> $resolvedIDFieldValues
     */
    public function endDirectivePipeline(
        RelationalTypeResolverInterface $relationalTypeResolver,
        array $idObjects,
        array $resolvedIDFieldValues,
        EngineIterationFeedbackStore $engineIterationFeedbackStore,
    ): void;
}
