<?php

declare(strict_types=1);

namespace PoP\ComponentModel\DirectiveResolvers;

use PoP\ComponentModel\Component;
use PoP\ComponentModel\ComponentConfiguration;
use PoP\ComponentModel\Container\ServiceTags\MandatoryDirectiveServiceTagInterface;
use PoP\ComponentModel\Directives\DirectiveKinds;
use PoP\ComponentModel\Error\Error;
use PoP\ComponentModel\Error\ErrorServiceInterface;
use PoP\ComponentModel\Feedback\EngineIterationFeedbackStore;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\Feedback\Tokens;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\ComponentModel\TypeResolvers\PipelinePositions;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\Root\App;

final class ResolveValueAndMergeDirectiveResolver extends AbstractGlobalDirectiveResolver implements MandatoryDirectiveServiceTagInterface
{
    private ?ErrorServiceInterface $errorService = null;

    final public function setErrorService(ErrorServiceInterface $errorService): void
    {
        $this->errorService = $errorService;
    }
    final protected function getErrorService(): ErrorServiceInterface
    {
        return $this->errorService ??= $this->instanceManager->getInstance(ErrorServiceInterface::class);
    }

    public function getDirectiveName(): string
    {
        return 'resolveValueAndMerge';
    }

    /**
     * This is a system directive
     */
    public function getDirectiveKind(): string
    {
        return DirectiveKinds::SYSTEM;
    }

    /**
     * This directive must be the first one of its group
     */
    public function getPipelinePosition(): string
    {
        return PipelinePositions::AFTER_RESOLVE;
    }

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
        // Iterate data, extract into final results
        if (!$objectIDItems) {
            return;
        }
        $this->resolveValueForObjects(
            $relationalTypeResolver,
            $objectIDItems,
            $idsDataFields,
            $dbItems,
            $previousDBItems,
            $variables,
            $messages,
            $engineIterationFeedbackStore,
            $objectErrors,
            $objectWarnings,
            $objectDeprecations
        );
    }

    private function resolveValueForObjects(
        RelationalTypeResolverInterface $relationalTypeResolver,
        array $objectIDItems,
        array $idsDataFields,
        array &$dbItems,
        array $previousDBItems,
        array &$variables,
        array &$messages,
        EngineIterationFeedbackStore $engineIterationFeedbackStore,
        array &$objectErrors,
        array &$objectWarnings,
        array &$objectDeprecations,
    ): void {
        $enqueueFillingObjectsFromIDs = [];
        foreach (array_keys($idsDataFields) as $id) {
            // Obtain its ID and the required data-fields for that ID
            $object = $objectIDItems[$id];
            // It could be that the object is NULL. For instance: a post has a location stored a meta value, and the corresponding location object was deleted, so the ID is pointing to a non-existing object
            // In that case, simply return a dbError, and set the result as an empty array
            if ($object === null) {
                $objectErrors[(string)$id][] = [
                    Tokens::PATH => ['id'],
                    Tokens::MESSAGE => sprintf(
                        $this->__('Corrupted data: Object with ID \'%s\' doesn\'t exist', 'component-model'),
                        $id
                    ),
                ];
                // This is currently pointing to NULL and returning this entry in the database. Remove it
                // (this will also avoid errors in the Engine, which expects this result to be an array and can't be null)
                unset($dbItems[(string)$id]);
                continue;
            }

            $expressions = $this->getExpressionsForObject($id, $variables, $messages);
            $this->resolveValuesForObject(
                $relationalTypeResolver,
                $id,
                $object,
                $idsDataFields[(string)$id]['direct'],
                $dbItems,
                $previousDBItems,
                $variables,
                $expressions,
                $engineIterationFeedbackStore,
                $objectErrors,
                $objectWarnings,
                $objectDeprecations
            );

            // Add the conditional data fields
            // If the conditionalDataFields are empty, we already reached the end of the tree. Nothing else to do
            foreach (array_filter($idsDataFields[$id]['conditional']) as $conditionDataField => $conditionalDataFields) {
                // Check if the condition field has value `true`
                // All 'conditional' fields must have their own key as 'direct', then simply look for this element on $dbItems
                $conditionFieldOutputKey = $this->getFieldQueryInterpreter()->getUniqueFieldOutputKey($relationalTypeResolver, $conditionDataField, $object);
                if (isset($dbItems[$id]) && array_key_exists($conditionFieldOutputKey, $dbItems[$id])) {
                    $conditionSatisfied = (bool)$dbItems[$id][$conditionFieldOutputKey];
                } else {
                    $conditionSatisfied = false;
                }
                if ($conditionSatisfied) {
                    $enqueueFillingObjectsFromIDs[(string)$id]['direct'] = array_unique(array_merge(
                        $enqueueFillingObjectsFromIDs[(string)$id]['direct'] ?? [],
                        array_keys($conditionalDataFields)
                    ));
                    foreach ($conditionalDataFields as $nextConditionDataField => $nextConditionalDataFields) {
                        $enqueueFillingObjectsFromIDs[(string)$id]['conditional'][$nextConditionDataField] = array_merge_recursive(
                            $enqueueFillingObjectsFromIDs[(string)$id]['conditional'][$nextConditionDataField] ?? [],
                            $nextConditionalDataFields
                        );
                    }
                }
            }
        }
        // Enqueue items for the next iteration
        if ($enqueueFillingObjectsFromIDs) {
            $relationalTypeResolver->enqueueFillingObjectsFromIDs($enqueueFillingObjectsFromIDs);
        }
    }

    private function resolveValuesForObject(
        RelationalTypeResolverInterface $relationalTypeResolver,
        string | int $id,
        object $object,
        array $dataFields,
        array &$dbItems,
        array $previousDBItems,
        array &$variables,
        array &$expressions,
        EngineIterationFeedbackStore $engineIterationFeedbackStore,
        array &$objectErrors,
        array &$objectWarnings,
        array &$objectDeprecations
    ): void {
        foreach ($dataFields as $field) {
            $this->resolveValueForObject(
                $relationalTypeResolver,
                $id,
                $object,
                $field,
                $dbItems,
                $previousDBItems,
                $variables,
                $expressions,
                $engineIterationFeedbackStore,
                $objectErrors,
                $objectWarnings,
                $objectDeprecations
            );
        }
    }

    private function resolveValueForObject(
        RelationalTypeResolverInterface $relationalTypeResolver,
        string | int $id,
        object $object,
        string $field,
        array &$dbItems,
        array $previousDBItems,
        array &$variables,
        array &$expressions,
        EngineIterationFeedbackStore $engineIterationFeedbackStore,
        array &$objectErrors,
        array &$objectWarnings,
        array &$objectDeprecations
    ): void {
        // 1. Resolve the value against the TypeResolver
        $objectTypeFieldResolutionFeedbackStore = new ObjectTypeFieldResolutionFeedbackStore();
        $value = $relationalTypeResolver->resolveValue(
            $object,
            $field,
            $variables,
            $expressions,
            $objectTypeFieldResolutionFeedbackStore,
        );
        
        // 2. Transfer the feedback
        $engineIterationFeedbackStore->incorporate(
            $objectTypeFieldResolutionFeedbackStore,
            $relationalTypeResolver,
            $field,
            $id,
        );

        // 3. Add the output in the DB
        $fieldOutputKey = $this->getFieldQueryInterpreter()->getUniqueFieldOutputKey(
            $relationalTypeResolver,
            $field,
            $object,
        );
        if ($objectTypeFieldResolutionFeedbackStore->getErrors() !== []) {
            // For GraphQL, set the response for the failing field as null
            /** @var ComponentConfiguration */
            $componentConfiguration = App::getComponent(Component::class)->getConfiguration();
            if ($componentConfiguration->setFailingFieldResponseAsNull()) {
                $dbItems[(string)$id][$fieldOutputKey] = null;
            }
            return;
        }
        // If there is an alias, store the results under this. Otherwise, on the fieldName+fieldArgs
        $dbItems[(string)$id][$fieldOutputKey] = $value;
    }

    public function getDirectiveDescription(RelationalTypeResolverInterface $relationalTypeResolver): ?string
    {
        return $this->__('Resolve the value of the field and merge it into results. This directive is already included by the engine, since its execution is mandatory', 'component-model');
    }
}
