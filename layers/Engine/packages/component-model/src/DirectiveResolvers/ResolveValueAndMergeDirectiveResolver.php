<?php

declare(strict_types=1);

namespace PoP\ComponentModel\DirectiveResolvers;

use PoP\ComponentModel\Container\ServiceTags\MandatoryDirectiveServiceTagInterface;
use PoP\ComponentModel\Directives\DirectiveKinds;
use PoP\ComponentModel\Engine\EngineIterationFieldSet;
use PoP\ComponentModel\Feedback\EngineIterationFeedbackStore;
use PoP\ComponentModel\Feedback\ObjectResolutionFeedback;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\FeedbackItemProviders\ErrorFeedbackItemProvider;
use PoP\ComponentModel\Module;
use PoP\ComponentModel\ModuleConfiguration;
use PoP\ComponentModel\QueryResolution\FieldDataAccessProviderInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\PipelinePositions;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;
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
     * @param array<FieldDataAccessProviderInterface> $succeedingPipelineFieldDataAccessProviders
     * @param array<string,array<string|int,SplObjectStorage<FieldInterface,mixed>>> $previouslyResolvedIDFieldValues
     * @param array<string|int,SplObjectStorage<FieldInterface,mixed>> $resolvedIDFieldValues
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
            $fieldDataAccessProvider,
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
     * @param array<string|int,SplObjectStorage<FieldInterface,mixed>> $resolvedIDFieldValues
     */
    private function resolveValueForObjects(
        RelationalTypeResolverInterface $relationalTypeResolver,
        array $idObjects,
        array $idFieldSet,
        FieldDataAccessProviderInterface $fieldDataAccessProvider,
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
                $engineIterationFeedbackStore->objectFeedbackStore->addError(
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

            $expressions = $this->getExpressionsForObject($id, $variables, $messages);
            $this->resolveValuesForObject(
                $relationalTypeResolver,
                $id,
                $object,
                $idFieldSet[$id]->fields,
                $fieldDataAccessProvider,
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
    private function resolveValuesForObject(
        RelationalTypeResolverInterface $relationalTypeResolver,
        string | int $id,
        object $object,
        array $fieldSet,
        FieldDataAccessProviderInterface $fieldDataAccessProvider,
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
                $fieldDataAccessProvider,
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
     * @param array<string|int,SplObjectStorage<FieldInterface,mixed>> $resolvedIDFieldValues
     */
    private function resolveValueForObject(
        RelationalTypeResolverInterface $relationalTypeResolver,
        string | int $id,
        object $object,
        FieldInterface $field,
        FieldDataAccessProviderInterface $fieldDataAccessProvider,
        array &$resolvedIDFieldValues,
        array $previouslyResolvedIDFieldValues,
        array &$variables,
        array &$expressions,
        EngineIterationFeedbackStore $engineIterationFeedbackStore,
    ): void {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $setFailingFieldResponseAsNull = $moduleConfiguration->setFailingFieldResponseAsNull();
        if ($relationalTypeResolver instanceof UnionTypeResolverInterface) {
            /** @var UnionTypeResolverInterface */
            $unionTypeResolver = $relationalTypeResolver;
            $objectTypeResolver = $unionTypeResolver->getTargetObjectTypeResolver($object);
            if ($objectTypeResolver === null) {
                if ($setFailingFieldResponseAsNull) {
                    $resolvedIDFieldValues[$id][$field] = null;
                }
                return;
            }
        } else {
            /** @var ObjectTypeResolverInterface */
            $objectTypeResolver = $relationalTypeResolver;
        }

        // 1. Resolve the value against the TypeResolver
        $objectTypeFieldResolutionFeedbackStore = new ObjectTypeFieldResolutionFeedbackStore();
        $fieldData = $fieldDataAccessProvider->getFieldData(
            $field,
            $objectTypeResolver,
            $object,
        );
        if ($fieldData === null) {
            if ($setFailingFieldResponseAsNull) {
                $resolvedIDFieldValues[$id][$field] = null;
            }
            return;
        }
        $fieldDataAccessor = $objectTypeResolver->createFieldDataAccessor(
            $field,
            $fieldData,
        );
        $value = $relationalTypeResolver->resolveValue(
            $object,
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );

        // 2. Transfer the feedback
        $engineIterationFeedbackStore->objectFeedbackStore->incorporateFromObjectTypeFieldResolutionFeedbackStore(
            $objectTypeFieldResolutionFeedbackStore,
            $relationalTypeResolver,
            $this->directive,
            [$id => new EngineIterationFieldSet([$field])]
        );

        // 3. Add the output in the DB
        if ($objectTypeFieldResolutionFeedbackStore->getErrors() !== []) {
            // For GraphQL, set the response for the failing field as null
            if ($setFailingFieldResponseAsNull) {
                $resolvedIDFieldValues[$id][$field] = null;
            }
            return;
        }
        // If there is an alias, store the results under this. Otherwise, on the fieldName+fieldArgs
        $resolvedIDFieldValues[$id][$field] = $value;
    }

    public function getDirectiveDescription(RelationalTypeResolverInterface $relationalTypeResolver): ?string
    {
        return $this->__('Resolve the value of the field and merge it into results. This directive is already included by the engine, since its execution is mandatory', 'component-model');
    }
}
