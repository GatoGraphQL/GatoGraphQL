<?php

declare(strict_types=1);

namespace PoP\ComponentModel\DirectiveResolvers;

use PoP\ComponentModel\Component;
use PoP\ComponentModel\ComponentConfiguration;
use PoP\ComponentModel\Directives\DirectiveKinds;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;

abstract class AbstractValidateDirectiveResolver extends AbstractGlobalDirectiveResolver
{
    use RemoveIDsDataFieldsDirectiveResolverTrait;

    /**
     * Validations are by default a "Schema" type directive
     */
    public function getDirectiveKind(): string
    {
        return DirectiveKinds::SCHEMA;
    }

    /**
     * Each validate can execute multiple times (eg: an added @validateIsUserLoggedIn)
     */
    public function isRepeatable(): bool
    {
        return true;
    }

    public function resolveDirective(
        RelationalTypeResolverInterface $relationalTypeResolver,
        array &$idsDataFields,
        array &$succeedingPipelineIDsDataFields,
        array &$succeedingPipelineDirectiveResolverInstances,
        array &$objectIDItems,
        array &$unionDBKeyIDs,
        array &$dbItems,
        array &$previousDBItems,
        array &$variables,
        array &$messages,
        array &$objectErrors,
        array &$objectWarnings,
        array &$objectDeprecations,
        array &$objectNotices,
        array &$objectTraces,
        array &$schemaErrors,
        array &$schemaWarnings,
        array &$schemaDeprecations,
        array &$schemaNotices,
        array &$schemaTraces
    ): void {
        $this->validateAndFilterFields($relationalTypeResolver, $idsDataFields, $succeedingPipelineIDsDataFields, $objectIDItems, $dbItems, $variables, $schemaErrors, $schemaWarnings, $schemaDeprecations);
    }

    protected function validateAndFilterFields(
        RelationalTypeResolverInterface $relationalTypeResolver,
        array &$idsDataFields,
        array &$succeedingPipelineIDsDataFields,
        array &$objectIDItems,
        array &$dbItems,
        array &$variables,
        array &$schemaErrors,
        array &$schemaWarnings,
        array &$schemaDeprecations
    ): void {
        // Validate that the schema and the provided data match, eg: passing mandatory values
        // (Such as fieldArg "status" for field "isStatus")
        // Combine all the datafields under all IDs
        $dataFields = $failedDataFields = [];
        foreach ($idsDataFields as $id => $data_fields) {
            $dataFields = array_values(array_unique(array_merge(
                $dataFields,
                $data_fields['direct']
            )));
        }
        $this->validateFields($relationalTypeResolver, $dataFields, $schemaErrors, $schemaWarnings, $schemaDeprecations, $variables, $failedDataFields);

        // Remove from the data_fields list to execute on the object for the next stages of the pipeline
        if ($failedDataFields) {
            $idsDataFieldsToRemove = [];
            foreach ($idsDataFields as $id => $dataFields) {
                $idsDataFieldsToRemove[(string)$id]['direct'] = array_intersect(
                    $dataFields['direct'],
                    $failedDataFields
                );
            }
            $this->removeIDsDataFields(
                $idsDataFieldsToRemove,
                $succeedingPipelineIDsDataFields
            );
            if (ComponentConfiguration::setFailingFieldResponseAsNull()) {
                $this->setIDsDataFieldsAsNull(
                    $relationalTypeResolver,
                    $idsDataFieldsToRemove,
                    $dbItems,
                    $objectIDItems,
                );
            }
        }
        // Since adding the Validate directive also when processing the conditional fields, there is no need to validate them now
        // They will be validated when it's their turn to be processed
        // // Validate conditional fields and, if they fail, already take them out from the `$idsDataFields` object
        // $dataFields = $failedDataFields = [];
        // // Because on the leaves we encounter an empty array, all fields are conditional fields (even if they are on the leaves)
        // foreach ($idsDataFields as $id => $data_fields) {
        //     foreach ($data_fields['conditional'] as $conditionField => $conditionalFields) {
        //         $this->validateAndFilterConditionalFields($relationalTypeResolver, $conditionField, $idsDataFields[$id]['conditional'], $dataFields, $schemaErrors, $schemaWarnings, $schemaDeprecations, $variables, $failedDataFields);
        //     }
        // }
    }

    abstract protected function validateFields(RelationalTypeResolverInterface $relationalTypeResolver, array $dataFields, array &$schemaErrors, array &$schemaWarnings, array &$schemaDeprecations, array &$variables, array &$failedDataFields): void;
}
