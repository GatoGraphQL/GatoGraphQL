<?php

declare(strict_types=1);

namespace PoP\ComponentModel\DirectiveResolvers;

use PoP\ComponentModel\Engine\EngineIterationFieldSet;

trait RemoveIDFieldSetDirectiveResolverTrait
{
    /**
     * @param array<array<string|int,EngineIterationFieldSet>> $succeedingPipelineIDFieldSet
     * @param array<string|int,EngineIterationFieldSet> $idFieldSetToRemove
     */
    protected function removeIDFieldSet(
        array &$succeedingPipelineIDFieldSet,
        array $idFieldSetToRemove,
    ): void {
        // For each combination of ID and field, remove them from the upcoming pipeline stages
        foreach ($idFieldSetToRemove as $id => $fieldSet) {
            foreach ($succeedingPipelineIDFieldSet as &$pipelineStageIDFieldSet) {
                // The next stage may or may not deal with this ID
                if (!array_key_exists($id, $pipelineStageIDFieldSet)) {
                    continue;
                }
                $pipelineStageIDFieldSet[$id]->fields = array_diff(
                    $pipelineStageIDFieldSet[$id]->fields,
                    $fieldSet->fields
                );
                foreach ($fieldSet->fields as $removeField) {
                    $pipelineStageIDFieldSet[$id]->conditionalFields->detach($removeField);
                }
            }
        }
    }
}
