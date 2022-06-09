<?php

declare(strict_types=1);

namespace PoP\ComponentModel\DirectiveResolvers;

use PoP\ComponentModel\Engine\EngineIterationFieldSet;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;

trait RemoveIDsDataFieldsDirectiveResolverTrait
{
    /**
     * @param array<string|int,EngineIterationFieldSet> $idsDataFieldsToRemove
     * @param array<array<string|int,EngineIterationFieldSet>> $succeedingPipelineIDsDataFields
     */
    protected function removeIDsDataFields(
        array $idsDataFieldsToRemove,
        array &$succeedingPipelineIDsDataFields
    ): void {
        // For each combination of ID and field, remove them from the upcoming pipeline stages
        foreach ($idsDataFieldsToRemove as $id => $dataFields) {
            foreach ($succeedingPipelineIDsDataFields as &$pipelineStageIDsDataFields) {
                // The next stage may or may not deal with this ID
                if (!array_key_exists((string)$id, $pipelineStageIDsDataFields)) {
                    continue;
                }
                $pipelineStageIDsDataFields[(string)$id]->direct = array_diff(
                    $pipelineStageIDsDataFields[(string)$id]->direct,
                    $dataFields->direct
                );
                foreach ($dataFields->direct as $removeField) {
                    $pipelineStageIDsDataFields[(string)$id]->conditional->detach($removeField);
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
            $object = $objectIDItems[$id];
            $fieldsToSetAsNullForID = $idsDataFieldsToSetAsNull[(string)$id]->direct;
            foreach ($fieldsToSetAsNullForID as $field) {
                $fieldOutputKey = $this->getFieldQueryInterpreter()->getUniqueFieldOutputKey($relationalTypeResolver, $field->asFieldOutputQueryString(), $object);
                $dbItems[(string)$id][$fieldOutputKey] = null;
            }
        }
    }
}
