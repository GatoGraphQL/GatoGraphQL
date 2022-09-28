<?php

declare(strict_types=1);

namespace PoP\Engine\DirectiveResolvers;

use PoP\ComponentModel\Engine\EngineIterationFieldSet;
use PoP\ComponentModel\Feedback\EngineIterationFeedbackStore;
use PoP\ComponentModel\QueryResolution\FieldDataAccessProviderInterface;
use PoP\ComponentModel\TypeResolvers\PipelinePositions;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;

abstract class AbstractValidateConditionFieldDirectiveResolver extends AbstractValidateFieldDirectiveResolver
{
    // /**
    //  * Validations are naturally added through code and not through the query, so no need to expose them in the schema
    //  */
    // public function skipExposingDirectiveInSchema(RelationalTypeResolverInterface $relationalTypeResolver): bool {
    //     return true;
    // }

    /**
     * If validating a directive, place it after resolveAndMerge
     * Otherwise, before
     */
    public function getPipelinePosition(): string
    {
        if ($this->isValidatingDirective()) {
            return PipelinePositions::AFTER_RESOLVE;
        }
        return PipelinePositions::BEFORE_RESOLVE;
    }

    /**
     * @param array<string|int,EngineIterationFieldSet> $idFieldSet
     * @return array<string|int,EngineIterationFieldSet> Failed $idFieldSet
     */
    protected function validateIDFieldSet(
        RelationalTypeResolverInterface $relationalTypeResolver,
        array $idFieldSet,
        FieldDataAccessProviderInterface $fieldDataAccessProvider,
        EngineIterationFeedbackStore $engineIterationFeedbackStore,
    ): array {
        if ($this->isValidationSuccessful($relationalTypeResolver)) {
            return [];
        }
        // All fields failed
        $this->addUnsuccessfulValidationErrors(
            $relationalTypeResolver,
            $idFieldSet,
            $fieldDataAccessProvider,
            $engineIterationFeedbackStore,
        );
        return $idFieldSet;
    }

    /**
     * Condition to validate. Return `true` for success, `false` for failure
     */
    abstract protected function isValidationSuccessful(RelationalTypeResolverInterface $relationalTypeResolver): bool;

    /**
     * Add the errors to the FeedbackStore
     *
     * @param array<string|int,EngineIterationFieldSet> $idFieldSet
     */
    abstract protected function addUnsuccessfulValidationErrors(
        RelationalTypeResolverInterface $relationalTypeResolver,
        array $idFieldSet,
        FieldDataAccessProviderInterface $fieldDataAccessProvider,
        EngineIterationFeedbackStore $engineIterationFeedbackStore,
    ): void;

    /**
     * Show a different error message depending on if we are validating the whole field, or a directive
     * By default, validate the whole field
     */
    protected function isValidatingDirective(): bool
    {
        return false;
    }

    /**
     * @param array<string|int> $ids
     * @return array<string|int,EngineIterationFieldSet>
     */
    protected function getFieldIDSetForField(FieldInterface $field, array $ids): array
    {
        $fieldIDFieldSet = [];
        $fieldEngineIterationFieldSet = new EngineIterationFieldSet([$field]);
        foreach ($ids as $id) {
            $fieldIDFieldSet[$id] = $fieldEngineIterationFieldSet;
        }
        return $fieldIDFieldSet;
    }
}
