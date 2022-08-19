<?php

declare(strict_types=1);

namespace PoP\ComponentModel\DirectiveResolvers;

use PoP\ComponentModel\App;
use PoP\ComponentModel\Container\ServiceTags\MandatoryDirectiveServiceTagInterface;
use PoP\ComponentModel\Directives\DirectiveKinds;
use PoP\ComponentModel\Engine\EngineIterationFieldSet;
use PoP\ComponentModel\Feedback\EngineIterationFeedbackStore;
use PoP\ComponentModel\Feedback\ObjectResolutionFeedback;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\FeedbackItemProviders\ErrorFeedbackItemProvider;
use PoP\ComponentModel\QueryResolution\FieldDataAccessProviderInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\PipelinePositions;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;
use PoP\ComponentModel\TypeSerialization\TypeSerializationServiceInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\Root\Feedback\FeedbackItemResolution;
use SplObjectStorage;

final class ResolveValueAndMergeDirectiveResolver extends AbstractGlobalDirectiveResolver implements MandatoryDirectiveServiceTagInterface
{
    private ?TypeSerializationServiceInterface $typeSerializationService = null;

    final public function setTypeSerializationService(TypeSerializationServiceInterface $typeSerializationService): void
    {
        $this->typeSerializationService = $typeSerializationService;
    }
    final protected function getTypeSerializationService(): TypeSerializationServiceInterface
    {
        return $this->typeSerializationService ??= $this->instanceManager->getInstance(TypeSerializationServiceInterface::class);
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

    /**
     * @param array<string|int,EngineIterationFieldSet> $idFieldSet
     * @param array<array<string|int,EngineIterationFieldSet>> $succeedingPipelineIDFieldSet
     * @param array<FieldDataAccessProviderInterface> $succeedingPipelineFieldDataAccessProviders
     * @param array<string,array<string|int,SplObjectStorage<FieldInterface,mixed>>> $previouslyResolvedIDFieldValues
     * @param array<string|int,SplObjectStorage<FieldInterface,mixed>> $resolvedIDFieldValues
     * @param array<\PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface> $succeedingPipelineDirectiveResolvers
     * @param array<string|int,object> $idObjects
     * @param array<string,array<string|int,SplObjectStorage<FieldInterface,array<string|int>>>> $unionTypeOutputKeyIDs
     * @param array<string,mixed> $messages
     */
    public function resolveDirective(
        RelationalTypeResolverInterface $relationalTypeResolver,
        array $idFieldSet,
        FieldDataAccessProviderInterface $fieldDataAccessProvider,
        array $succeedingPipelineDirectiveResolvers,
        array $idObjects,
        array $unionTypeOutputKeyIDs,
        array $previouslyResolvedIDFieldValues,
        array &$succeedingPipelineIDFieldSet,
        array &$succeedingPipelineFieldDataAccessProviders,
        array &$resolvedIDFieldValues,
        array &$messages,
        EngineIterationFeedbackStore $engineIterationFeedbackStore,
    ): void {
        // Iterate data, extract into final results
        if ($idObjects === []) {
            return;
        }

        $this->resolveValueForObjects(
            $relationalTypeResolver,
            $idObjects,
            $idFieldSet,
            $fieldDataAccessProvider,
            $resolvedIDFieldValues,
            $previouslyResolvedIDFieldValues,
            $messages,
            $engineIterationFeedbackStore,
        );
    }

    /**
     * @param array<string|int,EngineIterationFieldSet> $idFieldSet
     * @param array<string,array<string|int,SplObjectStorage<FieldInterface,mixed>>> $previouslyResolvedIDFieldValues
     * @param array<string|int,SplObjectStorage<FieldInterface,mixed>> $resolvedIDFieldValues
     * @param array<string|int,object> $idObjects
     * @param array<string,mixed> $messages
     */
    protected function resolveValueForObjects(
        RelationalTypeResolverInterface $relationalTypeResolver,
        array $idObjects,
        array $idFieldSet,
        FieldDataAccessProviderInterface $fieldDataAccessProvider,
        array &$resolvedIDFieldValues,
        array $previouslyResolvedIDFieldValues,
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
                $engineIterationFeedbackStore->objectResolutionFeedbackStore->addError(
                    new ObjectResolutionFeedback(
                        new FeedbackItemResolution(
                            ErrorFeedbackItemProvider::class,
                            ErrorFeedbackItemProvider::E13,
                            [
                                $id,
                            ]
                        ),
                        $this->directive,
                        $relationalTypeResolver,
                        $this->directive,
                        [$id => $fieldSet]
                    )
                );
                // This is currently pointing to NULL and returning this entry in the database. Remove it
                // (this will also avoid errors in the Engine, which expects this result to be an array and can't be null)
                unset($resolvedIDFieldValues[$id]);
                continue;
            }

            $this->resolveValuesForObject(
                $relationalTypeResolver,
                $id,
                $object,
                $idFieldSet[$id]->fields,
                $fieldDataAccessProvider,
                $resolvedIDFieldValues,
                $previouslyResolvedIDFieldValues,
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
                if (isset($resolvedIDFieldValues[$id]) && $resolvedIDFieldValues[$id]->contains($conditionField)) {
                    $conditionSatisfied = (bool)$resolvedIDFieldValues[$id][$conditionField];
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
     * @param array<string|int,SplObjectStorage<FieldInterface,mixed>> $resolvedIDFieldValues
     */
    protected function resolveValuesForObject(
        RelationalTypeResolverInterface $relationalTypeResolver,
        string|int $id,
        object $object,
        array $fieldSet,
        FieldDataAccessProviderInterface $fieldDataAccessProvider,
        array &$resolvedIDFieldValues,
        array $previouslyResolvedIDFieldValues,
        EngineIterationFeedbackStore $engineIterationFeedbackStore,
    ): void {
        /**
         * Before resolving the fields for the current
         * object (executed in the Engine Iteration),
         * reset the AppState concerning FieldValuePromises.
         */
        $this->resetAppStateForFieldValuePromises();
        foreach ($fieldSet as $field) {
            $this->resolveValueForObject(
                $relationalTypeResolver,
                $id,
                $object,
                $field,
                $fieldDataAccessProvider,
                $resolvedIDFieldValues,
                $previouslyResolvedIDFieldValues,
                $engineIterationFeedbackStore,
            );
        }
    }

    /**
     * Reset the AppState concerning FieldValuePromises
     */
    protected function resetAppStateForFieldValuePromises(): void
    {
        /**
         * @var SplObjectStorage<FieldInterface,mixed>
         */
        $objectResolvedFieldValues = new SplObjectStorage();
        $appStateManager = App::getAppStateManager();
        $appStateManager->override('engine-iteration-object-resolved-field-values', $objectResolvedFieldValues);

        /**
         * Object-resolved dynamic variables are those generated on runtime
         * when resolving the GraphQL query (eg: via @export),
         * with a value targetted for a specific object
         *
         * @var SplObjectStorage<FieldInterface,array<string|int,array<string,mixed>>> SplObjectStorage<Field, [objectID => [dynamicVariableName => value]]>
         */
        $objectResolvedDynamicVariables = new SplObjectStorage();
        $appStateManager->override('object-resolved-dynamic-variables', $objectResolvedDynamicVariables);

        /**
         * The current objectID/field for which to retrieve the dynamic variable for.
         */
        $appStateManager->override('object-resolved-dynamic-variables-current-object-id', null);
        $appStateManager->override('object-resolved-dynamic-variables-current-field', null);
    }

    /**
     * Set the resolved value (null or otherwise) to the AppState
     * to resolve the FieldValuePromises.
     *
     * The value must be serialized,
     * so that Object types are converted to String to be used as inputs.
     */
    protected function setAppStateForFieldValuePromises(
        RelationalTypeResolverInterface $relationalTypeResolver,
        string|int $id,
        object $object,
        FieldInterface $field,
        mixed $value,
        EngineIterationFeedbackStore $engineIterationFeedbackStore,
    ): void {
        /**
         * Optimization: Check if the field was referenced in the query,
         * otherwise can skip
         */
        /** @var FieldInterface[] */
        $documentObjectResolvedFieldValueReferencedFields = App::getState('document-object-resolved-field-value-referenced-fields');
        if (!in_array($field, $documentObjectResolvedFieldValueReferencedFields)) {
            return;
        }

        if ($value === null) {
            return;
        }

        /** @var SplObjectStorage<FieldInterface,mixed> */
        $resolvedFieldValues = new SplObjectStorage();
        $resolvedFieldValues[$field] = $value;

        /** @var array<string|int,SplObjectStorage<FieldInterface,mixed>> */
        $resolvedIDFieldValues = array(
            $id => $resolvedFieldValues,
        );
        $serializedIDFieldValues = $this->getTypeSerializationService()->serializeOutputTypeIDFieldValues(
            $relationalTypeResolver,
            $resolvedIDFieldValues,
            [$id => new EngineIterationFieldSet([$field])],
            [$id => $object],
            $this->directive,
            $engineIterationFeedbackStore,
        );

        /**
         * If the value was not serialized, it will not be included in the response
         */
        if (!isset($serializedIDFieldValues[$id]) || !$serializedIDFieldValues[$id]->contains($field)) {
            return;
        }

        /**
         * @var SplObjectStorage<FieldInterface,mixed>
         */
        $objectResolvedFieldValues = App::getState('engine-iteration-object-resolved-field-values');
        $objectResolvedFieldValues[$field] = $serializedIDFieldValues[$id][$field];

        $appStateManager = App::getAppStateManager();
        $appStateManager->override('engine-iteration-object-resolved-field-values', $objectResolvedFieldValues);
    }

    /**
     * @param array<string,array<string|int,SplObjectStorage<FieldInterface,mixed>>> $previouslyResolvedIDFieldValues
     * @param array<string|int,SplObjectStorage<FieldInterface,mixed>> $resolvedIDFieldValues
     */
    protected function resolveValueForObject(
        RelationalTypeResolverInterface $relationalTypeResolver,
        string|int $id,
        object $object,
        FieldInterface $field,
        FieldDataAccessProviderInterface $fieldDataAccessProvider,
        array &$resolvedIDFieldValues,
        array $previouslyResolvedIDFieldValues,
        EngineIterationFeedbackStore $engineIterationFeedbackStore,
    ): void {
        if ($relationalTypeResolver instanceof UnionTypeResolverInterface) {
            /** @var UnionTypeResolverInterface */
            $unionTypeResolver = $relationalTypeResolver;
            $objectTypeResolver = $unionTypeResolver->getTargetObjectTypeResolver($object);
            if ($objectTypeResolver === null) {
                // Set the response for the failing field as null
                $resolvedIDFieldValues[$id][$field] = null;
                return;
            }
        } else {
            /** @var ObjectTypeResolverInterface */
            $objectTypeResolver = $relationalTypeResolver;
        }

        // 1. Resolve the value against the TypeResolver
        $objectTypeFieldResolutionFeedbackStore = new ObjectTypeFieldResolutionFeedbackStore();
        $fieldArgs = $fieldDataAccessProvider->getFieldArgs(
            $field,
            $objectTypeResolver,
            $object,
        );
        if ($fieldArgs === null) {
            // Set the response for the failing field as null
            $resolvedIDFieldValues[$id][$field] = null;
            $this->setAppStateForFieldValuePromises(
                $relationalTypeResolver,
                $id,
                $object,
                $field,
                null,
                $engineIterationFeedbackStore,
            );
            return;
        }
        $fieldDataAccessor = $objectTypeResolver->createFieldDataAccessor(
            $field,
            $fieldArgs,
        );
        $value = $relationalTypeResolver->resolveValue(
            $object,
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );

        // 2. Transfer the feedback
        $engineIterationFeedbackStore->objectResolutionFeedbackStore->incorporateFromObjectTypeFieldResolutionFeedbackStore(
            $objectTypeFieldResolutionFeedbackStore,
            $relationalTypeResolver,
            $this->directive,
            [$id => new EngineIterationFieldSet([$field])]
        );

        // 3. Add the output in the DB
        if ($objectTypeFieldResolutionFeedbackStore->getErrors() !== []) {
            // Set the response for the failing field as null
            $resolvedIDFieldValues[$id][$field] = null;
            $this->setAppStateForFieldValuePromises(
                $relationalTypeResolver,
                $id,
                $object,
                $field,
                null,
                $engineIterationFeedbackStore,
            );
            return;
        }
        // If there is an alias, store the results under this. Otherwise, on the fieldName+fieldArgs
        $resolvedIDFieldValues[$id][$field] = $value;
        $this->setAppStateForFieldValuePromises(
            $relationalTypeResolver,
            $id,
            $object,
            $field,
            $value,
            $engineIterationFeedbackStore,
        );
    }

    public function getDirectiveDescription(RelationalTypeResolverInterface $relationalTypeResolver): ?string
    {
        return $this->__('Resolve the value of the field and merge it into results. This directive is already included by the engine, since its execution is mandatory', 'component-model');
    }
}
