<?php

declare(strict_types=1);

namespace PoP\Engine\DirectiveResolvers;

use PoP\ComponentModel\DirectiveResolvers\RemoveIDFieldSetDirectiveResolverTrait;
use PoP\ComponentModel\Engine\EngineIterationFieldSet;
use PoP\ComponentModel\Feedback\EngineIterationFeedbackStore;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;

trait FilterIDsSatisfyingConditionDirectiveResolverTrait
{
    use RemoveIDFieldSetDirectiveResolverTrait;

    /**
     * @param array<string|int,EngineIterationFieldSet> $idFieldSet
     * @return array<string|int>
     */
    protected function getIDsSatisfyingCondition(
        RelationalTypeResolverInterface $relationalTypeResolver,
        array $objectIDItems,
        array $idFieldSet,
        array &$variables,
        array &$messages,
        EngineIterationFeedbackStore $engineIterationFeedbackStore,
    ): array {
        // Check the condition field. If it is satisfied, then skip those fields
        $idsSatisfyingCondition = [];
        foreach ($idFieldSet as $id => $fieldSet) {
            // Validate directive args for the object
            $expressions = $this->getExpressionsForObject($id, $variables, $messages);
            $object = $objectIDItems[$id];
            list(
                $objectValidDirective,
                $objectDirectiveName,
                $objectDirectiveArgs
            ) = $this->dissectAndValidateDirectiveForObject($relationalTypeResolver, $object, $fieldSet->fields, $variables, $expressions, $engineIterationFeedbackStore);
            // Check that the directive is valid. If it is not, $objectErrors will have the error already added
            if (is_null($objectValidDirective)) {
                continue;
            }
            // $objectDirectiveArgs has all the right directiveArgs values. Now we can evaluate on it
            if ($objectDirectiveArgs['if'] ?? null) {
                $idsSatisfyingCondition[] = $id;
            }
        }
        return $idsSatisfyingCondition;
    }

    /**
     * @param array<string|int,EngineIterationFieldSet> $idFieldSet
     */
    protected function removeFieldSetForIDs(array $idFieldSet, array &$idsToRemove, array &$succeedingPipelineIDFieldSet)
    {
        // Calculate the $idFieldSet that must be removed from all the upcoming stages of the pipeline
        $idFieldSetToRemove = array_filter(
            $idFieldSet,
            fn (int | string $id) => in_array($id, $idsToRemove),
            ARRAY_FILTER_USE_KEY
        );
        $this->removeIDFieldSet(
            $idFieldSetToRemove,
            $succeedingPipelineIDFieldSet
        );
    }
}
