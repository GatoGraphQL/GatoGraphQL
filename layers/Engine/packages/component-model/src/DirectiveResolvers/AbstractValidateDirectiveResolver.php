<?php

declare(strict_types=1);

namespace PoP\ComponentModel\DirectiveResolvers;

use PoP\ComponentModel\Directives\DirectiveKinds;
use PoP\ComponentModel\Engine\EngineIterationFieldSet;
use PoP\ComponentModel\Feedback\EngineIterationFeedbackStore;
use PoP\ComponentModel\Module;
use PoP\ComponentModel\ModuleConfiguration;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\Root\App;

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

    /**
     * @param array<string|int,EngineIterationFieldSet> $idsDataFields
     * @param array<array<string|int,EngineIterationFieldSet>> $succeedingPipelineIDsDataFields
     */
    public function resolveDirective(
        RelationalTypeResolverInterface $relationalTypeResolver,
        array $idsDataFields,
        array $succeedingPipelineDirectiveResolverInstances,
        array $objectIDItems,
        array $unionDBKeyIDs,
        array $previousDBItems,
        array &$succeedingPipelineIDsDataFields,
        array &$dbItems,
        array &$variables,
        array &$messages,
        EngineIterationFeedbackStore $engineIterationFeedbackStore,
    ): void {
        $this->validateAndFilterFields($relationalTypeResolver, $idsDataFields, $succeedingPipelineIDsDataFields, $objectIDItems, $dbItems, $variables, $engineIterationFeedbackStore);
    }

    /**
     * @param array<string|int,EngineIterationFieldSet> $idsDataFields
     */
    protected function validateAndFilterFields(
        RelationalTypeResolverInterface $relationalTypeResolver,
        array $idsDataFields,
        array &$succeedingPipelineIDsDataFields,
        array $objectIDItems,
        array &$dbItems,
        array &$variables,
        EngineIterationFeedbackStore $engineIterationFeedbackStore,
    ): void {
        // Validate that the schema and the provided data match, eg: passing mandatory values
        // (Such as fieldArg "status" for field "isStatus")
        // Combine all the datafields under all IDs
        $fields = $failedFields = [];
        foreach ($idsDataFields as $id => $data_fields) {
            $fields = array_values(array_unique(array_merge(
                $fields,
                $data_fields->direct
            )));
        }
        $this->validateFields($relationalTypeResolver, $fields, $variables, $engineIterationFeedbackStore, $failedFields);

        // Remove from the data_fields list to execute on the object for the next stages of the pipeline
        if ($failedFields) {
            $idsDataFieldsToRemove = [];
            foreach ($idsDataFields as $id => $dataFields) {
                $idsDataFieldsToRemove[(string)$id]['direct'] = array_intersect(
                    $dataFields['direct'],
                    $failedFields
                );
            }
            $this->removeIDsDataFields(
                $idsDataFieldsToRemove,
                $succeedingPipelineIDsDataFields
            );
            /** @var ModuleConfiguration */
            $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
            if ($moduleConfiguration->setFailingFieldResponseAsNull()) {
                $this->setIDsDataFieldsAsNull(
                    $relationalTypeResolver,
                    $idsDataFieldsToRemove,
                    $objectIDItems,
                    $dbItems,
                );
            }
        }
        // Since adding the Validate directive also when processing the conditional fields, there is no need to validate them now
        // They will be validated when it's their turn to be processed
        // // Validate conditional fields and, if they fail, already take them out from the `$idsDataFields` object
        // $dataFields = $failedFields = [];
        // // Because on the leaves we encounter an empty array, all fields are conditional fields (even if they are on the leaves)
        // foreach ($idsDataFields as $id => $data_fields) {
        //     foreach ($data_fields->conditional as $conditionField => $conditionalFields) {
        //         $this->validateAndFilterConditionalFields($relationalTypeResolver, $conditionField, $idsDataFields[$id]->conditional, $dataFields, $variables, $failedFields);
        //     }
        // }
    }

    /**
     * @param FieldInterface[] $fields
     * @param FieldInterface[] $failedFields
     */
    abstract protected function validateFields(
        RelationalTypeResolverInterface $relationalTypeResolver,
        array $fields,
        array &$variables,
        EngineIterationFeedbackStore $engineIterationFeedbackStore,
        array &$failedFields,
    ): void;
}
