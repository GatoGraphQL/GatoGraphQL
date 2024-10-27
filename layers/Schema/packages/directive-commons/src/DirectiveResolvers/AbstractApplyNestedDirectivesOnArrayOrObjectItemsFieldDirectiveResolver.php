<?php

declare(strict_types=1);

namespace PoPSchema\DirectiveCommons\DirectiveResolvers;

use PoPSchema\DirectiveCommons\StateServices\ObjectResolvedDynamicVariablesServiceInterface;
use PoP\ComponentModel\App;
use PoP\ComponentModel\DirectivePipeline\DirectivePipelineServiceInterface;
use PoP\ComponentModel\DirectiveResolvers\AbstractGlobalMetaFieldDirectiveResolver;
use PoP\ComponentModel\DirectiveResolvers\DynamicVariableDefinerFieldDirectiveResolverInterface;
use PoP\ComponentModel\DirectiveResolvers\FieldDirectiveResolverInterface;
use PoP\ComponentModel\Engine\EngineIterationFieldSet;
use PoP\ComponentModel\FeedbackItemProviders\ErrorFeedbackItemProvider;
use PoP\ComponentModel\Feedback\EngineIterationFeedbackStore;
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
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\LeafField;
use PoP\GraphQLParser\Spec\Parser\Ast\RelationalField;
use PoP\GraphQLParser\Spec\Parser\RuntimeLocation;
use PoP\ComponentModel\Feedback\FeedbackItemResolution;
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
            $this->getPassValueOnwardsAsVariableArgumentName() => $this->__('The name of the dynamic variable under which to export the array element value from the current iteration', 'component-model'),
            $this->getPassKeyOnwardsAsVariableArgumentName() => $this->__('The name of the dynamic variable under which to export the array element key from the current iteration', 'component-model'),
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
         * Collect all ID => dataFields for the arrayItems
         *
         * @var array<string|int,EngineIterationFieldSet>
         */
        $arrayItemIDsProperties = [];
        $typeOutputKey = $relationalTypeResolver->getTypeOutputKey();

        /**
         * Execute composed directive only if the validations do not fail
         */
        $execute = false;

        /**
         * Append fieldArgs for the array item fields
         */
        $nestedFieldDataAccessProvider = clone $fieldDataAccessProvider;

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
        $originalFieldTypeModifiersByField = clone $fieldTypeModifiersByField;

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
                $hasIDFieldBeenResolved = isset($resolvedIDFieldValues[$id]) && $resolvedIDFieldValues[$id]->contains($field);
                if (!$hasIDFieldBeenResolved && !(isset($previouslyResolvedIDFieldValues[$typeOutputKey][$id]) && $previouslyResolvedIDFieldValues[$typeOutputKey][$id]->contains($field))) {
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

                    if (
                        $decreaseFieldTypeModifiersCardinalityForSerialization
                        // Execute only once per field (i.e. avoid recalculating for different objects)
                        && !$currentFieldTypeModifiersByField->contains($field)
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
                        $appStateManager->override('field-type-modifiers-for-serialization', $fieldTypeModifiersByField);
                    }

                    $arrayItemIDsProperties[$id] ??= new EngineIterationFieldSet();
                    foreach ($arrayItems as $key => &$value) {
                        /**
                         * Add into the $idFieldSet object for the array items.
                         *
                         * Watch out: function `regenerateAndExecuteFunction`
                         * receives `$idFieldSet` and not `$idsFieldSetOutputKeys`,
                         * so then re-create the "field" assigning a new alias.
                         * If it has an alias, use it. If not, use the fieldName
                         */
                        $arrayItemAlias = $this->createPropertyForArrayItem($field->getOutputKey(), (string) $key);
                        $arrayItemField = $this->getArrayItemField($field, $arrayItemAlias);
                        $nestedFieldDataAccessProvider->duplicateFieldData($field, $arrayItemField);
                        // Place into the current object
                        $resolvedIDFieldValues[$id][$arrayItemField] = $value;
                        // Place it into list of fields to process
                        $arrayItemIDsProperties[$id]->fields[] = $arrayItemField;

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
                        if (isset($fieldTypeModifiersByField[$field])) {
                            $fieldTypeModifiersByField[$arrayItemField] = $fieldTypeModifiersByField[$field];
                            $appStateManager->override('field-type-modifiers-for-serialization', $fieldTypeModifiersByField);
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
                        if ($this->passKeyOnwardsAsVariable() && !empty($passKeyOnwardsAs)) {
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
                        /**
                         * Allow the Field created by @underJSONObjectProperty
                         * to read the state defined at that previous level
                         */
                        $objectResolvedDynamicVariablesService->copyObjectResolvedDynamicVariablesFromFieldToFieldInAppState(
                            $field,
                            $arrayItemField,
                        );
                    }
                }
            }
        }

        if ($decreaseFieldTypeModifiersCardinalityForSerialization) {
            $appStateManager->override('field-type-modifiers-for-serialization', $fieldTypeModifiersByField);
        }

        if ($execute) {
            // Build the directive pipeline
            /** @var FieldDirectiveResolverInterface[] */
            $nestedDirectiveResolvers = iterator_to_array($this->nestedDirectivePipelineData);
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
            $separateEngineIterationFeedbackStore->objectResolutionFeedbackStore->setErrors([]);
            $separateEngineIterationFeedbackStore->schemaFeedbackStore->setErrors([]);
            $engineIterationFeedbackStore->incorporate($separateEngineIterationFeedbackStore);

            /**
             * Restore 'field-type-modifiers-for-serialization' to the previous state
             */
            if ($decreaseFieldTypeModifiersCardinalityForSerialization) {
                $appStateManager->override('field-type-modifiers-for-serialization', $originalFieldTypeModifiersByField);
            }

            // If any item fails, set the whole response field as null
            if ($objectResolutionFeedbackStoreErrors !== [] || $schemaFeedbackStoreErrors !== []) {
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

            // 3. Compose the array from the results for each array item
            foreach ($idFieldSet as $id => $fieldSet) {
                $object = $idObjects[$id];
                foreach ($fieldSet->fields as $field) {
                    $hasIDFieldBeenResolved = isset($resolvedIDFieldValues[$id]) && $resolvedIDFieldValues[$id]->contains($field);
                    $value = $hasIDFieldBeenResolved ?
                        $resolvedIDFieldValues[$id][$field] :
                        $previouslyResolvedIDFieldValues[$typeOutputKey][$id][$field];

                    // If the array is null or empty, nothing to do
                    if (!$value) {
                        continue;
                    }
                    if (!$this->validateInputValueType($value)) {
                        continue;
                    }

                    if ($resolveDirectiveArgsOnObject) {
                        $this->loadObjectResolvedDynamicVariablesInAppState($field, $id);
                        $this->directiveDataAccessor->resetDirectiveArgs();
                    }

                    // If there are errors, it will return null. Don't add the errors again
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
                    // The value is an array. Unpack all the elements into their own property
                    foreach (array_keys($arrayItems) as $key) {
                        $arrayItemAlias = $this->createPropertyForArrayItem($field->getOutputKey(), (string) $key);
                        $arrayItemField = $this->getArrayItemField($field, $arrayItemAlias);
                        // Place the result of executing the function on the array item
                        $arrayItemValue = $resolvedIDFieldValues[$id][$arrayItemField];
                        // Remove this temporary property from $resolvedIDFieldValues
                        $resolvedIDFieldValues[$id]->detach($arrayItemField);
                        // Place the result for the array in the original property
                        $this->addProcessedItemBackToResolvedIDFieldValues($relationalTypeResolver, $resolvedIDFieldValues, $engineIterationFeedbackStore, $id, $field, $key, $arrayItemValue);
                    }
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
        if (!$this->arrayItemFieldInstanceContainer->contains($field) || !isset($this->arrayItemFieldInstanceContainer[$field][$arrayItemAlias])) {
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
     * Place the result for the array in the original property
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
        $value = $resolvedIDFieldValues[$id][$field];
        if (is_array($value)) {
            $value[$arrayItemKey] = $arrayItemValue;
        } else {
            // stdClass
            $value->$arrayItemKey = $arrayItemValue;
        }
        $resolvedIDFieldValues[$id][$field] = $value;
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
