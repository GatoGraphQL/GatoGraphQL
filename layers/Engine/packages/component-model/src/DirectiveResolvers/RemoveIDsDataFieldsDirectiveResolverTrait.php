<?php

declare(strict_types=1);

namespace PoP\ComponentModel\DirectiveResolvers;

use PoP\ComponentModel\ComponentConfiguration;

trait RemoveIDsDataFieldsDirectiveResolverTrait
{
    protected function removeIDsDataFields(
        array &$idsDataFieldsToRemove,
        array &$succeedingPipelineIDsDataFields
    ): void {
        // For each combination of ID and field, remove them from the upcoming pipeline stages
        foreach ($idsDataFieldsToRemove as $id => $dataFields) {
            foreach ($succeedingPipelineIDsDataFields as &$pipelineStageIDsDataFields) {
                // The next stage may or may not deal with this ID
                if (!array_key_exists((string)$id, $pipelineStageIDsDataFields)) {
                    continue;
                }
                $pipelineStageIDsDataFields[(string)$id]['direct'] = array_diff(
                    $pipelineStageIDsDataFields[(string)$id]['direct'],
                    $dataFields['direct']
                );
                foreach ($dataFields['direct'] as $removeField) {
                    unset($pipelineStageIDsDataFields[(string)$id]['conditional'][$removeField]);
                }
            }
        }
    }

    /**
     * For GraphQL, set the response for the failing field as null
     */
    protected function maybeSetFailingFieldResponseAsNull(
        array &$idsDataFieldsToRemove,
        array &$dbItems
    ): void {
        if (ComponentConfiguration::setFailingFieldResponseAsNull()) {
            foreach (array_keys($idsDataFieldsToRemove) as $id) {
                $fieldsToRemoveForID = $idsDataFieldsToRemove[(string)$id]['direct'];
                foreach ($fieldsToRemoveForID as $field) {
                    $fieldOutputKey = $this->fieldQueryInterpreter->getFieldOutputKey($field);
                    $dbItems[(string)$id][$fieldOutputKey] = null;
                }
            }
        }
    }
}
