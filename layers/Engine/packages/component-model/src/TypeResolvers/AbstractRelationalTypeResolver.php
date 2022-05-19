<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolvers;

use PoP\ComponentModel\AttachableExtensions\AttachableExtensionGroups;
use PoP\ComponentModel\Module;
use PoP\ComponentModel\ModuleConfiguration;
use PoP\ComponentModel\DirectivePipeline\DirectivePipelineServiceInterface;
use PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface;
use PoP\ComponentModel\Engine\DataloadingEngineInterface;
use PoP\ComponentModel\Feedback\EngineIterationFeedbackStore;
use PoP\Root\Feedback\FeedbackItemResolution;
use PoP\ComponentModel\Feedback\SchemaFeedback;
use PoP\ComponentModel\FeedbackItemProviders\DeprecationFeedbackItemProvider;
use PoP\ComponentModel\FeedbackItemProviders\ErrorFeedbackItemProvider;
use PoP\ComponentModel\RelationalTypeResolverDecorators\RelationalTypeResolverDecoratorInterface;
use PoP\ComponentModel\Schema\FieldQueryInterpreterInterface;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeHelpers;
use PoP\FieldQuery\QueryHelpers;
use PoP\FieldQuery\QuerySyntax;
use PoP\FieldQuery\QueryUtils;
use PoP\GraphQLParser\StaticHelpers\LocationHelper;
use PoP\Root\App;

abstract class AbstractRelationalTypeResolver extends AbstractTypeResolver implements RelationalTypeResolverInterface
{
    use ExcludeFieldNamesFromSchemaTypeResolverTrait;

    public final const OPTION_VALIDATE_SCHEMA_ON_RESULT_ITEM = 'validateSchemaOnObject';

    /**
     * @var array<string,DirectiveResolverInterface[]>|null
     */
    protected ?array $directiveNameResolvers = null;
    /**
     * @var string[]|null
     */
    protected ?array $typeResolverDecorators = null;
    /**
     * @var array<string, array>|null
     */
    protected ?array $precedingMandatoryDirectivesForDirectives = null;
    /**
     * @var array<string, array>|null
     */
    protected ?array $succeedingMandatoryDirectivesForDirectives = null;

    /**
     * @var array<string, array>
     */
    private array $fieldDirectiveIDFields = [];
    /**
     * @var array<string, string>
     */
    private array $fieldDirectivesFromFieldCache = [];
    /**
     * @var array<string, array<string, DirectiveResolverInterface>>
     */
    private array $directiveResolverInstanceCache = [];

    private ?FieldQueryInterpreterInterface $fieldQueryInterpreter = null;
    private ?DataloadingEngineInterface $dataloadingEngine = null;
    private ?DirectivePipelineServiceInterface $directivePipelineService = null;

    final public function setFieldQueryInterpreter(FieldQueryInterpreterInterface $fieldQueryInterpreter): void
    {
        $this->fieldQueryInterpreter = $fieldQueryInterpreter;
    }
    final protected function getFieldQueryInterpreter(): FieldQueryInterpreterInterface
    {
        return $this->fieldQueryInterpreter ??= $this->instanceManager->getInstance(FieldQueryInterpreterInterface::class);
    }
    final public function setDataloadingEngine(DataloadingEngineInterface $dataloadingEngine): void
    {
        $this->dataloadingEngine = $dataloadingEngine;
    }
    final protected function getDataloadingEngine(): DataloadingEngineInterface
    {
        return $this->dataloadingEngine ??= $this->instanceManager->getInstance(DataloadingEngineInterface::class);
    }
    final public function setDirectivePipelineService(DirectivePipelineServiceInterface $directivePipelineService): void
    {
        $this->directivePipelineService = $directivePipelineService;
    }
    final protected function getDirectivePipelineService(): DirectivePipelineServiceInterface
    {
        return $this->directivePipelineService ??= $this->instanceManager->getInstance(DirectivePipelineServiceInterface::class);
    }

    /**
     * @return array<string,DirectiveResolverInterface[]>
     */
    protected function getDirectiveNameResolvers(): array
    {
        if (is_null($this->directiveNameResolvers)) {
            $this->directiveNameResolvers = $this->calculateFieldDirectiveNameResolvers();
        }
        return $this->directiveNameResolvers;
    }

    /**
     * @param string|int|array<string|int> $dbObjectIDOrIDs
     * @return string|int|array<string|int>
     */
    public function getQualifiedDBObjectIDOrIDs(string | int | array $dbObjectIDOrIDs): string | int | array
    {
        // Add the type before the ID
        $objectIDs = is_array($dbObjectIDOrIDs) ? $dbObjectIDOrIDs : [$dbObjectIDOrIDs];
        $qualifiedDBObjectIDs = array_map(
            fn (int | string $id) => UnionTypeHelpers::getObjectComposedTypeAndID(
                $this,
                $id
            ),
            $objectIDs
        );
        return is_array($dbObjectIDOrIDs) ? $qualifiedDBObjectIDs : $qualifiedDBObjectIDs[0];
    }

    public function qualifyDBObjectIDsToRemoveFromErrors(): bool
    {
        return false;
    }

    /**
     * By default, the pipeline must always have directives:
     *
     *   1. Validate: to validate that the schema, fieldNames, etc are supported, and filter them out if not
     *   2. ResolveAndMerge: to resolve the field and place the data into the DB object
     *   3. SerializeScalarTypeValuesInDBItems: to serialize Scalar Type values
     *
     * Additionally to these 3, we can add other mandatory directives, such as:
     *   - setSelfAsExpression
     *   - cacheControl
     *
     * Because it may be more convenient to add the directive or the class, there are 2 methods
     */
    protected function getMandatoryDirectives()
    {
        return array_map(
            function ($directiveResolver) {
                return $this->getFieldQueryInterpreter()->listFieldDirective($directiveResolver->getDirectiveName());
            },
            $this->getDataloadingEngine()->getMandatoryDirectiveResolvers()
        );
    }

    /**
     * Validate and resolve the fieldDirectives into an array, each item containing:
     * 1. the directiveResolverInstance
     * 2. its fieldDirective
     * 3. the fields it affects
     */
    public function resolveDirectivesIntoPipelineData(
        array $fieldDirectives,
        array &$fieldDirectiveFields,
        array &$variables,
        EngineIterationFeedbackStore $engineIterationFeedbackStore,
    ): array {
        /**
        * All directives are placed somewhere in the pipeline. There are 5 positions:
        * 1. At the beginning
        * 2. Before Validate directive
        * 3. Between the Validate and Resolve directives
        * 4. After the ResolveAndMerge directive
        * 4. At the end
        */
        $directiveInstancesByPosition = $fieldDirectivesByPosition = $directiveFieldsByPosition = [
            PipelinePositions::BEGINNING => [],
            PipelinePositions::BEFORE_VALIDATE => [],
            PipelinePositions::AFTER_VALIDATE_BEFORE_RESOLVE => [],
            PipelinePositions::AFTER_RESOLVE => [],
            PipelinePositions::END => [],
        ];

        // Resolve from directive into their actual object instance.
        $directiveResolverInstanceData = $this->validateAndResolveInstances(
            $fieldDirectives,
            $fieldDirectiveFields,
            $variables,
            $engineIterationFeedbackStore,
        );

        // Create an array with the dataFields affected by each directive, in order in which they will be invoked
        foreach ($directiveResolverInstanceData as $instanceID => $directiveResolverInstanceData) {
            // Add the directive in its required position in the pipeline, and retrieve what fields it will process
            $directiveResolverInstance = $directiveResolverInstanceData['instance'];
            $pipelinePosition = $directiveResolverInstance->getPipelinePosition();
            $directiveInstancesByPosition[$pipelinePosition][] = $directiveResolverInstance;
            $fieldDirectivesByPosition[$pipelinePosition][] = $directiveResolverInstanceData['fieldDirective'];
            $directiveFieldsByPosition[$pipelinePosition][] = $directiveResolverInstanceData['fields'];
        }

        // Once we have them ordered, we can simply discard the positions, keep only the values
        // Each item has 3 elements: the directiveResolverInstance, its fieldDirective, and the fields it affects
        $pipelineData = [];
        foreach ($directiveInstancesByPosition as $position => $directiveResolverInstances) {
            for ($i = 0; $i < count($directiveResolverInstances); $i++) {
                $pipelineData[] = [
                    'instance' => $directiveResolverInstances[$i],
                    'fieldDirective' => $fieldDirectivesByPosition[$position][$i],
                    'fields' => $directiveFieldsByPosition[$position][$i],
                ];
            }
        }

        return $pipelineData;
    }

    protected function validateAndResolveInstances(
        array $fieldDirectives,
        array $fieldDirectiveFields,
        array &$variables,
        EngineIterationFeedbackStore $engineIterationFeedbackStore,
    ): array {
        $instances = [];
        // Count how many times each directive is added
        $directiveFieldTrack = [];
        $directiveResolverInstanceFields = [];
        $fieldDirectivesCount = count($fieldDirectives);
        for ($i = 0; $i < $fieldDirectivesCount; $i++) {
            // Because directives can be repeated inside a field (eg: <resize(50%),resize(50%)>),
            // then we deal with 2 variables:
            // 1. $fieldDirective: the actual directive
            // 2. $enqueuedFieldDirective: how it was added to the array
            // For retrieving the idsDataFields for the directive, we'll use $enqueuedFieldDirective, since under this entry we stored all the data in the previous functions
            // For everything else, we use $fieldDirective
            $enqueuedFieldDirective = $fieldDirectives[$i];
            // Check if it is a repeated directive: if it has the "|" symbol
            $counterSeparatorPos = QueryUtils::findLastSymbolPosition(
                $enqueuedFieldDirective,
                FieldSymbols::REPEATED_DIRECTIVE_COUNTER_SEPARATOR,
                [QuerySyntax::SYMBOL_FIELDARGS_OPENING, QuerySyntax::SYMBOL_FIELDDIRECTIVE_OPENING],
                [QuerySyntax::SYMBOL_FIELDARGS_CLOSING, QuerySyntax::SYMBOL_FIELDDIRECTIVE_CLOSING],
            );
            $isRepeatedFieldDirective = $counterSeparatorPos !== false;
            if ($isRepeatedFieldDirective) {
                // Remove the "|counter" bit from the fieldDirective
                $fieldDirective = substr($enqueuedFieldDirective, 0, $counterSeparatorPos);
            } else {
                $fieldDirective = $enqueuedFieldDirective;
            }

            $fieldDirectiveResolverInstances = $this->getDirectiveResolverInstancesForDirective($fieldDirective, $fieldDirectiveFields[$enqueuedFieldDirective], $variables);
            $directiveName = $this->getFieldQueryInterpreter()->getFieldDirectiveName($fieldDirective);
            // If there is no directive with this name, show an error and skip it
            if ($fieldDirectiveResolverInstances === null) {
                foreach ($fieldDirectiveFields[$fieldDirective] as $field) {
                    $engineIterationFeedbackStore->schemaFeedbackStore->addError(
                        new SchemaFeedback(
                            new FeedbackItemResolution(
                                ErrorFeedbackItemProvider::class,
                                ErrorFeedbackItemProvider::E20,
                                [
                                    $directiveName,
                                ]
                            ),
                            LocationHelper::getNonSpecificLocation(),
                            $this,
                            $field,
                        )
                    );
                }
                continue;
            }
            $directiveArgs = $this->getFieldQueryInterpreter()->extractStaticDirectiveArguments($fieldDirective);

            if (empty($fieldDirectiveResolverInstances)) {
                foreach ($fieldDirectiveFields[$fieldDirective] as $field) {
                    $engineIterationFeedbackStore->schemaFeedbackStore->addError(
                        new SchemaFeedback(
                            new FeedbackItemResolution(
                                ErrorFeedbackItemProvider::class,
                                ErrorFeedbackItemProvider::E21,
                                [
                                    $directiveName,
                                    json_encode($directiveArgs),
                                    implode(
                                        $this->__('\', \'', 'component-model'),
                                        $fieldDirectiveFields[$fieldDirective]
                                    ),
                                ]
                            ),
                            LocationHelper::getNonSpecificLocation(),
                            $this,
                            $field,
                        )
                    );
                }
                continue;
            }

            foreach ($fieldDirectiveFields[$enqueuedFieldDirective] as $field) {
                $directiveResolverInstance = $fieldDirectiveResolverInstances[$field] ?? null;
                if ($directiveResolverInstance === null) {
                    $engineIterationFeedbackStore->schemaFeedbackStore->addError(
                        new SchemaFeedback(
                            new FeedbackItemResolution(
                                ErrorFeedbackItemProvider::class,
                                ErrorFeedbackItemProvider::E22,
                                [
                                    $directiveName,
                                    json_encode($directiveArgs),
                                    $field,
                                ]
                            ),
                            LocationHelper::getNonSpecificLocation(),
                            $this,
                            $field,
                        )
                    );
                    continue;
                }

                // Consolidate the same directiveResolverInstances for different fields,
                // as to do the validation only once on each of them
                $instanceID = get_class($directiveResolverInstance) . $enqueuedFieldDirective;
                if (!isset($directiveResolverInstanceFields[$instanceID])) {
                    $directiveResolverInstanceFields[$instanceID]['fieldDirective'] = $fieldDirective;
                    $directiveResolverInstanceFields[$instanceID]['enqueuedFieldDirective'] = $enqueuedFieldDirective;
                    $directiveResolverInstanceFields[$instanceID]['instance'] = $directiveResolverInstance;
                }
                $directiveResolverInstanceFields[$instanceID]['fields'][] = $field;
            }
        }

        // Validate all the directiveResolvers in the field
        foreach ($directiveResolverInstanceFields as $instanceID => $instanceData) {
            $fieldDirective = $instanceData['fieldDirective'];
            $enqueuedFieldDirective = $instanceData['enqueuedFieldDirective'];
            /** @var DirectiveResolverInterface */
            $directiveResolverInstance = $instanceData['instance'];
            $directiveResolverFields = $instanceData['fields'];
            // If the enqueued and the fieldDirective are different, it's because it is a repeated one
            $isRepeatedFieldDirective = $fieldDirective !== $enqueuedFieldDirective;
            // If it is a repeated directive, no need to do the validation again
            if (!$isRepeatedFieldDirective) {
                // Validate schema (eg of error in schema: ?query=posts<include(if:this-field-doesnt-exist())>)
                $separateEngineIterationFeedbackStore = new EngineIterationFeedbackStore();
                list(
                    $validFieldDirective,
                    $directiveName,
                    $directiveArgs,
                ) = $directiveResolverInstance->dissectAndValidateDirectiveForSchema(
                    $this,
                    $fieldDirectiveFields,
                    $variables,
                    $separateEngineIterationFeedbackStore,
                );
                $engineIterationFeedbackStore->incorporate($separateEngineIterationFeedbackStore);
                /** @phpstan-ignore-next-line */
                if ($separateEngineIterationFeedbackStore->hasErrors()) {
                    continue;
                }

                // Validate against the directiveResolver
                if ($maybeErrorFeedbackItemResolutions = $directiveResolverInstance->resolveDirectiveValidationErrors($this, $directiveName, $directiveArgs)) {
                    foreach ($maybeErrorFeedbackItemResolutions as $errorFeedbackItemResolution) {
                        foreach ($fieldDirectiveFields[$fieldDirective] as $field) {
                            $engineIterationFeedbackStore->schemaFeedbackStore->addError(
                                new SchemaFeedback(
                                    $errorFeedbackItemResolution,
                                    LocationHelper::getNonSpecificLocation(),
                                    $this,
                                    $field,
                                )
                            );
                        }
                    }
                    continue;
                }

                // Check for warnings
                if ($warningFeedbackItemResolution = $directiveResolverInstance->resolveDirectiveWarning($this)) {
                    foreach ($fieldDirectiveFields[$fieldDirective] as $field) {
                        $engineIterationFeedbackStore->schemaFeedbackStore->addWarning(
                            new SchemaFeedback(
                                $warningFeedbackItemResolution,
                                LocationHelper::getNonSpecificLocation(),
                                $this,
                                $field,
                            )
                        );
                    }
                }

                // Check for deprecations
                if ($deprecationMessage = $directiveResolverInstance->getDirectiveDeprecationMessage($this)) {
                    foreach ($fieldDirectiveFields[$fieldDirective] as $field) {
                        $engineIterationFeedbackStore->schemaFeedbackStore->addDeprecation(
                            new SchemaFeedback(
                                new FeedbackItemResolution(
                                    DeprecationFeedbackItemProvider::class,
                                    DeprecationFeedbackItemProvider::D1,
                                    [
                                        $directiveName,
                                        $deprecationMessage,
                                    ]
                                ),
                                LocationHelper::getNonSpecificLocation(),
                                $this,
                                $field,
                            )
                        );
                    }
                }
            }

            // Validate if the directive can be executed multiple times on each field
            if (!$directiveResolverInstance->isRepeatable()) {
                // Check if the directive is already processing any of the fields
                $directiveName = $this->getFieldQueryInterpreter()->getFieldDirectiveName($fieldDirective);
                $alreadyProcessingFields = array_intersect(
                    $directiveFieldTrack[$directiveName] ?? [],
                    $directiveResolverFields
                );
                $directiveFieldTrack[$directiveName] = array_unique(array_merge(
                    $directiveFieldTrack[$directiveName] ?? [],
                    $directiveResolverFields
                ));
                if ($alreadyProcessingFields) {
                    // Remove the fields from this iteration, and add an error
                    $directiveResolverFields = array_diff(
                        $directiveResolverFields,
                        $alreadyProcessingFields
                    );
                    foreach ($alreadyProcessingFields as $field) {
                        $engineIterationFeedbackStore->schemaFeedbackStore->addError(
                            new SchemaFeedback(
                                new FeedbackItemResolution(
                                    ErrorFeedbackItemProvider::class,
                                    ErrorFeedbackItemProvider::E23,
                                    [
                                        $fieldDirective,
                                        implode('\', \'', $alreadyProcessingFields),
                                    ]
                                ),
                                LocationHelper::getNonSpecificLocation(),
                                $this,
                                $field,
                            )
                        );
                    }
                    // If after removing the duplicated fields there are still others, process them
                    // Otherwise, skip
                    if (!$directiveResolverFields) {
                        continue;
                    }
                }
            }

            // Directive is valid. Add it under its instanceID, which enables to add fields under the same directiveResolverInstance
            $instances[$instanceID]['instance'] = $directiveResolverInstance;
            $instances[$instanceID]['fieldDirective'] = $fieldDirective;
            $instances[$instanceID]['fields'] = $directiveResolverFields;
        }
        return $instances;
    }

    /**
     * @return array<string,DirectiveResolverInterface>|null
     */
    public function getDirectiveResolverInstancesForDirective(string $fieldDirective, array $fieldDirectiveFields, array &$variables): ?array
    {
        $directiveName = $this->getFieldQueryInterpreter()->getFieldDirectiveName($fieldDirective);
        $directiveArgs = $this->getFieldQueryInterpreter()->extractStaticDirectiveArguments($fieldDirective);

        $directiveNameResolvers = $this->getDirectiveNameResolvers();
        $directiveResolvers = $directiveNameResolvers[$directiveName] ?? null;
        if ($directiveResolvers === null) {
            return null;
        }

        // Calculate directives per field
        $fieldDirectiveResolverInstances = [];
        foreach ($fieldDirectiveFields as $field) {
            // Check that at least one class which deals with this directiveName can satisfy the directive (for instance, validating that a required directiveArg is present)
            $fieldName = $this->getFieldQueryInterpreter()->getFieldName($field);
            foreach ($directiveResolvers as $directiveResolver) {
                $directiveSupportedFieldNames = $directiveResolver->getFieldNamesToApplyTo();
                // If this field is not supported by the directive, skip
                if ($directiveSupportedFieldNames && !in_array($fieldName, $directiveSupportedFieldNames)) {
                    continue;
                }
                $directiveResolverClass = get_class($directiveResolver);
                // Get the instance from the cache if it exists, or create it if not
                if (!isset($this->directiveResolverInstanceCache[$directiveResolverClass][$fieldDirective])) {
                    /**
                     * The instance from the container is shared. We need a non-shared instance
                     * to set the unique $fieldDirective. So clone the service.
                     */
                    $fieldDirectiveResolver = clone $directiveResolver;
                    $fieldDirectiveResolver->setDirective($fieldDirective);
                    $this->directiveResolverInstanceCache[$directiveResolverClass][$fieldDirective] = $fieldDirectiveResolver;
                }
                $maybeDirectiveResolverInstance = $this->directiveResolverInstanceCache[$directiveResolverClass][$fieldDirective];
                // Check if this instance can process the directive
                if ($maybeDirectiveResolverInstance->resolveCanProcess($this, $directiveName, $directiveArgs, $field, $variables)) {
                    $fieldDirectiveResolverInstances[$field] = $maybeDirectiveResolverInstance;
                    break;
                }
            }
        }
        return $fieldDirectiveResolverInstances;
    }

    /**
     * @return mixed[]
     */
    protected function getIDsToQuery(array $ids_data_fields): array
    {
        return array_keys($ids_data_fields);
    }

    public function fillObjects(
        array $ids_data_fields,
        array $unionDBKeyIDs,
        array $previousDBItems,
        array &$dbItems,
        array &$variables,
        array &$messages,
        EngineIterationFeedbackStore $engineIterationFeedbackStore,
    ): array {
        // Obtain the data for the required object IDs
        $objectIDItems = [];
        $ids = $this->getIDsToQuery($ids_data_fields);
        $typeDataLoader = $this->getRelationalTypeDataLoader();
        // If any ID cannot be resolved, the object will be null
        $objects = array_filter($typeDataLoader->getObjects($ids));
        foreach ($objects as $object) {
            $objectID = $this->getID($object);
            // If the UnionTypeResolver doesn't have a TypeResolver to process this element, the ID will be null, and an error will be show below
            if ($objectID === null) {
                continue;
            }
            $objectIDItems[$objectID] = $object;
            /**
             * If no fields are queried, the entry will be null.
             * Initialize it to [] to simplify typing/null-checking
             */
            $dbItems[$objectID] ??= [];
        }
        // Show an error for all objects that couldn't be processed
        $resolvedObjectIDs = $this->getIDsToQuery($objectIDItems);
        $unresolvedObjectIDs = [];
        $schemaFeedbackStore = $engineIterationFeedbackStore->schemaFeedbackStore;
        foreach (array_diff($ids, $resolvedObjectIDs) as $unresolvedObjectID) {
            // If a UnionTypeResolver fails to load an object, the fields will be NULL
            $failedFields = $ids_data_fields[$unresolvedObjectID]['direct'] ?? [];
            // Add in $schemaErrors instead of $objectErrors because in the latter one it will attempt to fetch the ID from the object, which it can't do
            foreach ($failedFields as $failedField) {
                $schemaFeedbackStore->addError(
                    new SchemaFeedback(
                        $this->getUnresolvedObjectIDErrorFeedbackItemResolution($unresolvedObjectID),
                        LocationHelper::getNonSpecificLocation(),
                        $this,
                        $failedField,
                    )
                );
            }

            // Indicate that this ID must be removed from the results
            $unresolvedObjectIDs[] = $unresolvedObjectID;
        }
        // Remove all the IDs that failed from the elements to process, so it doesn't show a "Corrupted Data" error
        // Because these are IDs (eg: 223) and $ids_data_fields contains qualified or typed IDs (eg: post/223), we must convert them first
        if ($unresolvedObjectIDs) {
            if ($this->qualifyDBObjectIDsToRemoveFromErrors()) {
                $unresolvedObjectIDs = $this->getQualifiedDBObjectIDOrIDs($unresolvedObjectIDs);
            }
            $ids_data_fields = array_filter(
                $ids_data_fields,
                fn (int | string $id) => !in_array($id, $unresolvedObjectIDs),
                ARRAY_FILTER_USE_KEY
            );
        }

        // Enqueue the items
        $this->enqueueFillingObjectsFromIDs($ids_data_fields);

        // Process them
        $this->processFillingObjectsFromIDs(
            $unionDBKeyIDs,
            $objectIDItems,
            $previousDBItems,
            $dbItems,
            $variables,
            $messages,
            $engineIterationFeedbackStore,
        );

        return $objectIDItems;
    }

    protected function getUnresolvedObjectIDErrorFeedbackItemResolution(string | int $objectID): FeedbackItemResolution
    {
        return new FeedbackItemResolution(
            ErrorFeedbackItemProvider::class,
            ErrorFeedbackItemProvider::E9,
            [
                $this->getMaybeNamespacedTypeName(),
                $objectID
            ]
        );
    }

    /**
     * Given an array of directives, attach, before and after each of them, their own mandatory directives
     * Eg: a directive `@validateDoesUserHaveCapability` must be preceded by a directive `@validateIsUserLoggedIn`
     *
     * The process is recursive: mandatory directives can have their own mandatory directives, and these are added too
     */
    protected function addMandatoryDirectivesForDirectives(array $directives): array
    {
        $precedingMandatoryDirectivesForDirectives = $this->getAllPrecedingMandatoryDirectivesForDirectives();
        $succeedingMandatoryDirectivesForDirectives = $this->getAllSucceedingMandatoryDirectivesForDirectives();
        $allDirectives = [];
        foreach ($directives as $directive) {
            $directiveName = $this->getFieldQueryInterpreter()->getDirectiveName($directive);
            // Add preceding mandatory directives
            if (
                $mandatoryDirectivesForDirective = array_merge(
                    $precedingMandatoryDirectivesForDirectives[FieldSymbols::ANY_FIELD] ?? [],
                    $precedingMandatoryDirectivesForDirectives[$directiveName] ?? []
                )
            ) {
                $allDirectives = array_merge(
                    $allDirectives,
                    $this->addMandatoryDirectivesForDirectives($mandatoryDirectivesForDirective)
                );
            }
            // Add the directive
            $allDirectives[] = $directive;
            // Add succeeding mandatory directives
            if (
                $mandatoryDirectivesForDirective = array_merge(
                    $succeedingMandatoryDirectivesForDirectives[FieldSymbols::ANY_FIELD] ?? [],
                    $succeedingMandatoryDirectivesForDirectives[$directiveName] ?? []
                )
            ) {
                $allDirectives = array_merge(
                    $allDirectives,
                    $this->addMandatoryDirectivesForDirectives($mandatoryDirectivesForDirective)
                );
            }
        }

        return $allDirectives;
    }

    protected function getAllPrecedingMandatoryDirectivesForDirectives(): array
    {
        if (is_null($this->precedingMandatoryDirectivesForDirectives)) {
            $this->precedingMandatoryDirectivesForDirectives = $this->calculateAllPrecedingMandatoryDirectivesForDirectives();
        }
        return $this->precedingMandatoryDirectivesForDirectives;
    }

    protected function calculateAllPrecedingMandatoryDirectivesForDirectives(): array
    {
        $precedingMandatoryDirectivesForDirectives = [];
        $typeResolverDecorators = $this->getAllRelationalTypeResolverDecorators();
        foreach ($typeResolverDecorators as $typeResolverDecorator) {
            // array_merge_recursive so that if 2 different decorators add a directive for the same directive, the results are merged together, not override each other
            if ($typeResolverDecorator->enabled($this)) {
                $precedingMandatoryDirectivesForDirectives = array_merge_recursive(
                    $precedingMandatoryDirectivesForDirectives,
                    $typeResolverDecorator->getPrecedingMandatoryDirectivesForDirectives($this)
                );
            }
        }

        return $precedingMandatoryDirectivesForDirectives;
    }

    protected function getAllSucceedingMandatoryDirectivesForDirectives(): array
    {
        if (is_null($this->succeedingMandatoryDirectivesForDirectives)) {
            $this->succeedingMandatoryDirectivesForDirectives = $this->calculateAllSucceedingMandatoryDirectivesForDirectives();
        }
        return $this->succeedingMandatoryDirectivesForDirectives;
    }

    protected function calculateAllSucceedingMandatoryDirectivesForDirectives(): array
    {
        $succeedingMandatoryDirectivesForDirectives = [];
        $typeResolverDecorators = $this->getAllRelationalTypeResolverDecorators();
        foreach ($typeResolverDecorators as $typeResolverDecorator) {
            // array_merge_recursive so that if 2 different decorators add a directive for the same directive, the results are merged together, not override each other
            if ($typeResolverDecorator->enabled($this)) {
                $succeedingMandatoryDirectivesForDirectives = array_merge_recursive(
                    $succeedingMandatoryDirectivesForDirectives,
                    $typeResolverDecorator->getSucceedingMandatoryDirectivesForDirectives($this)
                );
            }
        }

        return $succeedingMandatoryDirectivesForDirectives;
    }

    protected function getAllRelationalTypeResolverDecorators(): array
    {
        if ($this->typeResolverDecorators === null) {
            $this->typeResolverDecorators = $this->calculateAllRelationalTypeResolverDecorators();
        }
        return $this->typeResolverDecorators;
    }

    protected function calculateAllRelationalTypeResolverDecorators(): array
    {
        $typeResolverDecorators = [];
        /**
         * Also get the decorators for the implemented interfaces
         */
        $classes = array_merge(
            [
                get_class($this->getTypeResolverToCalculateSchema()),
            ],
            array_map(
                get_class(...),
                $this->getImplementedInterfaceTypeResolvers()
            )
        );
        foreach ($classes as $class) {
            $typeResolverDecorators = array_merge(
                $typeResolverDecorators,
                $this->calculateAllRelationalTypeResolverDecoratorsForRelationalTypeOrInterfaceTypeResolverClass($class)
            );
        }

        return $typeResolverDecorators;
    }

    /**
     * @return RelationalTypeResolverDecoratorInterface[]
     */
    protected function calculateAllRelationalTypeResolverDecoratorsForRelationalTypeOrInterfaceTypeResolverClass(string $class): array
    {
        $typeResolverDecorators = [];

        // Iterate classes from the current class towards the parent classes until finding typeResolver that satisfies processing this field
        do {
            // Important: do array_reverse to enable more specific hooks, which are initialized later on in the project, to be the chosen ones (if their priority is the same)
            /** @var RelationalTypeResolverDecoratorInterface[] */
            $attachedRelationalTypeResolverDecorators = array_reverse($this->getAttachableExtensionManager()->getAttachedExtensions($class, AttachableExtensionGroups::RELATIONAL_TYPE_RESOLVER_DECORATORS));
            // Order them by priority: higher priority are evaluated first
            $extensionPriorities = array_map(
                fn (RelationalTypeResolverDecoratorInterface $typeResolverDecorator) => $typeResolverDecorator->getPriorityToAttachToClasses(),
                $attachedRelationalTypeResolverDecorators
            );
            array_multisort($extensionPriorities, SORT_DESC, SORT_NUMERIC, $attachedRelationalTypeResolverDecorators);
            // Add them to the results
            $typeResolverDecorators = array_merge(
                $typeResolverDecorators,
                $attachedRelationalTypeResolverDecorators
            );
            // Continue iterating for the class parents
        } while ($class = get_parent_class($class));

        return $typeResolverDecorators;
    }

    /**
     * Execute a hook to allow to disable directives (eg: to implement a private schema)
     *
     * @param array<string,DirectiveResolverInterface[]> $directiveNameResolvers
     * @return array<string,DirectiveResolverInterface[]> $directiveNameResolvers
     */
    protected function filterDirectiveNameResolvers(array $directiveNameResolvers): array
    {
        // Execute a hook, allowing to filter them out (eg: removing fieldNames from a private schema)
        return array_filter(
            $directiveNameResolvers,
            function ($directiveName) use ($directiveNameResolvers) {
                $directiveResolvers = $directiveNameResolvers[$directiveName];
                foreach ($directiveResolvers as $directiveResolver) {
                    // Execute 2 filters: a generic one, and a specific one
                    if (
                        App::applyFilters(
                            HookHelpers::getHookNameToFilterDirective(),
                            true,
                            $this,
                            $directiveResolver,
                            $directiveName
                        )
                    ) {
                        return App::applyFilters(
                            HookHelpers::getHookNameToFilterDirective($directiveName),
                            true,
                            $this,
                            $directiveResolver,
                            $directiveName
                        );
                    }
                    return false;
                }
                return true;
            },
            ARRAY_FILTER_USE_KEY
        );
    }

    /**
     * Split function, so it can be invoked both from here and from the UnionTypeResolver
     */
    protected function getFieldsToEnqueueFillingObjectsFromIDs(array $data_fields)
    {
        $fields = $data_fields['direct'];
        // Watch out: If there are conditional fields, these will be processed by this directive too
        // Hence, collect all these fields, and add them as if they were direct
        $conditionalFields = FieldHelpers::extractConditionalFields($data_fields);
        return array_unique(array_merge(
            $fields,
            $conditionalFields
        ));
    }

    /**
     * Split function, so it can be invoked both from here and from the UnionTypeResolver
     */
    public function doEnqueueFillingObjectsFromIDs(array $fields, array $mandatoryDirectivesForFields, array $mandatorySystemDirectives, string | int $id, array $data_fields): void
    {
        $fieldDirectiveCounter = [];
        foreach ($fields as $field) {
            if (!isset($this->fieldDirectivesFromFieldCache[$field])) {
                // Get the directives from the field
                $directives = $this->getFieldQueryInterpreter()->getDirectives($field);

                // Add the mandatory directives defined for this field or for any field in this typeResolver
                $fieldName = $this->getFieldQueryInterpreter()->getFieldName($field);
                if (
                    $mandatoryDirectivesForField = array_merge(
                        $mandatoryDirectivesForFields[FieldSymbols::ANY_FIELD] ?? [],
                        $mandatoryDirectivesForFields[$fieldName] ?? []
                    )
                ) {
                    // The mandatory directives must be placed first!
                    $directives = array_merge(
                        $mandatoryDirectivesForField,
                        $directives
                    );
                }

                // Place the mandatory "system" directives at the beginning of the list, then they will be added to their needed position in the pipeline
                $directives = array_merge(
                    $mandatorySystemDirectives,
                    $directives
                );
                // If the directives must be preceded by other directives, add them now
                $directives = $this->addMandatoryDirectivesForDirectives($directives);

                // Convert from directive to fieldDirective
                $fieldDirectives = implode(
                    /**
                     * @todo Temporary addition to match `asQueryString` in the AST
                     * Added an extra " "
                     */
                    QuerySyntax::SYMBOL_FIELDDIRECTIVE_SEPARATOR . ' ',
                    array_map(
                        $this->getFieldQueryInterpreter()->convertDirectiveToFieldDirective(...),
                        $directives
                    )
                );
                // Assign in the cache
                $this->fieldDirectivesFromFieldCache[$field] = $fieldDirectives;
            }
            // Extract all the directives, and store which fields they process
            foreach (QueryHelpers::splitFieldDirectives($this->fieldDirectivesFromFieldCache[$field]) as $fieldDirective) {
                // Watch out! Directives can be repeated, and then they must be executed multiple times
                // Eg: resizing a pic to 25%: <resize(50%),resize(50%)>
                // However, because we are adding the $idsDataFields under key $fieldDirective, when the 2nd occurrence of the directive is found,
                // adding data would just override the previous entry, and we can't keep track that it's another iteration
                // Then, as solution, change the name of the $fieldDirective, adding "|counter". This is an artificial construction,
                // in which the "|" symbol could not be part of the field naturally
                if (isset($fieldDirectiveCounter[$field][(string)$id][$fieldDirective])) {
                    // Increase counter and add to $fieldDirective
                    $fieldDirective .= FieldSymbols::REPEATED_DIRECTIVE_COUNTER_SEPARATOR . (++$fieldDirectiveCounter[$field][(string)$id][$fieldDirective]);
                } else {
                    $fieldDirectiveCounter[$field][(string)$id][$fieldDirective] = 0;
                }
                // Store which ID/field this directive must process
                if (in_array($field, $data_fields['direct'])) {
                    $this->fieldDirectiveIDFields[$fieldDirective][(string)$id]['direct'][] = $field;
                }
                if ($conditionalFields = $data_fields['conditional'][$field] ?? null) {
                    // Make sure there's always a 'direct' alongside a 'conditional'
                    $this->fieldDirectiveIDFields[$fieldDirective][(string)$id]['direct'] ??= [];
                    $this->fieldDirectiveIDFields[$fieldDirective][(string)$id]['conditional'][$field] = array_merge_recursive(
                        $this->fieldDirectiveIDFields[$fieldDirective][(string)$id]['conditional'][$field] ?? [],
                        $conditionalFields
                    );
                }
            }
        }
    }

    protected function processFillingObjectsFromIDs(
        array $unionDBKeyIDs,
        array $objectIDItems,
        array $previousDBItems,
        array &$dbItems,
        array &$variables,
        array &$messages,
        EngineIterationFeedbackStore $engineIterationFeedbackStore,
    ): void {
        // Iterate while there are directives with data to be processed
        while (!empty($this->fieldDirectiveIDFields)) {
            $fieldDirectiveIDFields = $this->fieldDirectiveIDFields;
            // Now that we have all data, remove all entries from the inner stack.
            // It may be filled again with composed directives, when resolving the pipeline
            $this->fieldDirectiveIDFields = [];

            // Calculate the fieldDirectives
            $fieldDirectives = array_keys($fieldDirectiveIDFields);

            // Calculate all the fields on which the directive will be applied.
            $fieldDirectiveFields = $fieldDirectiveFieldIDs = [];
            $fieldDirectiveDirectFields = [];
            foreach ($fieldDirectives as $fieldDirective) {
                foreach ($fieldDirectiveIDFields[$fieldDirective] as $id => $dataFields) {
                    $fieldDirectiveDirectFields = array_merge(
                        $fieldDirectiveDirectFields,
                        $dataFields['direct']
                    );
                    $conditionalFields = FieldHelpers::extractConditionalFields($dataFields);
                    $idFieldDirectiveIDFields = array_unique(array_merge(
                        $dataFields['direct'],
                        $conditionalFields
                    ));
                    $fieldDirectiveFields[$fieldDirective] = array_merge(
                        $fieldDirectiveFields[$fieldDirective] ?? [],
                        $idFieldDirectiveIDFields
                    );
                    // Also transpose the array to match field to IDs later on
                    foreach ($idFieldDirectiveIDFields as $field) {
                        $fieldDirectiveFieldIDs[$fieldDirective][$field][] = $id;
                    }
                }
                $fieldDirectiveFields[$fieldDirective] = array_unique($fieldDirectiveFields[$fieldDirective]);
            }
            $fieldDirectiveDirectFields = array_unique($fieldDirectiveDirectFields);

            // Validate and resolve the directives into instances and fields they operate on
            $separateEngineIterationFeedbackStore = new EngineIterationFeedbackStore();
            $directivePipelineData = $this->resolveDirectivesIntoPipelineData(
                $fieldDirectives,
                $fieldDirectiveFields,
                $variables,
                $separateEngineIterationFeedbackStore,
            );
            $engineIterationFeedbackStore->incorporate($separateEngineIterationFeedbackStore);

            // If any directive failed validation and the field must be set to `null`,
            // then skip processing that field altogether
            $schemaErrorFailingFields = [];
            /** @var ModuleConfiguration */
            $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
            if (
                $separateEngineIterationFeedbackStore->hasErrors()
                && $moduleConfiguration->removeFieldIfDirectiveFailed()
            ) {
                // Extract the failing fields from the errors
                foreach ($separateEngineIterationFeedbackStore->objectFeedbackStore->getErrors() as $error) {
                    $schemaErrorFailingFields[] = $error->getField();
                }
                foreach ($separateEngineIterationFeedbackStore->schemaFeedbackStore->getErrors() as $error) {
                    $schemaErrorFailingFields[] = $error->getField();
                }
                $schemaErrorFailingFields = array_unique($schemaErrorFailingFields);
                // Set those fields as null
                foreach ($fieldDirectives as $fieldDirective) {
                    foreach ($fieldDirectiveIDFields[$fieldDirective] as $id => $dataFields) {
                        $object = $objectIDItems[$id];
                        $failingFields = array_intersect(
                            $dataFields['direct'],
                            $schemaErrorFailingFields
                        );
                        foreach ($failingFields as $field) {
                            $fieldOutputKey = $this->getFieldQueryInterpreter()->getUniqueFieldOutputKey($this, $field, $object);
                            $dbItems[(string)$id][$fieldOutputKey] = null;
                        }
                    }
                }
            }

            // From the fields, reconstitute the $idsDataFields for each directive,
            // and build the array to pass to the pipeline, for each directive (stage)
            $directiveResolverInstances = $pipelineIDsDataFields = [];
            foreach ($directivePipelineData as $pipelineStageData) {
                $directiveResolverInstance = $pipelineStageData['instance'];
                $fieldDirective = $pipelineStageData['fieldDirective'];
                $directiveFields = $pipelineStageData['fields'];
                // Only process the direct fields
                $directiveDirectFields = array_intersect(
                    $directiveFields,
                    $fieldDirectiveDirectFields
                );
                // Remove those fields which have a failing directive
                $directiveDirectFields = array_diff(
                    $directiveDirectFields,
                    $schemaErrorFailingFields
                );
                // From the fields, reconstitute the $idsDataFields for each directive, and build the array to pass to the pipeline, for each directive (stage)
                $directiveIDFields = [];
                foreach ($directiveDirectFields as $field) {
                    $ids = $fieldDirectiveFieldIDs[$fieldDirective][$field];
                    foreach ($ids as $id) {
                        $directiveIDFields[$id]['direct'][] = $field;
                        if ($fieldConditionalFields = $fieldDirectiveIDFields[$fieldDirective][$id]['conditional'][$field] ?? null) {
                            $directiveIDFields[$id]['conditional'][$field] = $fieldConditionalFields;
                        } else {
                            $directiveIDFields[$id]['conditional'] = $directiveIDFields[$id]['conditional'] ?? [];
                        }
                    }
                }
                $pipelineIDsDataFields[] = $directiveIDFields;
                $directiveResolverInstances[] = $directiveResolverInstance;
            }

            // We can finally resolve the pipeline, passing along an array with the ID and fields for each directive
            $directivePipeline = $this->getDirectivePipelineService()->getDirectivePipeline($directiveResolverInstances);
            $directivePipeline->resolveDirectivePipeline(
                $this,
                $pipelineIDsDataFields,
                $directiveResolverInstances,
                $objectIDItems,
                $unionDBKeyIDs,
                $previousDBItems,
                $dbItems,
                $variables,
                $messages,
                $engineIterationFeedbackStore,
            );
        }
    }

    public function getSchemaDirectiveResolvers(bool $global): array
    {
        $directiveResolverInstances = [];
        $directiveNameResolvers = $this->getDirectiveNameResolvers();
        foreach ($directiveNameResolvers as $directiveName => $directiveResolvers) {
            foreach ($directiveResolvers as $directiveResolver) {
                // A directive can decide to not be added to the schema, eg: when it is repeated/implemented several times
                if ($directiveResolver->skipExposingDirectiveInSchema($this)) {
                    continue;
                }
                $isGlobal = $directiveResolver->isGlobal($this);
                if (($global && $isGlobal) || (!$global && !$isGlobal)) {
                    $directiveResolverInstances[$directiveName] = $directiveResolver;
                }
            }
        }
        return $directiveResolverInstances;
    }

    protected function getTypeResolverToCalculateSchema(): RelationalTypeResolverInterface
    {
        return $this;
    }

    protected function calculateFieldDirectiveNameResolvers(): array
    {
        $directiveNameResolvers = [];

        // Directives can also be attached to the interface implemented by this typeResolver
        $classes = array_merge(
            [
                get_class($this->getTypeResolverToCalculateSchema()),
            ],
            array_map(
                get_class(...),
                $this->getImplementedInterfaceTypeResolvers()
            )
        );
        foreach ($classes as $class) {
            // Iterate classes from the current class towards the parent classes until finding typeResolver that satisfies processing this field
            do {
                // Important: do array_reverse to enable more specific hooks, which are initialized later on in the project, to be the chosen ones (if their priority is the same)
                /** @var DirectiveResolverInterface[] */
                $attachedDirectiveResolvers = array_reverse($this->getAttachableExtensionManager()->getAttachedExtensions($class, AttachableExtensionGroups::DIRECTIVE_RESOLVERS));
                // Order them by priority: higher priority are evaluated first
                $extensionPriorities = array_map(
                    fn (DirectiveResolverInterface $directiveResolver) => $directiveResolver->getPriorityToAttachToClasses(),
                    $attachedDirectiveResolvers
                );
                array_multisort($extensionPriorities, SORT_DESC, SORT_NUMERIC, $attachedDirectiveResolvers);
                // Add them to the results. We keep the list of all resolvers, so that if the first one cannot process the directive (eg: through `resolveCanProcess`, the next one can do it)
                foreach ($attachedDirectiveResolvers as $directiveResolver) {
                    $directiveName = $directiveResolver->getDirectiveName();
                    $directiveNameResolvers[$directiveName][] = $directiveResolver;
                }
                // Continue iterating for the class parents
            } while ($class = get_parent_class($class));
        }

        // Validate that the user has access to the directives (eg: can remove access to them for non logged-in users)
        $directiveNameResolvers = $this->filterDirectiveNameResolvers($directiveNameResolvers);

        return $directiveNameResolvers;
    }
}
