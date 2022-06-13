<?php

declare(strict_types=1);

namespace PoP\Engine\DirectiveResolvers;

use PoP\ComponentModel\DirectiveResolvers\RemoveIDsDataFieldsDirectiveResolverTrait;
use PoP\ComponentModel\Engine\EngineIterationFieldSet;
use PoP\ComponentModel\Feedback\EngineIterationFeedbackStore;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;

trait FilterIDsSatisfyingConditionDirectiveResolverTrait
{
    use RemoveIDsDataFieldsDirectiveResolverTrait;

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
        foreach ($idFieldSet as $id => $dataFields) {
            // Validate directive args for the object
            $expressions = $this->getExpressionsForObject($id, $variables, $messages);
            $object = $objectIDItems[$id];
            list(
                $objectValidDirective,
                $objectDirectiveName,
                $objectDirectiveArgs
            ) = $this->dissectAndValidateDirectiveForObject($relationalTypeResolver, $object, $dataFields->fields, $variables, $expressions, $engineIterationFeedbackStore);
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
    protected function removeDataFieldsForIDs(array $idFieldSet, array &$idsToRemove, array &$succeedingPipelineIDsDataFields)
    {
        // Calculate the $idFieldSet that must be removed from all the upcoming stages of the pipeline
        $idsDataFieldsToRemove = array_filter(
            $idFieldSet,
            fn (int | string $id) => in_array($id, $idsToRemove),
            ARRAY_FILTER_USE_KEY
        );
        $this->removeIDsDataFields(
            $idsDataFieldsToRemove,
            $succeedingPipelineIDsDataFields
        );
    }
}
