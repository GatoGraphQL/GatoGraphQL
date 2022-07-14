<?php

declare(strict_types=1);

namespace PoP\ComponentModel\DirectiveResolvers;

use PoP\ComponentModel\Engine\EngineIterationFieldSet;
use PoP\ComponentModel\Feedback\EngineIterationFeedbackStore;
use PoP\ComponentModel\Feedback\ObjectResolutionFeedback;
use PoP\ComponentModel\Feedback\SchemaFeedback;
use PoP\ComponentModel\FeedbackItemProviders\ErrorFeedbackItemProvider;
use PoP\ComponentModel\QueryResolution\FieldDataAccessProviderInterface;
use PoP\ComponentModel\TypeResolvers\PipelinePositions;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\Root\Feedback\FeedbackItemResolution;

abstract class AbstractValidateConditionDirectiveResolver extends AbstractValidateDirectiveResolver
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
            return PipelinePositions::AFTER_RESOLVE_BEFORE_SERIALIZE;
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
        array &$variables,
        EngineIterationFeedbackStore $engineIterationFeedbackStore,
    ): array {
        if ($this->isValidationSuccessful($relationalTypeResolver)) {
            return [];
        }
        // All fields failed
        $engineIterationFeedbackStore->objectFeedbackStore->addError(
            new ObjectResolutionFeedback(
                $this->getValidationFailedFeedbackItemResolution($relationalTypeResolver, $idFieldSet),
                $this->directive,
                $relationalTypeResolver,
                $this->directive,
                $idFieldSet,
            )
        );
        return $idFieldSet;
    }

    /**
     * Condition to validate. Return `true` for success, `false` for failure
     */
    abstract protected function isValidationSuccessful(RelationalTypeResolverInterface $relationalTypeResolver): bool;

    /**
     * Show a different error message depending on if we are validating the whole field, or a directive
     * By default, validate the whole field
     */
    protected function isValidatingDirective(): bool
    {
        return false;
    }

    /**
     * @param array<string|int,EngineIterationFieldSet> $idFieldSet
     */
    abstract protected function getValidationFailedFeedbackItemResolution(RelationalTypeResolverInterface $relationalTypeResolver, array $idFieldSet): FeedbackItemResolution;

    /**
     * @param array<string|int,EngineIterationFieldSet> $idFieldSet
     * @param FieldInterface[]
     */
    protected function getFieldsFromIDFieldSet(array $idFieldSet): array
    {
        /** @var FieldInterface[] */
        $fields = [];
        foreach ($idFieldSet as $id => $fieldSet) {
            $fields = array_merge(
                $fields,
                $fieldSet->fields
            );
        }
        return array_values(array_unique($fields));
    }
}
