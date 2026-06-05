<?php

declare(strict_types=1);

namespace PoPSchema\DirectiveCommons\DirectiveResolvers;

use PoPSchema\DirectiveCommons\Module;
use PoPSchema\DirectiveCommons\ModuleConfiguration;
use PoPSchema\DirectiveCommons\StateServices\ObjectResolvedDynamicVariablesServiceInterface;
use PoP\ComponentModel\App;
use PoP\ComponentModel\DirectivePipeline\DirectivePipelineServiceInterface;
use PoP\ComponentModel\DirectiveResolvers\AbstractGlobalMetaFieldDirectiveResolver;
use PoP\ComponentModel\DirectiveResolvers\DynamicVariableDefinerFieldDirectiveResolverInterface;
use PoP\ComponentModel\DirectiveResolvers\FieldDirectiveResolverInterface;
use PoP\ComponentModel\Engine\EngineIterationFieldSet;
use PoP\ComponentModel\FeedbackItemProviders\ErrorFeedbackItemProvider;
use PoP\ComponentModel\Feedback\EngineIterationFeedbackStore;
use PoP\ComponentModel\Feedback\FeedbackItemResolution;
use PoP\ComponentModel\Feedback\ObjectResolutionFeedback;
use PoP\ComponentModel\Feedback\SchemaFeedback;
use PoP\ComponentModel\Module as ComponentModelModule;
use PoP\ComponentModel\ModuleConfiguration as ComponentModelModuleConfiguration;
use PoP\ComponentModel\QueryResolution\FieldDataAccessProviderInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\StaticHelpers\MethodHelpers;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;
use PoP\Engine\FeedbackItemProviders\ErrorFeedbackItemProvider as EngineErrorFeedbackItemProvider;
use PoP\GraphQLParser\Spec\Parser\Ast\Argument;
use PoP\GraphQLParser\Spec\Parser\Ast\Directive;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\LeafField;
use PoP\GraphQLParser\Spec\Parser\Ast\RelationalField;
use PoP\GraphQLParser\Spec\Parser\RuntimeLocation;
use SplObjectStorage;
use stdClass;

abstract class AbstractApplyNestedDirectivesOnArrayOrObjectItemsFieldDirectiveResolver extends AbstractGlobalMetaFieldDirectiveResolver implements DynamicVariableDefinerFieldDirectiveResolverInterface
{
    /**
     * @var SplObjectStorage<FieldInterface,array<string,FieldInterface>>
     */
    protected SplObjectStorage $arrayItemFieldInstanceContainer;

    private ?DirectivePipelineServiceInterface $directivePipelineService = null;
    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;
    private ?ObjectResolvedDynamicVariablesServiceInterface $objectResolvedDynamicVariablesService = null;

    /**
     * Cache the iterator_to_array conversion of $nestedDirectivePipelineData,
     * keyed by directive identity so it is rebuilt only when the directive
     * bound to this resolver changes (i.e. on a new prepareDirective cycle).
     *
     * @var FieldDirectiveResolverInterface[]
     */
    private array $cachedNestedDirectiveResolvers = [];
    private ?Directive $cachedNestedDirectiveResolversForDirective = null;

    final protected function getDirectivePipelineService(): DirectivePipelineServiceInterface
    {
        if ($this->directivePipelineService === null) {
            /** @var DirectivePipelineServiceInterface */
            $directivePipelineService = $this->instanceManager->getInstance(DirectivePipelineServiceInterface::class);
            $this->directivePipelineService = $directivePipelineService;
        }
        return $this->directivePipelineService;
    }
    final protected function getStringScalarTypeResolver(): StringScalarTypeResolver
    {
        if ($this->stringScalarTypeResolver === null) {
            /** @var StringScalarTypeResolver */
            $stringScalarTypeResolver = $this->instanceManager->getInstance(StringScalarTypeResolver::class);
            $this->stringScalarTypeResolver = $stringScalarTypeResolver;
        }
        return $this->stringScalarTypeResolver;
    }
    final protected function getObjectResolvedDynamicVariablesService(): ObjectResolvedDynamicVariablesServiceInterface
    {
        if ($this->objectResolvedDynamicVariablesService === null) {
            /** @var ObjectResolvedDynamicVariablesServiceInterface */
            $objectResolvedDynamicVariablesService = $this->instanceManager->getInstance(ObjectResolvedDynamicVariablesServiceInterface::class);
            $this->objectResolvedDynamicVariablesService = $objectResolvedDynamicVariablesService;
        }
        return $this->objectResolvedDynamicVariablesService;
    }

    public function __construct()
    {
        parent::__construct();
        $this->arrayItemFieldInstanceContainer = new SplObjectStorage();
    }

    /**
     * @return array<string,InputTypeResolverInterface>
     */
    public function getDirectiveArgNameTypeResolvers(RelationalTypeResolverInterface $relationalTypeResolver): array
    {
        return array_merge(
            parent::getDirectiveArgNameTypeResolvers($relationalTypeResolver),
            [
                $this->getPassValueOnwardsAsVariableArgumentName() => $this->getStringScalarTypeResolver(),
            ],
            $this->passKeyOnwardsAsVariable()
                ? [
                    $this->getPassKeyOnwardsAsVariableArgumentName() => $this->getStringScalarTypeResolver(),
                ]
                : []
        );
    }

    abstract protected function passKeyOnwardsAsVariable(): bool;

    public function getDirectiveArgDescription(RelationalTypeResolverInterface $relationalTypeResolver, string $directiveArgName): ?string
    {
        return match ($directiveArgName) {
            $this->getPassValueOnwardsAsVariableArgumentName() => $this->__('The name of the dynamic variable under which to export the array element value from the current iteration', 'gatographql'),
            $this->getPassKeyOnwardsAsVariableArgumentName() => $this->__('The name of the dynamic variable under which to export the array element key from the current iteration', 'gatographql'),
            default => parent::getDirectiveArgDescription($relationalTypeResolver, $directiveArgName),
        };
    }

    /**
     * Execute directive <transformProperty> to each of the elements on the affected field, which must be an array
     * This is achieved by executing the following logic:
     * 1. Unpack the elements of the array into a temporary property for each, in the current object
     * 2. Execute <transformProperty> on each property
     * 3. Pack into the array, once again, and remove all temporary properties
     *
     * @param array<string|int,EngineIterationFieldSet> $idFieldSet
     * @param array<array<string|int,EngineIterationFieldSet>> $succeedingPipelineIDFieldSet
     * @param array<FieldDataAccessProviderInterface> $succeedingPipelineFieldDataAccessProviders
     * @param array<string,array<string|int,SplObjectStorage<FieldInterface,mixed>>> $previouslyResolvedIDFieldValues
     * @param array<string|int,SplObjectStorage<FieldInterface,mixed>> $resolvedIDFieldValues
     * @param array<FieldDirectiveResolverInterface> $succeedingPipelineFieldDirectiveResolvers
     * @param array<string|int,object> $idObjects
     * @param array<string,array<string|int,SplObjectStorage<FieldInterface,array<string|int>>>> $unionTypeOutputKeyIDs
     * @param array<string,mixed> $messages
     */
    public function resolveDirective(
        RelationalTypeResolverInterface $relationalTypeResolver,
        array $idFieldSet,
        FieldDataAccessProviderInterface $fieldDataAccessProvider,
        array $succeedingPipelineFieldDirectiveResolvers,
        array $idObjects,
        array $unionTypeOutputKeyIDs,
        array $previouslyResolvedIDFieldValues,
        array &$succeedingPipelineIDFieldSet,
        array &$succeedingPipelineFieldDataAccessProviders,
        array &$resolvedIDFieldValues,
        array &$messages,
        EngineIterationFeedbackStore $engineIterationFeedbackStore,
    ): void {
        /** @var ComponentModelModuleConfiguration */
        $moduleConfiguration = App::getModule(ComponentModelModule::class)->getConfiguration();
        $setFieldAsNullIfDirectiveFailed = $moduleConfiguration->setFieldAsNullIfDirectiveFailed();

        /**
         * Reset the arrayItemField instance cache per resolveDirective
         * call, so it doesn't accumulate entries across requests when
         * the resolver is held as a singleton (e.g. in long-running PHP
         * workers like Swoole/RoadRunner). The cache only needs to span
         * step 1 below — step 3 reads the captured map instead.
         */
        $this->arrayItemFieldInstanceContainer = new SplObjectStorage();

        /**
         * Collect all ID => dataFields for the arrayItems
         *
         * @var array<string|int,EngineIterationFieldSet>
         */
        $arrayItemIDsProperties = [];
        /**
         * Capture the (arrayItemField, key) pairs produced in step 1,
         * grouped by (id, parent field), so step 3 can iterate them
         * directly instead of re-validating and re-running getArrayItems.
         *
         * @var array<string|int,SplObjectStorage<FieldInterface,list<array{0:FieldInterface,1:int|string}>>>
         */
        $arrayItemEntriesByIDField = [];
        $typeOutputKey = $relationalTypeResolver->getTypeOutputKey();

        /**
         * Execute composed directive only if the validations do not fail
         */
        $execute = false;

        /**
         * Append fieldArgs for the array item fields.
         *
         * Lazy: only clone on the first ID with non-empty array items.
         * If validation drops every ID before then, the clone is skipped.
         */
        $nestedFieldDataAccessProvider = null;

        /**
         * Argument "if" can receive a Promise
         */
        $appStateManager = App::getAppStateManager();
        $resolveDirectiveArgsOnObject = $this->directive->hasArgumentReferencingResolvedOnObjectPromise();

        $passValueOnwardsAsVariableArgumentName = $this->getPassValueOnwardsAsVariableArgumentName();
        $passKeyOnwardsAsVariableArgumentName = $this->getPassKeyOnwardsAsVariableArgumentName();

        if (!$resolveDirectiveArgsOnObject) {
            $directiveArgs = $this->getResolvedDirectiveArgs(
                $relationalTypeResolver,
                $idFieldSet,
                $engineIterationFeedbackStore,
            );
            if ($directiveArgs === null) {
                if ($setFieldAsNullIfDirectiveFailed) {
                    $this->removeIDFieldSet(
                        $succeedingPipelineIDFieldSet,
                        $idFieldSet,
                    );
                    $this->setFieldResponseValueAsNull(
                        $resolvedIDFieldValues,
                        $idFieldSet,
                    );
                }
                return;
            }
            $passValueOnwardsAs = $directiveArgs[$passValueOnwardsAsVariableArgumentName] ?? null;
            $passKeyOnwardsAs = $directiveArgs[$passKeyOnwardsAsVariableArgumentName] ?? null;
        }

        $valueArgument = $this->directive->getArgument($passValueOnwardsAsVariableArgumentName);
        $keyArgument = $this->directive->getArgument($passKeyOnwardsAsVariableArgumentName);

        $isUnionTypeResolver = $relationalTypeResolver instanceof UnionTypeResolverInterface;
        $decreaseFieldTypeModifiersCardinalityForSerialization = $this->decreaseFieldTypeModifiersCardinalityForSerialization();
        /** @var SplObjectStorage<FieldInterface,int|null> */
        $fieldTypeModifiersByField = App::getState('field-type-modifiers-for-serialization');
        /**
         * The clone is only needed to restore state at the end (see below),
         * which only happens when decreasing the cardinality.
         */
        $originalFieldTypeModifiersByField = $decreaseFieldTypeModifiersCardinalityForSerialization
            ? clone $fieldTypeModifiersByField
            : null;

        /** @var SplObjectStorage<FieldInterface,int|null> */
        $currentFieldTypeModifiersByField = new SplObjectStorage();

        $objectResolvedDynamicVariablesService = $this->getObjectResolvedDynamicVariablesService();

        /**
         * 1. Unpack all elements of the array into a property for each
         */
        foreach ($idFieldSet as $id => $fieldSet) {
            $object = $idObjects[$id];
            foreach ($fieldSet->fields as $field) {
                // Validate that the property exists
                $hasIDFieldBeenResolved = isset($resolvedIDFieldValues[$id]) && $resolvedIDFieldValues[$id]->offsetExists($field);
                if (!$hasIDFieldBeenResolved && !(isset($previouslyResolvedIDFieldValues[$typeOutputKey][$id]) && $previouslyResolvedIDFieldValues[$typeOutputKey][$id]->offsetExists($field))) {
                    $this->processObjectFailure(
                        $relationalTypeResolver,
                        new FeedbackItemResolution(
                            EngineErrorFeedbackItemProvider::class,
                            EngineErrorFeedbackItemProvider::E2,
                            [
                                $field->getOutputKey(),
                                $id
                            ]
                        ),
                        [$id => new EngineIterationFieldSet([$field])],
                        $succeedingPipelineIDFieldSet,
                        $this->directive,
                        $resolvedIDFieldValues,
                        $engineIterationFeedbackStore,
                    );
                    continue;
                }

                $value = $hasIDFieldBeenResolved ?
                    $resolvedIDFieldValues[$id][$field] :
                    $previouslyResolvedIDFieldValues[$typeOutputKey][$id][$field];

                // If the array is null or empty, nothing to do
                if (!$value) {
                    continue;
                }

                // Validate that the value is an array or stdClass
                if (!$this->validateInputValueType($value)) {
                    $this->processObjectFailure(
                        $relationalTypeResolver,
                        $this->getItemValueValidationFailedFeedbackItemResolution($field, $id),
                        [$id => new EngineIterationFieldSet([$field])],
                        $succeedingPipelineIDFieldSet,
                        $this->directive,
                        $resolvedIDFieldValues,
                        $engineIterationFeedbackStore,
                    );
                    continue;
                }

                if ($resolveDirectiveArgsOnObject) {
                    $directiveArgs = $this->getResolvedDirectiveArgsForObjectAndField(
                        $relationalTypeResolver,
                        $field,
                        $id,
                        $engineIterationFeedbackStore,
                    );
                    if ($directiveArgs === null) {
                        if ($setFieldAsNullIfDirectiveFailed) {
                            $this->removeIDFieldSet(
                                $succeedingPipelineIDFieldSet,
                                [$id => new EngineIterationFieldSet([$field])],
                            );
                            $this->setFieldResponseValueAsNull(
                                $resolvedIDFieldValues,
                                [$id => new EngineIterationFieldSet([$field])],
                            );
                        }
                        continue;
                    }
                    $passValueOnwardsAs = $directiveArgs[$passValueOnwardsAsVariableArgumentName] ?? null;
                    $passKeyOnwardsAs = $directiveArgs[$passKeyOnwardsAsVariableArgumentName] ?? null;
                }

                // The value is an array or an stdClass. Unpack all the elements into their own property
                $arrayOrObject = $value;
                $arrayItems = $this->getArrayItems(
                    $arrayOrObject,
                    $object,
                    $id,
                    $field,
                    $relationalTypeResolver,
                    $idObjects,
                    $previouslyResolvedIDFieldValues,
                    $succeedingPipelineIDFieldSet,
                    $resolvedIDFieldValues,
                    $messages,
                    $engineIterationFeedbackStore,
                );
                if ($arrayItems !== []) {
                    $execute = true;
                    $nestedFieldDataAccessProvider ??= clone $fieldDataAccessProvider;

                    if (
                        $decreaseFieldTypeModifiersCardinalityForSerialization
                        // Execute only once per field (i.e. avoid recalculating for different objects)
                        && !$currentFieldTypeModifiersByField->offsetExists($field)
                    ) {
                        $targetObjectTypeResolver = null;
                        if ($isUnionTypeResolver) {
                            /** @var UnionTypeResolverInterface */
                            $unionTypeResolver = $relationalTypeResolver;
                            $targetObjectTypeResolver = $unionTypeResolver->getTargetObjectTypeResolver($object);
                        } else {
                            $targetObjectTypeResolver = $relationalTypeResolver;
                        }
                        /** @var ObjectTypeResolverInterface $targetObjectTypeResolver */

                        $currentFieldTypeModifiersByField[$field] = $this->decreaseFieldTypeModifiersForSerializationInAppState(
                            $fieldTypeModifiersByField,
                            $targetObjectTypeResolver,
                            $field,
                        );
                        /**
                         * No `override(...)` call needed here:
                         * `$fieldTypeModifiersByField` IS the SplObjectStorage
                         * already stored in AppState (retrieved by reference
                         * above), so the in-place mutation is already visible.
                         */
                    }

                    $arrayItemIDsProperties[$id] ??= new EngineIterationFieldSet();
                    /**
                     * Hoist the parent-field's modifiers lookup out of the
                     * per-item loop — the value is constant for this $field.
                     */
                    $hasParentFieldTypeModifiers = isset($fieldTypeModifiersByField[$field]);
                    $parentFieldTypeModifiers = $hasParentFieldTypeModifiers
                        ? $fieldTypeModifiersByField[$field]
                        : null;
                    $passKeyOnwardsAsVariable = $this->passKeyOnwardsAsVariable();
                    /**
                     * Collected per parent (id, $field): list of
                     * [arrayItemField, key] pairs. Reused below to:
                     *  - batch the parent-to-arrayItem dynamic-variable copy.
                     *  - drive step 3 directly, without redoing
                     *    validateInputValueType / getArrayItems / getArrayItemField.
                     *
                     * @var list<array{0:FieldInterface,1:int|string}>
                     */
                    $arrayItemEntriesForField = [];
                    /**
                     * `getOutputKey()` is constant for the duration of
                     * this loop — read once.
                     */
                    $fieldOutputKey = $field->getOutputKey();
                    foreach ($arrayItems as $key => &$value) {
                        /**
                         * Add into the $idFieldSet object for the array items.
                         *
                         * Watch out: function `regenerateAndExecuteFunction`
                         * receives `$idFieldSet` and not `$idsFieldSetOutputKeys`,
                         * so then re-create the "field" assigning a new alias.
                         * If it has an alias, use it. If not, use the fieldName
                         */
                        $arrayItemAlias = $this->createPropertyForArrayItem($fieldOutputKey, (string) $key);
                        $arrayItemField = $this->getArrayItemField($field, $arrayItemAlias);
                        $nestedFieldDataAccessProvider->duplicateFieldData($field, $arrayItemField);
                        // Place into the current object
                        $resolvedIDFieldValues[$id][$arrayItemField] = $value;
                        // Place it into list of fields to process
                        $arrayItemIDsProperties[$id]->fields[] = $arrayItemField;
                        $arrayItemEntriesForField[] = [$arrayItemField, $key];

                        /**
                         * Indicate the cardinality for the array item.
                         *
                         * Important: do it ALWAYS, and not only when
                         * $decreaseFieldTypeModifiersCardinalityForSerialization is true.
                         * That's because @underJSONObjectProperty does not decrease the
                         * cardinality, but the iterated elements must also receive the
                         * fieldTypeModifiers from the level above, as in this query:
                         *
                         *   {
                         *     post(by: { id: 19 }) {
                         *       blockDataItems
                         *         @underEachArrayItem
                         *           @underJSONObjectProperty(by: { path: "attributes.content" })
                         *             @export(as:"original")
                         *     }
                         *   }
                         */
                        if ($hasParentFieldTypeModifiers) {
                            $fieldTypeModifiersByField[$arrayItemField] = $parentFieldTypeModifiers;
                            /**
                             * No `override(...)` call needed: the SplObjectStorage
                             * is the same instance already stored in AppState.
                             */
                        }

                        // Export the array item value into the dynamic variable
                        if (!empty($passValueOnwardsAs)) {
                            /** @var Argument $valueArgument */
                            $objectResolvedDynamicVariablesService->setObjectResolvedDynamicVariableInAppState(
                                $relationalTypeResolver,
                                $arrayItemField,
                                $object,
                                $id,
                                $value,
                                true,
                                $passValueOnwardsAs,
                                [$arrayItemField],
                                $valueArgument,
                                $this->directive,
                                $engineIterationFeedbackStore,
                            );
                        }
                        if ($passKeyOnwardsAsVariable && !empty($passKeyOnwardsAs)) {
                            /** @var Argument $keyArgument */
                            $objectResolvedDynamicVariablesService->setObjectResolvedDynamicVariableInAppState(
                                $relationalTypeResolver,
                                $arrayItemField,
                                $object,
                                $id,
                                $key,
                                false, // For the key, do NOT serialize the value! Because the fieldTypeModifiers apply to the value, not to the key
                                $passKeyOnwardsAs,
                                [$arrayItemField],
                                $keyArgument,
                                $this->directive,
                                $engineIterationFeedbackStore,
                            );
                        }
                    }
                    unset($value);
                    /**
                     * Allow the Field created by @underJSONObjectProperty
                     * to read the state defined at that previous level.
                     *
                     * Batched: $field's state is constant across the loop
                     * above, so do the AppState lookup + source `contains`
                     * check once, then assign to all arrayItemFields.
                     */
                    $objectResolvedDynamicVariablesService->copyObjectResolvedDynamicVariablesFromFieldToFieldsInAppState(
                        $field,
                        array_column($arrayItemEntriesForField, 0),
                    );
                    $arrayItemEntriesByIDField[$id] ??= new SplObjectStorage();
                    $arrayItemEntriesByIDField[$id][$field] = $arrayItemEntriesForField;
                }
            }
        }

        /**
         * No `override(...)` call needed here:
         * `$fieldTypeModifiersByField` IS the SplObjectStorage instance
         * already stored in AppState, and was mutated in place by the
         * inner loop above.
         */

        if ($execute) {
            /**
             * `$execute = true` implies the inner loop above ran for
             * at least one (id, field) with non-empty arrayItems, which
             * triggered the lazy clone of $nestedFieldDataAccessProvider.
             */
            assert($nestedFieldDataAccessProvider !== null);
            // Build the directive pipeline
            /** @var FieldDirectiveResolverInterface[] */
            $nestedDirectiveResolvers = $this->getCachedNestedDirectiveResolvers();
            $nestedDirectivePipeline = $this->getDirectivePipelineService()->getDirectivePipeline($nestedDirectiveResolvers);
            // Fill the idFieldSet for each directive in the pipeline
            /** @var array<array<string|int,EngineIterationFieldSet>> */
            $pipelineArrayItemIDsProperties = [];
            $nestedFieldDataAccessProviders = [];
            for ($i = 0; $i < count($nestedDirectiveResolvers); $i++) {
                $pipelineArrayItemIDsProperties[] = $arrayItemIDsProperties;
                $nestedFieldDataAccessProviders[] = $nestedFieldDataAccessProvider;
            }
            // 2. Execute the composed directive pipeline on all arrayItems
            $separateEngineIterationFeedbackStore = new EngineIterationFeedbackStore();
            $nestedDirectivePipeline->resolveDirectivePipeline(
                $relationalTypeResolver,
                $pipelineArrayItemIDsProperties, // Here we pass the properties to the array elements!
                $nestedFieldDataAccessProviders,
                $nestedDirectiveResolvers,
                $idObjects,
                $unionTypeOutputKeyIDs,
                $previouslyResolvedIDFieldValues,
                $resolvedIDFieldValues,
                $messages,
                $separateEngineIterationFeedbackStore,
            );

            $objectResolutionFeedbackStoreErrors = $separateEngineIterationFeedbackStore->objectResolutionFeedbackStore->getErrors();
            $schemaFeedbackStoreErrors = $separateEngineIterationFeedbackStore->schemaFeedbackStore->getErrors();

            /**
             * If bubbling up the errors for the meta directives, the errors
             * will be handled below.
             *
             * Otherwise, already incorporate the errors.
             *
             * @var ModuleConfiguration
             */
            $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
            $nestErrorsInMetaDirectives = $moduleConfiguration->nestErrorsInMetaDirectives();
            if ($nestErrorsInMetaDirectives) {
                $separateEngineIterationFeedbackStore->objectResolutionFeedbackStore->setErrors([]);
                $separateEngineIterationFeedbackStore->schemaFeedbackStore->setErrors([]);
            }
            $engineIterationFeedbackStore->incorporate($separateEngineIterationFeedbackStore);

            /**
             * Restore 'field-type-modifiers-for-serialization' to the previous state
             */
            if ($decreaseFieldTypeModifiersCardinalityForSerialization) {
                $appStateManager->override('field-type-modifiers-for-serialization', $originalFieldTypeModifiersByField);
            }

            // If any item fails, set the whole response field as null
            if (
                $nestErrorsInMetaDirectives
                && ($objectResolutionFeedbackStoreErrors !== [] || $schemaFeedbackStoreErrors !== [])
            ) {
                // // Transfer the error to the composable directive
                if ($schemaFeedbackStoreErrors !== []) {
                    $fields = MethodHelpers::getFieldsFromIDFieldSet($idFieldSet);
                    $engineIterationFeedbackStore->schemaFeedbackStore->addError(
                        new SchemaFeedback(
                            new FeedbackItemResolution(
                                ErrorFeedbackItemProvider::class,
                                ErrorFeedbackItemProvider::E18,
                                [
                                    $this->directive->asQueryString(),
                                ],
                                $schemaFeedbackStoreErrors
                            ),
                            $this->directive,
                            $relationalTypeResolver,
                            $fields,
                        )
                    );
                }

                if ($objectResolutionFeedbackStoreErrors !== []) {
                    $engineIterationFeedbackStore->objectResolutionFeedbackStore->addError(
                        new ObjectResolutionFeedback(
                            new FeedbackItemResolution(
                                ErrorFeedbackItemProvider::class,
                                ErrorFeedbackItemProvider::E18,
                                [
                                    $this->directive->asQueryString(),
                                ],
                                $objectResolutionFeedbackStoreErrors
                            ),
                            $this->directive,
                            $relationalTypeResolver,
                            $this->directive,
                            $idFieldSet
                        )
                    );
                }

                if ($setFieldAsNullIfDirectiveFailed) {
                    $this->removeIDFieldSet(
                        $succeedingPipelineIDFieldSet,
                        $idFieldSet,
                    );
                    $this->setFieldResponseValueAsNull(
                        $resolvedIDFieldValues,
                        $idFieldSet
                    );
                }
                return;
            }

            /**
             * 3. Compose the array from the results for each array item.
             *
             * Drive this from the (id, field) → [arrayItemField, key]
             * map captured during step 1, so we don't redo
             * validateInputValueType / getArrayItems / getArrayItemField.
             */
            foreach ($arrayItemEntriesByIDField as $id => $fieldEntries) {
                /** @var FieldInterface $field */
                foreach ($fieldEntries as $field) {
                    if ($resolveDirectiveArgsOnObject) {
                        $this->loadObjectResolvedDynamicVariablesInAppState($field, $id);
                        $this->directiveDataAccessor->resetDirectiveArgs();
                    }
                    $entries = $fieldEntries[$field];
                    /**
                     * Collect [key, processedValue] pairs and detach the
                     * temporary arrayItemField slots; then a single
                     * batched call composes all items back, doing one
                     * read+write of the (id, field) container instead
                     * of N.
                     *
                     * @var list<array{0:int|string,1:mixed}>
                     */
                    $arrayItemKeyValues = [];
                    foreach ($entries as [$arrayItemField, $key]) {
                        // Place the result of executing the function on the array item
                        $arrayItemKeyValues[] = [$key, $resolvedIDFieldValues[$id][$arrayItemField]];
                        // Remove this temporary property from $resolvedIDFieldValues
                        $resolvedIDFieldValues[$id]->offsetUnset($arrayItemField);
                    }
                    // Place the results for the array in the original property
                    $this->addProcessedItemsBackToResolvedIDFieldValues(
                        $relationalTypeResolver,
                        $resolvedIDFieldValues,
                        $engineIterationFeedbackStore,
                        $id,
                        $field,
                        $arrayItemKeyValues,
                    );
                }
            }
        }

        /**
         * Reset the AppState
         */
        if ($resolveDirectiveArgsOnObject) {
            $this->resetObjectResolvedDynamicVariablesInAppState();
            $this->directiveDataAccessor->resetDirectiveArgs();
        }
    }

    /**
     * Validate that the value is an array or stdClass.
     * Can override for more-specific validation.
     */
    protected function validateInputValueType(mixed $value): bool
    {
        return is_array($value) || ($value instanceof stdClass);
    }

    /**
     * Cache `iterator_to_array($this->nestedDirectivePipelineData)`,
     * keyed by the currently-bound directive instance so the cache is
     * naturally invalidated when `prepareDirective` rebinds the resolver
     * to a different directive.
     *
     * @return FieldDirectiveResolverInterface[]
     */
    private function getCachedNestedDirectiveResolvers(): array
    {
        if ($this->cachedNestedDirectiveResolversForDirective !== $this->directive) {
            $nestedDirectiveResolvers = [];
            /** @var FieldDirectiveResolverInterface $nestedDirectiveResolver */
            foreach ($this->nestedDirectivePipelineData as $nestedDirectiveResolver) {
                $nestedDirectiveResolvers[] = $nestedDirectiveResolver;
            }
            $this->cachedNestedDirectiveResolvers = $nestedDirectiveResolvers;
            $this->cachedNestedDirectiveResolversForDirective = $this->directive;
        }
        return $this->cachedNestedDirectiveResolvers;
    }

    /**
     * For Serialization: Force the modifiers for "IsArrayOfArrays"
     * and "IsArray", because the serialization for @underEachArrayItem
     * will decrease on 1 level the cardinality of the value,
     * not corresponding anymore with that one from the type
     * in the field.
     *
     * For instance, after applying @underEachArrayItem, the cardinality
     * of the type modifiers must be handled like this:
     *
     * - [[String]] => [String]
     * - [String] => String
     * - String => ShouldNotHappenException!?
     *
     * If there's already a 'field-type-modifiers-for-serialization' use
     * that one, as it could come from a previous @underEachArrayItem.
     *
     * Then return the previous state, so it can be restored
     * after executing the serialization.
     *
     * @param SplObjectStorage<FieldInterface,int|null> $fieldTypeModifiersByField
     */
    protected function decreaseFieldTypeModifiersForSerializationInAppState(
        SplObjectStorage $fieldTypeModifiersByField,
        ObjectTypeResolverInterface $targetObjectTypeResolver,
        FieldInterface $field,
    ): ?int {
        /** @var int|null */
        $currentFieldTypeModifiers = $fieldTypeModifiersByField[$field] ?? null;
        if ($currentFieldTypeModifiers === null) {
            $fieldTypeModifiers = $targetObjectTypeResolver->getFieldTypeModifiers($field);
        } else {
            $fieldTypeModifiers = $currentFieldTypeModifiers;
        }
        /** @var int $fieldTypeModifiers */

        /**
         * Decrease one level the cardinality of the type modifiers:
         *
         * - [[String]] => [String]
         * - [String] => String
         */
        $fieldLeafOutputTypeIsArrayOfArrays = ($fieldTypeModifiers & SchemaTypeModifiers::IS_ARRAY_OF_ARRAYS) === SchemaTypeModifiers::IS_ARRAY_OF_ARRAYS;
        $fieldLeafOutputTypeIsArray = ($fieldTypeModifiers & SchemaTypeModifiers::IS_ARRAY) === SchemaTypeModifiers::IS_ARRAY;
        if ($fieldLeafOutputTypeIsArrayOfArrays) {
            $fieldTypeModifiers = ($fieldTypeModifiers & ~SchemaTypeModifiers::IS_ARRAY_OF_ARRAYS) | SchemaTypeModifiers::IS_ARRAY;
        } elseif ($fieldLeafOutputTypeIsArray) {
            $fieldTypeModifiers &= ~SchemaTypeModifiers::IS_ARRAY;
        } else {
            /**
             * How could 3 @underEachArrayItem be nested, when the GraphQL Server
             * currently only supports 2 levels of nested arrays
             * (i.e.: [[String]], but not [[[String]]])?
             *
             * @todo Consider if to throw ShouldNotHappenException
             */
        }

        $fieldTypeModifiersByField[$field] = $fieldTypeModifiers;

        return $currentFieldTypeModifiers;
    }

    /**
     * Indicate if the directive will decrease the
     * field type modifiers, such as [[String]] => [String].
     *
     * - @underEachArrayItem: decrease
     * - @underArrayItem: decrease
     * - @underJSONObject(Nested)Property: do not decrease
     */
    abstract protected function decreaseFieldTypeModifiersCardinalityForSerialization(): bool;

    protected function getItemValueValidationFailedFeedbackItemResolution(
        FieldInterface $field,
        string|int $id,
    ): FeedbackItemResolution {
        return new FeedbackItemResolution(
            EngineErrorFeedbackItemProvider::class,
            EngineErrorFeedbackItemProvider::E4,
        );
    }

    protected function getArrayItemField(
        FieldInterface $field,
        string $arrayItemAlias,
    ): FieldInterface {
        if (!$this->arrayItemFieldInstanceContainer->offsetExists($field) || !isset($this->arrayItemFieldInstanceContainer[$field][$arrayItemAlias])) {
            $arrayItemFieldInstanceContainerSplObjectStorage = $this->arrayItemFieldInstanceContainer[$field] ?? [];
            $arrayItemFieldInstanceContainerSplObjectStorage[$arrayItemAlias] = ($field instanceof RelationalField)
                ? new RelationalField(
                    $field->getName(),
                    $arrayItemAlias,
                    $field->getArguments(),
                    $field->getFieldsOrFragmentBonds(),
                    $field->getDirectives(),
                    new RuntimeLocation($field),
                )
                : new LeafField(
                    $field->getName(),
                    $arrayItemAlias,
                    $field->getArguments(),
                    $field->getDirectives(),
                    new RuntimeLocation($field),
                );
            $this->arrayItemFieldInstanceContainer[$field] = $arrayItemFieldInstanceContainerSplObjectStorage;
        }
        return $this->arrayItemFieldInstanceContainer[$field][$arrayItemAlias];
    }

    /**
     * @param array<string,array<string|int,SplObjectStorage<FieldInterface,mixed>>> $previouslyResolvedIDFieldValues
     * @param array<array<string|int,EngineIterationFieldSet>> $succeedingPipelineIDFieldSet
     * @param array<string|int,SplObjectStorage<FieldInterface,mixed>> $resolvedIDFieldValues
     * @return mixed[]
     * @param mixed[]|stdClass $arrayOrObject
     * @param array<string|int,object> $idObjects
     * @param array<string,mixed> $messages
     */
    final protected function getArrayItems(
        array|stdClass &$arrayOrObject,
        object $object,
        int|string $id,
        FieldInterface $field,
        RelationalTypeResolverInterface $relationalTypeResolver,
        array $idObjects,
        array $previouslyResolvedIDFieldValues,
        array &$succeedingPipelineIDFieldSet,
        array &$resolvedIDFieldValues,
        array &$messages,
        EngineIterationFeedbackStore $engineIterationFeedbackStore,
    ): array {
        $arrayItems = $this->doGetArrayItems(
            $arrayOrObject,
            $id,
            $field,
            $relationalTypeResolver,
            $idObjects,
            $previouslyResolvedIDFieldValues,
            $succeedingPipelineIDFieldSet,
            $resolvedIDFieldValues,
            $messages,
            $engineIterationFeedbackStore,
        );
        // If something went wrong, this will be null, nothing to do
        if ($arrayItems === null) {
            return [];
        }
        return $arrayItems;
    }

    /**
     * Place the result for the array in the original property.
     *
     * Backward-compat wrapper: delegates to the batched form, which
     * does the actual mutation via setProcessedArrayItemValue().
     * Internal callers in this package use the batched form directly.
     *
     * @param array<string|int,SplObjectStorage<FieldInterface,mixed>> $resolvedIDFieldValues
     */
    protected function addProcessedItemBackToResolvedIDFieldValues(
        RelationalTypeResolverInterface $relationalTypeResolver,
        array &$resolvedIDFieldValues,
        EngineIterationFeedbackStore $engineIterationFeedbackStore,
        string|int $id,
        FieldInterface $field,
        int|string $arrayItemKey,
        mixed $arrayItemValue,
    ): void {
        $this->addProcessedItemsBackToResolvedIDFieldValues(
            $relationalTypeResolver,
            $resolvedIDFieldValues,
            $engineIterationFeedbackStore,
            $id,
            $field,
            [[$arrayItemKey, $arrayItemValue]],
        );
    }

    /**
     * Batched compose: read the (id, field) container once, mutate it
     * in place for every array item via setProcessedArrayItemValue(),
     * then write it back once.
     *
     * For PHP arrays this changes per-(id, field) compose-back from
     * O(N²) (each per-item assignment triggers a copy-on-write of an
     * N-element array) to O(N) — one COW + N in-place writes.
     *
     * @param array<string|int,SplObjectStorage<FieldInterface,mixed>> $resolvedIDFieldValues
     * @param list<array{0:int|string,1:mixed}> $arrayItemKeyValues
     */
    protected function addProcessedItemsBackToResolvedIDFieldValues(
        RelationalTypeResolverInterface $relationalTypeResolver,
        array &$resolvedIDFieldValues,
        EngineIterationFeedbackStore $engineIterationFeedbackStore,
        string|int $id,
        FieldInterface $field,
        array $arrayItemKeyValues,
    ): void {
        if ($arrayItemKeyValues === []) {
            return;
        }
        /** @var array<string|int,mixed>|stdClass */
        $value = $resolvedIDFieldValues[$id][$field];
        foreach ($arrayItemKeyValues as [$arrayItemKey, $arrayItemValue]) {
            $this->setProcessedArrayItemValue(
                $relationalTypeResolver,
                $engineIterationFeedbackStore,
                $id,
                $field,
                $arrayItemKey,
                $arrayItemValue,
                $value,
            );
        }
        $resolvedIDFieldValues[$id][$field] = $value;
    }

    /**
     * Mutate `$value` in place to record the processed item at
     * `$arrayItemKey`. Subclasses override this hook to customize how
     * the result is placed back (e.g. path-based traversion for
     * `@underJSONObjectProperty`) without having to handle the
     * read/write of the outer container.
     *
     * @param array<string|int,mixed>|stdClass &$value
     */
    protected function setProcessedArrayItemValue(
        RelationalTypeResolverInterface $relationalTypeResolver,
        EngineIterationFeedbackStore $engineIterationFeedbackStore,
        string|int $id,
        FieldInterface $field,
        int|string $arrayItemKey,
        mixed $arrayItemValue,
        array|stdClass &$value,
    ): void {
        if (is_array($value)) {
            $value[$arrayItemKey] = $arrayItemValue;
        } else {
            // stdClass
            $value->$arrayItemKey = $arrayItemValue;
        }
    }

    /**
     * Return the items to iterate on
     *
     * @param array<string,array<string|int,SplObjectStorage<FieldInterface,mixed>>> $previouslyResolvedIDFieldValues
     * @param array<array<string|int,EngineIterationFieldSet>> $succeedingPipelineIDFieldSet
     * @param array<string|int,SplObjectStorage<FieldInterface,mixed>> $resolvedIDFieldValues
     * @param mixed[]|stdClass $arrayOrObject
     * @param array<string|int,object> $idObjects
     * @param array<string,mixed> $messages
     * @return mixed[]|null
     */
    abstract protected function doGetArrayItems(
        array|stdClass &$arrayOrObject,
        int|string $id,
        FieldInterface $field,
        RelationalTypeResolverInterface $relationalTypeResolver,
        array $idObjects,
        array $previouslyResolvedIDFieldValues,
        array &$succeedingPipelineIDFieldSet,
        array &$resolvedIDFieldValues,
        array &$messages,
        EngineIterationFeedbackStore $engineIterationFeedbackStore,
    ): ?array;

    /**
     * Create a property for storing the array item in the current object.
     *
     * Important: use chars '[...]' as part of the field name because
     * it's forbidden by GraphQL spec, so there's no risk of
     * conflict with an existing property. And additionally,
     * prepend it with char '*' as to give it some prominence,
     * to further say "this field is different"
     */
    protected function createPropertyForArrayItem(string $fieldAliasOrName, string $key): string
    {
        return '*' . $fieldAliasOrName . '[' . $key . ']';
    }

    /**
     * Name for the directive arg(s) to indicate the name of the
     * dynamic variable(s).
     *
     * Eg:
     *
     *   - @underEachArrayItem(passIndexOnwardsAs: "index", passValueOnwardsAs: "value")
     *   - @underArrayItem(passOnwardsAs: "item")
     *
     * @return string[]
     */
    public function getExportUnderVariableNameArgumentNames(): array
    {
        return array_merge(
            [
                $this->getPassValueOnwardsAsVariableArgumentName(),
            ],
            $this->passKeyOnwardsAsVariable()
                ? [
                    $this->getPassKeyOnwardsAsVariableArgumentName(),
                ]
                : []
        );
    }

    protected function getPassValueOnwardsAsVariableArgumentName(): string
    {
        return $this->passKeyOnwardsAsVariable() ? 'passValueOnwardsAs' : 'passOnwardsAs';
    }

    protected function getPassKeyOnwardsAsVariableArgumentName(): string
    {
        return 'passKeyOnwardsAs';
    }

    final public function mustResolveDynamicVariableOnObject(): bool
    {
        return true;
    }
}
