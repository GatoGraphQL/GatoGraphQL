<?php

declare(strict_types=1);

namespace PoP\ComponentModel\DirectiveResolvers;

use PoP\ComponentModel\ComponentConfiguration;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;

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
    protected function setIDsDataFieldsAsNull(
        TypeResolverInterface $typeResolver,
        array &$idsDataFieldsToSetAsNull,
        array &$dbItems
    ): void {
        foreach (array_keys($idsDataFieldsToSetAsNull) as $id) {
            $fieldsToSetAsNullForID = $idsDataFieldsToSetAsNull[(string)$id]['direct'];
            foreach ($fieldsToSetAsNullForID as $field) {
                $fieldOutputKey = $this->fieldQueryInterpreter->getUniqueFieldOutputKey($typeResolver, $field);
                $dbItems[(string)$id][$fieldOutputKey] = null;
            }
        }
    }
}
