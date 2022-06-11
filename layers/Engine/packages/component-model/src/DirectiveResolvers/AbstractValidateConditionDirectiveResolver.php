<?php

declare(strict_types=1);

namespace PoP\ComponentModel\DirectiveResolvers;

use PoP\ComponentModel\Feedback\EngineIterationFeedbackStore;
use PoP\ComponentModel\Feedback\SchemaFeedback;
use PoP\ComponentModel\FeedbackItemProviders\ErrorFeedbackItemProvider;
use PoP\ComponentModel\TypeResolvers\PipelinePositions;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\GraphQLParser\StaticHelpers\LocationHelper;
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
            return PipelinePositions::AFTER_RESOLVE;
        }
        return PipelinePositions::AFTER_VALIDATE_BEFORE_RESOLVE;
    }

    /**
     * Validate a custom condition
     *
     * @param FieldInterface[] $fields
     * @param FieldInterface[] $failedFields
     */
    protected function validateFields(
        RelationalTypeResolverInterface $relationalTypeResolver,
        array $fields,
        array &$variables,
        EngineIterationFeedbackStore $engineIterationFeedbackStore,
        array &$failedFields,
    ): void {
        if ($this->isValidationSuccessful($relationalTypeResolver)) {
            return;
        }
        // All fields failed
        $failedFields = array_merge(
            $failedFields,
            $fields
        );
        foreach ($fields as $field) {
            $engineIterationFeedbackStore->schemaFeedbackStore->addError(
                new SchemaFeedback(
                    $this->getValidationFailedFeedbackItemResolution($relationalTypeResolver, $fields),
                    LocationHelper::getNonSpecificLocation(),
                    $relationalTypeResolver,
                    $field,
                    $this->directive,
                )
            );
        }
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
     * @param FieldInterface[] $failedFields
     */
    protected function getValidationFailedFeedbackItemResolution(RelationalTypeResolverInterface $relationalTypeResolver, array $failedFields): FeedbackItemResolution
    {
        return new FeedbackItemResolution(
            ErrorFeedbackItemProvider::class,
            $this->isValidatingDirective() ? ErrorFeedbackItemProvider::E18 : ErrorFeedbackItemProvider::E19,
            [
                implode(
                    $this->__('\', \''),
                    array_map(
                        fn (FieldInterface $field) => $field->asFieldOutputQueryString(),
                        $failedFields
                    )
                ),
            ]
        );
    }
}
