<?php

declare(strict_types=1);

namespace PoP\ComponentModel\DirectiveResolvers;

use PoP\ComponentModel\Container\ServiceTags\MandatoryDirectiveServiceTagInterface;
use PoP\ComponentModel\Directives\DirectiveKinds;
use PoP\ComponentModel\Engine\EngineIterationFieldSet;
use PoP\ComponentModel\Feedback\EngineIterationFeedbackStore;
use PoP\ComponentModel\Feedback\ObjectFeedback;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\FeedbackItemProviders\ErrorFeedbackItemProvider;
use PoP\ComponentModel\Module;
use PoP\ComponentModel\ModuleConfiguration;
use PoP\ComponentModel\TypeResolvers\PipelinePositions;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\GraphQLParser\StaticHelpers\LocationHelper;
use PoP\Root\App;
use PoP\Root\Feedback\FeedbackItemResolution;

final class ResolveValueAndMergeDirectiveResolver extends AbstractGlobalDirectiveResolver implements MandatoryDirectiveServiceTagInterface
{
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
        );
    }

    /**
     * @param array<string|int,EngineIterationFieldSet> $idsDataFields
     */
    private function resolveValueForObjects(
        RelationalTypeResolverInterface $relationalTypeResolver,
        array $objectIDItems,
        array $idsDataFields,
        array &$dbItems,
        array $previousDBItems,
        array &$variables,
        array &$messages,
        EngineIterationFeedbackStore $engineIterationFeedbackStore,
    ): void {
        /** @var array<string|int,EngineIterationFieldSet> */
        $enqueueFillingObjectsFromIDs = [];
        foreach ($idsDataFields as $id => $dataFields) {
            // Obtain its ID and the required data-fields for that ID
            $object = $objectIDItems[$id];
            // It could be that the object is NULL. For instance: a post has a location stored a meta value, and the corresponding location object was deleted, so the ID is pointing to a non-existing object
            // In that case, simply return a dbError, and set the result as an empty array
            if ($object === null) {
                foreach ($dataFields->direct as $field) {
                    $engineIterationFeedbackStore->objectFeedbackStore->addError(
                        new ObjectFeedback(
                            new FeedbackItemResolution(
                                ErrorFeedbackItemProvider::class,
                                ErrorFeedbackItemProvider::E13,
                                [
                                    $id,
                                ]
                            ),
                            LocationHelper::getNonSpecificLocation(),
                            $relationalTypeResolver,
                            $field,
                            $id,
                            $this->directive,
                        )
                    );
                }
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
                $idsDataFields[(string)$id]->direct,
                $dbItems,
                $previousDBItems,
                $variables,
                $expressions,
                $engineIterationFeedbackStore,
            );

            // Add the conditional data fields
            // If the conditionalDataFields are empty, we already reached the end of the tree. Nothing else to do
            foreach ($idsDataFields[$id]->conditional as $conditionDataField) {
                /** @var FieldInterface $conditionDataField */
                $conditionalDataFields = $idsDataFields[$id]->conditional[$conditionDataField];
                /** @var FieldInterface[] $conditionalDataFields */
                if ($conditionalDataFields === []) {
                    continue;
                }

                // Check if the condition field has value `true`
                // All 'conditional' fields must have their own key as 'direct', then simply look for this element on $dbItems
                $conditionFieldOutputKey = $conditionDataField->getOutputKey();
                if (isset($dbItems[$id]) && array_key_exists($conditionFieldOutputKey, $dbItems[$id])) {
                    $conditionSatisfied = (bool)$dbItems[$id][$conditionFieldOutputKey];
                } else {
                    $conditionSatisfied = false;
                }
                if (!$conditionSatisfied) {
                    continue;
                }
                $enqueueFillingObjectsFromIDs[(string)$id] ??= new EngineIterationFieldSet([], $idsDataFields[$id]->conditional);
                $enqueueFillingObjectsFromIDs[(string)$id]->addDirectFields($conditionalDataFields);
            }
        }
        // Enqueue items for the next iteration
        if ($enqueueFillingObjectsFromIDs !== []) {
            $relationalTypeResolver->enqueueFillingObjectsFromIDs($enqueueFillingObjectsFromIDs);
        }
    }

    /**
     * @param FieldInterface[] $dataFields
     */
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
            );
        }
    }

    private function resolveValueForObject(
        RelationalTypeResolverInterface $relationalTypeResolver,
        string | int $id,
        object $object,
        FieldInterface $field,
        array &$dbItems,
        array $previousDBItems,
        array &$variables,
        array &$expressions,
        EngineIterationFeedbackStore $engineIterationFeedbackStore,
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
        $engineIterationFeedbackStore->objectFeedbackStore->incorporateFromObjectTypeFieldResolutionFeedbackStore(
            $objectTypeFieldResolutionFeedbackStore,
            $relationalTypeResolver,
            $field,
            $id,
            $this->directive
        );

        // 3. Add the output in the DB
        $fieldOutputKey = $field->getOutputKey();
        if ($objectTypeFieldResolutionFeedbackStore->getErrors() !== []) {
            // For GraphQL, set the response for the failing field as null
            /** @var ModuleConfiguration */
            $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
            if ($moduleConfiguration->setFailingFieldResponseAsNull()) {
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
