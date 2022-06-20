<?php

declare(strict_types=1);

namespace PoP\ComponentModel\DirectiveResolvers;

use PoP\ComponentModel\Engine\EngineIterationFieldSet;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;

trait RemoveIDFieldSetDirectiveResolverTrait
{
    /**
     * @param array<string|int,EngineIterationFieldSet> $idFieldSetToRemove
     * @param array<array<string|int,EngineIterationFieldSet>> $succeedingPipelineIDFieldSet
     */
    protected function removeIDFieldSet(
        array $idFieldSetToRemove,
        array &$succeedingPipelineIDFieldSet
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

    /**
     * For GraphQL, set the response for the failing field as null
     *
     * @param array<string|int,EngineIterationFieldSet> $idFieldSetToSetAsNull
     * @param array<string|int,object> $idObjects
     * @param array<string|int,SplObjectStorage<FieldInterface,mixed>|null> $resolvedIDFieldValues
     */
    protected function setIDFieldSetAsNull(
        RelationalTypeResolverInterface $relationalTypeResolver,
        array $idFieldSetToSetAsNull,
        array $idObjects,
        array &$resolvedIDFieldValues,
    ): void {
        foreach (array_keys($idFieldSetToSetAsNull) as $id) {
            $fieldsToSetAsNullForID = $idFieldSetToSetAsNull[$id]->fields;
            foreach ($fieldsToSetAsNullForID as $field) {
                $fieldOutputKey = $field->getOutputKey();
                $resolvedIDFieldValues[$id][$field] = null;
            }
        }
    }
}
