<?php

declare(strict_types=1);

namespace PoP\ComponentModel\DirectiveResolvers;

use PoP\ComponentModel\Engine\EngineIterationFieldSet;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;

trait RemoveIDsDataFieldsDirectiveResolverTrait
{
    /**
     * @param array<string|int,EngineIterationFieldSet> $idsDataFieldsToRemove
     * @param array<array<string|int,EngineIterationFieldSet>> $succeedingPipelineIDFieldSet
     */
    protected function removeIDFieldSet(
        array $idsDataFieldsToRemove,
        array &$succeedingPipelineIDFieldSet
    ): void {
        // For each combination of ID and field, remove them from the upcoming pipeline stages
        foreach ($idsDataFieldsToRemove as $id => $fieldSet) {
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
     * @param array<string|int,EngineIterationFieldSet> $idsDataFieldsToSetAsNull
     * @param array<string|int,object> $objectIDItems
     */
    protected function setIDsDataFieldsAsNull(
        RelationalTypeResolverInterface $relationalTypeResolver,
        array $idsDataFieldsToSetAsNull,
        array $objectIDItems,
        array &$dbItems,
    ): void {
        foreach (array_keys($idsDataFieldsToSetAsNull) as $id) {
            $fieldsToSetAsNullForID = $idsDataFieldsToSetAsNull[$id]->fields;
            foreach ($fieldsToSetAsNullForID as $field) {
                $fieldOutputKey = $field->getOutputKey();
                $dbItems[$id][$fieldOutputKey] = null;
            }
        }
    }
}
