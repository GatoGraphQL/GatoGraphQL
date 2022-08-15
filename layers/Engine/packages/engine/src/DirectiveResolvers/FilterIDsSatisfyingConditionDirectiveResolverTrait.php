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
     * Check the condition field. If it is satisfied, then skip those fields.
     *
     * @param array<string|int,EngineIterationFieldSet> $idFieldSet
     * @return array<string|int>
     */
    protected function getIDsSatisfyingCondition(
        RelationalTypeResolverInterface $relationalTypeResolver,
        array $idObjects,
        array $idFieldSet,
        array &$messages,
        EngineIterationFeedbackStore $engineIterationFeedbackStore,
    ): array {
        $directiveArgs = $this->directiveDataAccessor->getDirectiveArgs();
        $idsSatisfyingCondition = [];
        foreach (array_keys($idFieldSet) as $id) {
            if ($directiveArgs['if'] ?? null) {
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
            fn (int|string $id) => in_array($id, $idsToRemove),
            ARRAY_FILTER_USE_KEY
        );
        $this->removeIDFieldSet(
            $succeedingPipelineIDFieldSet,
            $idFieldSetToRemove,
        );
    }
}
