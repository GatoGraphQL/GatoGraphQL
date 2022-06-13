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
    use RemoveIDFieldSetDirectiveResolverTrait;

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
     * @param array<string|int,EngineIterationFieldSet> $idFieldSet
     * @param array<array<string|int,EngineIterationFieldSet>> $succeedingPipelineIDFieldSet
     */
    public function resolveDirective(
        RelationalTypeResolverInterface $relationalTypeResolver,
        array $idFieldSet,
        array $succeedingPipelineDirectiveResolverInstances,
        array $objectIDItems,
        array $unionDBKeyIDs,
        array $previousDBItems,
        array &$succeedingPipelineIDFieldSet,
        array &$dbItems,
        array &$variables,
        array &$messages,
        EngineIterationFeedbackStore $engineIterationFeedbackStore,
    ): void {
        $this->validateAndFilterFields($relationalTypeResolver, $idFieldSet, $succeedingPipelineIDFieldSet, $objectIDItems, $dbItems, $variables, $engineIterationFeedbackStore);
    }

    /**
     * @param array<string|int,EngineIterationFieldSet> $idFieldSet
     */
    protected function validateAndFilterFields(
        RelationalTypeResolverInterface $relationalTypeResolver,
        array $idFieldSet,
        array &$succeedingPipelineIDFieldSet,
        array $objectIDItems,
        array &$dbItems,
        array &$variables,
        EngineIterationFeedbackStore $engineIterationFeedbackStore,
    ): void {
        // Validate that the schema and the provided data match, eg: passing mandatory values
        // (Such as fieldArg "status" for field "isStatus")
        // Combine all the fields under all IDs
        $fields = $failedFields = [];
        foreach ($idFieldSet as $id => $fieldSet) {
            $fields = array_values(array_unique(array_merge(
                $fields,
                $fieldSet->fields
            )));
        }
        $this->validateFields($relationalTypeResolver, $fields, $variables, $engineIterationFeedbackStore, $failedFields);

        // Remove from the data_fields list to execute on the object for the next stages of the pipeline
        if ($failedFields) {
            /** @var array<string|int,EngineIterationFieldSet> */
            $idFieldSetToRemove = [];
            foreach ($idFieldSet as $id => $fieldSet) {
                $idFieldSetToRemove[$id] = new EngineIterationFieldSet(
                    array_intersect(
                        $fieldSet->fields,
                        $failedFields
                    )
                );
            }
            $this->removeIDFieldSet(
                $idFieldSetToRemove,
                $succeedingPipelineIDFieldSet
            );
            /** @var ModuleConfiguration */
            $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
            if ($moduleConfiguration->setFailingFieldResponseAsNull()) {
                $this->setIDFieldSetAsNull(
                    $relationalTypeResolver,
                    $idFieldSetToRemove,
                    $objectIDItems,
                    $dbItems,
                );
            }
        }
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
