<?php

declare(strict_types=1);

namespace PoPSchema\DirectiveCommons\DirectiveResolvers;

use PoPSchema\DirectiveCommons\FeedbackItemProviders\FeedbackItemProvider;
use PoPSchema\DirectiveCommons\ObjectModels\TypedDataValidationPayload;
use PoP\ComponentModel\Engine\EngineIterationFieldSet;
use PoP\ComponentModel\Feedback\EngineIterationFeedbackStore;
use PoP\ComponentModel\Feedback\ObjectResolutionFeedback;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\AstInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\ComponentModel\Feedback\FeedbackItemResolution;
use SplObjectStorage;

abstract class AbstractTransformTypedFieldValueFieldDirectiveResolver extends AbstractTransformFieldValueFieldDirectiveResolver
{
    /**
     * @param array<array<string|int,EngineIterationFieldSet>> $succeedingPipelineIDFieldSet
     * @param array<string|int,SplObjectStorage<FieldInterface,mixed>> $resolvedIDFieldValues
     */
    final protected function transformValue(
        mixed $value,
        string|int $id,
        FieldInterface $field,
        RelationalTypeResolverInterface $relationalTypeResolver,
        array &$succeedingPipelineIDFieldSet,
        array &$resolvedIDFieldValues,
        EngineIterationFeedbackStore $engineIterationFeedbackStore,
    ): mixed {
        if (
            $value === null
            && $this->skipNullValue()
        ) {
            return null;
        }

        if (!$this->isMatchingType($value)) {
            $this->handleError(
                $value,
                $id,
                $field,
                $relationalTypeResolver,
                $succeedingPipelineIDFieldSet,
                $resolvedIDFieldValues,
                $this->getNonMatchingTypeValueFeedbackItemResolution(
                    $value,
                    $id,
                    $field,
                    $relationalTypeResolver,
                ),
                $this->directive,
                $engineIterationFeedbackStore,
            );
            return null;
        }

        $this->loadObjectResolvedDynamicVariablesInAppState($field, $id);
        $typedDataValidationPayload = $this->validateTypeData($value);
        $this->resetObjectResolvedDynamicVariablesInAppState();
        
        if ($typedDataValidationPayload !== null) {
            $this->handleError(
                $value,
                $id,
                $field,
                $relationalTypeResolver,
                $succeedingPipelineIDFieldSet,
                $resolvedIDFieldValues,
                $typedDataValidationPayload->feedbackItemResolution,
                $typedDataValidationPayload->astNode ?? $this->directive,
                $engineIterationFeedbackStore,
            );
            return null;
        }

        /**
         * Also the actual transformation could raise errors,
         * and these can't be validated on the step before.
         *
         * Eg: `preg_replace` may throw an error if the regex
         * pattern is not right.
         */
        $this->loadObjectResolvedDynamicVariablesInAppState($field, $id);
        $transformedTypeValue = $this->transformTypeValue($value);
        $this->resetObjectResolvedDynamicVariablesInAppState();
        
        if ($transformedTypeValue instanceof TypedDataValidationPayload) {
            /** @var TypedDataValidationPayload */
            $typedDataValidationPayload = $transformedTypeValue;
            $this->handleError(
                $value,
                $id,
                $field,
                $relationalTypeResolver,
                $succeedingPipelineIDFieldSet,
                $resolvedIDFieldValues,
                $typedDataValidationPayload->feedbackItemResolution,
                $typedDataValidationPayload->astNode ?? $this->directive,
                $engineIterationFeedbackStore,
            );
            return null;
        }
        return $transformedTypeValue;
    }

    protected function skipNullValue(): bool
    {
        return true;
    }

    abstract protected function isMatchingType(mixed $value): bool;

    /**
     * Validate the value against the directive args
     */
    protected function validateTypeData(mixed $value): ?TypedDataValidationPayload
    {
        return null;
    }

    /**
     * @return mixed TypedDataValidationPayload if error, or the value otherwise
     */
    abstract protected function transformTypeValue(mixed $value): mixed;

    /**
     * @param array<array<string|int,EngineIterationFieldSet>> $succeedingPipelineIDFieldSet
     * @param array<string|int,SplObjectStorage<FieldInterface,mixed>> $resolvedIDFieldValues
     */
    private function handleError(
        mixed $value,
        string|int $id,
        FieldInterface $field,
        RelationalTypeResolverInterface $relationalTypeResolver,
        array &$succeedingPipelineIDFieldSet,
        array &$resolvedIDFieldValues,
        FeedbackItemResolution $feedbackItemResolution,
        AstInterface $astNode,
        EngineIterationFeedbackStore $engineIterationFeedbackStore,
    ): void {
        /** @var array<string|int,EngineIterationFieldSet> */
        $idFieldSetToRemove = [
            $id => new EngineIterationFieldSet([$field]),
        ];

        $this->removeIDFieldSet(
            $succeedingPipelineIDFieldSet,
            $idFieldSetToRemove,
        );
        $this->setFieldResponseValueAsNull(
            $resolvedIDFieldValues,
            $idFieldSetToRemove,
        );

        $engineIterationFeedbackStore->objectResolutionFeedbackStore->addError(
            new ObjectResolutionFeedback(
                $feedbackItemResolution,
                $astNode,
                $relationalTypeResolver,
                $this->directive,
                $idFieldSetToRemove
            )
        );
    }

    protected function getNonMatchingTypeValueFeedbackItemResolution(
        mixed $value,
        string|int $id,
        FieldInterface $field,
        RelationalTypeResolverInterface $relationalTypeResolver,
    ): FeedbackItemResolution {
        return new FeedbackItemResolution(
            FeedbackItemProvider::class,
            FeedbackItemProvider::E1,
            [
                $this->getDirectiveName(),
                $field->getOutputKey(),
                $id,
            ]
        );
    }
}
