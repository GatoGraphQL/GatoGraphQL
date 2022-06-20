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
use SplObjectStorage;

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
        return PipelinePositions::AFTER_RESOLVE_BEFORE_SERIALIZE;
    }

    /**
     * @param array<string|int,EngineIterationFieldSet> $idFieldSet
     * @param array<array<string|int,EngineIterationFieldSet>> $succeedingPipelineIDFieldSet
     * @param array<string,array<string|int,SplObjectStorage<FieldInterface,mixed>>> $previouslyResolvedIDFieldValues
     */
    public function resolveDirective(
        RelationalTypeResolverInterface $relationalTypeResolver,
        array $idFieldSet,
        array $succeedingPipelineDirectiveResolvers,
        array $idObjects,
        array $unionTypeOutputKeyIDs,
        array $previouslyResolvedIDFieldValues,
        array &$succeedingPipelineIDFieldSet,
        array &$resolvedIDFieldValues,
        array &$variables,
        array &$messages,
        EngineIterationFeedbackStore $engineIterationFeedbackStore,
    ): void {
        // Iterate data, extract into final results
        if (!$idObjects) {
            return;
        }
        $this->resolveValueForObjects(
            $relationalTypeResolver,
            $idObjects,
            $idFieldSet,
            $resolvedIDFieldValues,
            $previouslyResolvedIDFieldValues,
            $variables,
            $messages,
            $engineIterationFeedbackStore,
        );
    }

    /**
     * @param array<string|int,EngineIterationFieldSet> $idFieldSet
     * @param array<string,array<string|int,SplObjectStorage<FieldInterface,mixed>>> $previouslyResolvedIDFieldValues
     */
    private function resolveValueForObjects(
        RelationalTypeResolverInterface $relationalTypeResolver,
        array $idObjects,
        array $idFieldSet,
        array &$resolvedIDFieldValues,
        array $previouslyResolvedIDFieldValues,
        array &$variables,
        array &$messages,
        EngineIterationFeedbackStore $engineIterationFeedbackStore,
    ): void {
        /** @var array<string|int,EngineIterationFieldSet> */
        $enqueueFillingObjectsFromIDs = [];
        foreach ($idFieldSet as $id => $fieldSet) {
            // Obtain its ID and the required data-fields for that ID
            $object = $idObjects[$id];
            // It could be that the object is NULL. For instance: a post has a location stored a meta value, and the corresponding location object was deleted, so the ID is pointing to a non-existing object
            // In that case, simply return a dbError, and set the result as an empty array
            if ($object === null) {
                foreach ($fieldSet->fields as $field) {
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
                unset($resolvedIDFieldValues[$id]);
                continue;
            }

            $expressions = $this->getExpressionsForObject($id, $variables, $messages);
            $this->resolveValuesForObject(
                $relationalTypeResolver,
                $id,
                $object,
                $idFieldSet[$id]->fields,
                $resolvedIDFieldValues,
                $previouslyResolvedIDFieldValues,
                $variables,
                $expressions,
                $engineIterationFeedbackStore,
            );

            // Add the conditional data fields
            // If the conditionalFields are empty, we already reached the end of the tree. Nothing else to do
            foreach ($idFieldSet[$id]->conditionalFields as $conditionField) {
                /** @var FieldInterface $conditionField */
                $conditionalFields = $idFieldSet[$id]->conditionalFields[$conditionField];
                /** @var FieldInterface[] $conditionalFields */
                if ($conditionalFields === []) {
                    continue;
                }

                // Check if the condition field has value `true`
                // All 'conditional' fields must have their own key as 'direct', then simply look for this element on $resolvedIDFieldValues
                $conditionFieldOutputKey = $conditionField->getOutputKey();
                if (isset($resolvedIDFieldValues[$id]) && array_key_exists($conditionFieldOutputKey, $resolvedIDFieldValues[$id])) {
                    $conditionSatisfied = (bool)$resolvedIDFieldValues[$id][$conditionFieldOutputKey];
                } else {
                    $conditionSatisfied = false;
                }
                if (!$conditionSatisfied) {
                    continue;
                }
                $enqueueFillingObjectsFromIDs[$id] ??= new EngineIterationFieldSet([], $idFieldSet[$id]->conditionalFields);
                $enqueueFillingObjectsFromIDs[$id]->addFields($conditionalFields);
            }
        }
        // Enqueue items for the next iteration
        if ($enqueueFillingObjectsFromIDs !== []) {
            $relationalTypeResolver->enqueueFillingObjectsFromIDs($enqueueFillingObjectsFromIDs);
        }
    }

    /**
     * @param FieldInterface[] $fieldSet
     * @param array<string,array<string|int,SplObjectStorage<FieldInterface,mixed>>> $previouslyResolvedIDFieldValues
     */
    private function resolveValuesForObject(
        RelationalTypeResolverInterface $relationalTypeResolver,
        string | int $id,
        object $object,
        array $fieldSet,
        array &$resolvedIDFieldValues,
        array $previouslyResolvedIDFieldValues,
        array &$variables,
        array &$expressions,
        EngineIterationFeedbackStore $engineIterationFeedbackStore,
    ): void {
        foreach ($fieldSet as $field) {
            $this->resolveValueForObject(
                $relationalTypeResolver,
                $id,
                $object,
                $field,
                $resolvedIDFieldValues,
                $previouslyResolvedIDFieldValues,
                $variables,
                $expressions,
                $engineIterationFeedbackStore,
            );
        }
    }

    /**
     * @param array<string,array<string|int,SplObjectStorage<FieldInterface,mixed>>> $previouslyResolvedIDFieldValues
     */
    private function resolveValueForObject(
        RelationalTypeResolverInterface $relationalTypeResolver,
        string | int $id,
        object $object,
        FieldInterface $field,
        array &$resolvedIDFieldValues,
        array $previouslyResolvedIDFieldValues,
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
                $resolvedIDFieldValues[$id][$fieldOutputKey] = null;
            }
            return;
        }
        // If there is an alias, store the results under this. Otherwise, on the fieldName+fieldArgs
        $resolvedIDFieldValues[$id][$fieldOutputKey] = $value;
    }

    public function getDirectiveDescription(RelationalTypeResolverInterface $relationalTypeResolver): ?string
    {
        return $this->__('Resolve the value of the field and merge it into results. This directive is already included by the engine, since its execution is mandatory', 'component-model');
    }
}
