<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolvers;

use PoP\ComponentModel\AttachableExtensions\AttachableExtensionGroups;
use PoP\ComponentModel\DirectivePipeline\DirectivePipelineServiceInterface;
use PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface;
use PoP\ComponentModel\Engine\DataloadingEngineInterface;
use PoP\ComponentModel\Engine\EngineIterationFieldSet;
use PoP\ComponentModel\Feedback\EngineIterationFeedbackStore;
use PoP\ComponentModel\Feedback\SchemaFeedback;
use PoP\ComponentModel\FeedbackItemProviders\DeprecationFeedbackItemProvider;
use PoP\ComponentModel\FeedbackItemProviders\ErrorFeedbackItemProvider;
use PoP\ComponentModel\Module;
use PoP\ComponentModel\ModuleConfiguration;
use PoP\ComponentModel\RelationalTypeResolverDecorators\RelationalTypeResolverDecoratorInterface;
use PoP\ComponentModel\Schema\FieldQueryInterpreterInterface;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeHelpers;
use PoP\GraphQLParser\Spec\Parser\Ast\Argument;
use PoP\GraphQLParser\Spec\Parser\Ast\Directive;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\GraphQLParser\StaticHelpers\LocationHelper;
use PoP\Root\App;
use PoP\Root\Feedback\FeedbackItemResolution;
use SplObjectStorage;

abstract class AbstractRelationalTypeResolver extends AbstractTypeResolver implements RelationalTypeResolverInterface
{
    use ExcludeFieldNamesFromSchemaTypeResolverTrait;

    public final const OPTION_VALIDATE_SCHEMA_ON_RESULT_ITEM = 'validateSchemaOnObject';

    /**
     * @var array<string,Directive>
     */
    protected array $directives = [];

    /**
     * @var array<string,DirectiveResolverInterface[]>|null
     */
    protected ?array $directiveNameResolvers = null;
    /**
     * @var RelationalTypeResolverDecoratorInterface[]|null
     */
    protected ?array $typeResolverDecorators = null;
    /**
     * @var array<string,Directive[]>|null
     */
    protected ?array $precedingMandatoryDirectivesForDirectives = null;
    /**
     * @var array<string,Directive[]>|null
     */
    protected ?array $succeedingMandatoryDirectivesForDirectives = null;

    /**
     * @var SplObjectStorage<Directive,array<string|int,EngineIterationFieldSet>>
     */
    private SplObjectStorage $directiveIDFieldSet;
    /**
     * @var SplObjectStorage<FieldInterface,Directive[]>
     */
    private SplObjectStorage $fieldDirectives;
    /**
     * @var array<string,SplObjectStorage<Directive,DirectiveResolverInterface>>
     */
    private array $directiveResolverClassDirectivesCache = [];

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

    public function __construct()
    {
        $this->directiveIDFieldSet = new SplObjectStorage();
        $this->fieldDirectives = new SplObjectStorage();
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
     * @param string|int|array<string|int> $objectIDOrIDs
     * @return string|int|array<string|int>
     */
    public function getQualifiedDBObjectIDOrIDs(string | int | array $objectIDOrIDs): string | int | array
    {
        // Add the type before the ID
        $objectIDs = is_array($objectIDOrIDs) ? $objectIDOrIDs : [$objectIDOrIDs];
        $qualifiedDBObjectIDs = array_map(
            fn (int | string $id) => UnionTypeHelpers::getObjectComposedTypeAndID(
                $this,
                $id
            ),
            $objectIDs
        );
        return is_array($objectIDOrIDs) ? $qualifiedDBObjectIDs : $qualifiedDBObjectIDs[0];
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
     *   3. SerializeLeafOutputTypeValues: to serialize Scalar and Enum Type values
     *
     * Additionally to these 3, we can add other mandatory directives, such as:
     *   - setSelfAsExpression
     *   - cacheControl
     *
     * Because it may be more convenient to add the directive or the class,
     * there are 2 methods.
     *
     * @return Directive[]
     */
    protected function getMandatoryDirectives(): array
    {
        return array_map(
            fn (DirectiveResolverInterface $directiveResolver) => $this->getDirective($directiveResolver->getDirectiveName()),
            $this->getDataloadingEngine()->getMandatoryDirectiveResolvers()
        );
    }

    protected function getDirective(string $directiveName): Directive
    {
        if (!isset($this->directives[$directiveName])) {
            $this->directives[$directiveName] = new Directive(
                $directiveName,
                [],
                LocationHelper::getNonSpecificLocation()
            );
        }
        return $this->directives[$directiveName];
    }

    /**
     * Validate and resolve the directives into an array, each item containing:
     *
     *   1. the directiveResolverInstance
     *   2. its directive
     *   3. the fields it affects
     *
     * @param Directive[] $directives
     * @param SplObjectStorage<Directive,FieldInterface[]> $directiveFields
     * @param array<string,mixed> $variables
     * @return SplObjectStorage<DirectiveResolverInterface,FieldInterface[]>
     */
    public function resolveDirectivesIntoPipelineData(
        array $directives,
        SplObjectStorage $directiveFields,
        array &$variables,
        EngineIterationFeedbackStore $engineIterationFeedbackStore,
    ): SplObjectStorage {
        /**
         * All directives are placed somewhere in the pipeline.
         *
         *   1. At the very beginning
         *   2. Before Validate directive
         *   3. Between the Validate and Resolve directives
         *   4. Between the Resolve and Serialize directives
         *   5. After the Serialize directive
         *   6. At the very end
         */
        $directiveInstancesByPosition = $fieldDirectivesByPosition = $directiveFieldsByPosition = [
            PipelinePositions::BEGINNING => [],
            PipelinePositions::BEFORE_VALIDATE => [],
            PipelinePositions::AFTER_VALIDATE_BEFORE_RESOLVE => [],
            PipelinePositions::AFTER_RESOLVE_BEFORE_SERIALIZE => [],
            PipelinePositions::AFTER_SERIALIZE => [],
            PipelinePositions::END => [],
        ];

        // Resolve from directive into their actual object instance.
        $directiveResolverFields = $this->validateAndResolveDirectiveResolverToFields(
            $directives,
            $directiveFields,
            $variables,
            $engineIterationFeedbackStore,
        );

        // Create an array with the dataFields affected by each directive, in order in which they will be invoked
        foreach ($directiveResolverFields as $directiveResolver) {
            /** @var DirectiveResolverInterface $directiveResolver */
            $fields = $directiveResolverFields[$directiveResolver];
            /** @var FieldInterface[] $fields */
            // Add the directive in its required position in the pipeline, and retrieve what fields it will process
            $pipelinePosition = $directiveResolver->getPipelinePosition();
            $directiveInstancesByPosition[$pipelinePosition][] = $directiveResolver;
            $fieldDirectivesByPosition[$pipelinePosition][] = $directiveResolver->getDirective();
            $directiveFieldsByPosition[$pipelinePosition][] = $fields;
        }

        // Once we have them ordered, we can simply discard the positions, keep only the values
        // Each item has 2 elements: the directiveResolverInstance and the fields it affects
        /** @var SplObjectStorage<DirectiveResolverInterface,FieldInterface[]> */
        $pipelineData = new SplObjectStorage();
        foreach ($directiveInstancesByPosition as $position => $directiveResolverInstances) {
            for ($i = 0; $i < count($directiveResolverInstances); $i++) {
                $pipelineData[$directiveResolverInstances[$i]] = $directiveFieldsByPosition[$position][$i];
            }
        }

        return $pipelineData;
    }

    /**
     * @param Directive[] $directives
     * @param SplObjectStorage<Directive,FieldInterface[]> $directiveFields
     * @param array<string,mixed> $variables
     * @return SplObjectStorage<DirectiveResolverInterface,FieldInterface[]>
     */
    protected function validateAndResolveDirectiveResolverToFields(
        array $directives,
        SplObjectStorage $directiveFields,
        array &$variables,
        EngineIterationFeedbackStore $engineIterationFeedbackStore,
    ): SplObjectStorage {
        /** @var SplObjectStorage<DirectiveResolverInterface,FieldInterface[]> */
        $instances = new SplObjectStorage();
        // Count how many times each directive is added
        /** @var array<string,FieldInterface[]> */
        $directiveFieldTrack = [];
        /** @var SplObjectStorage<DirectiveResolverInterface,FieldInterface[]> */
        $directiveResolverInstanceFields = new SplObjectStorage();
        foreach ($directives as $directive) {
            $fieldDirectiveResolvers = $this->getFieldDirectiveResolvers($directive, $directiveFields[$directive]);
            // If there is no directive with this name, show an error and skip it
            if ($fieldDirectiveResolvers === null) {
                foreach ($directiveFields[$directive] as $field) {
                    $engineIterationFeedbackStore->schemaFeedbackStore->addError(
                        new SchemaFeedback(
                            new FeedbackItemResolution(
                                ErrorFeedbackItemProvider::class,
                                ErrorFeedbackItemProvider::E20,
                                [
                                    $directive->getName(),
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
            $directiveArgs = $directive->getArguments();

            if ($fieldDirectiveResolvers->count() === 0) {
                foreach ($directiveFields[$directive] as $field) {
                    $engineIterationFeedbackStore->schemaFeedbackStore->addError(
                        new SchemaFeedback(
                            new FeedbackItemResolution(
                                ErrorFeedbackItemProvider::class,
                                ErrorFeedbackItemProvider::E21,
                                [
                                    $directive->getName(),
                                    json_encode(array_map(
                                        fn (Argument $argument) => $argument->asQueryString(),
                                        $directiveArgs
                                    )),
                                    implode(
                                        $this->__('\', \'', 'component-model'),
                                        array_map(
                                            fn (FieldInterface $field) => $field->asFieldOutputQueryString(),
                                            $directiveFields[$directive]
                                        )
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

            foreach ($directiveFields[$directive] as $field) {
                $directiveResolverInstance = $fieldDirectiveResolvers[$field] ?? null;
                if ($directiveResolverInstance === null) {
                    $engineIterationFeedbackStore->schemaFeedbackStore->addError(
                        new SchemaFeedback(
                            new FeedbackItemResolution(
                                ErrorFeedbackItemProvider::class,
                                ErrorFeedbackItemProvider::E22,
                                [
                                    $directive->getName(),
                                    json_encode($directiveArgs),
                                    $field->asFieldOutputQueryString(),
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
                $directiveResolverInstanceFieldsSplObjectStorage = $directiveResolverInstanceFields[$directiveResolverInstance] ?? [];
                $directiveResolverInstanceFieldsSplObjectStorage[] = $field;
                $directiveResolverInstanceFields[$directiveResolverInstance] = $directiveResolverInstanceFieldsSplObjectStorage;
            }
        }

        // Validate all the directiveResolvers in the field
        /** @var DirectiveResolverInterface $directiveResolverInstance */
        foreach ($directiveResolverInstanceFields as $directiveResolverInstance) {
            /** @var FieldInterface[] */
            $directiveResolverFields = $directiveResolverInstanceFields[$directiveResolverInstance];
            $directive = $directiveResolverInstance->getDirective();
            $directiveName = $directive->getName();

            // Validate schema (eg of error in schema: ?query=posts<include(if:this-field-doesnt-exist())>)
            $separateEngineIterationFeedbackStore = new EngineIterationFeedbackStore();
            list(
                $validFieldDirective,
                $directiveName,
                $directiveArgs,
            ) = $directiveResolverInstance->dissectAndValidateDirectiveForSchema(
                $this,
                $directiveFields,
                $variables,
                $separateEngineIterationFeedbackStore,
            );
            $engineIterationFeedbackStore->incorporate($separateEngineIterationFeedbackStore);
            if ($separateEngineIterationFeedbackStore->hasErrors()) {
                continue;
            }

            // Validate against the directiveResolver
            if ($maybeErrorFeedbackItemResolutions = $directiveResolverInstance->resolveDirectiveValidationErrors($this, $directive)) {
                foreach ($maybeErrorFeedbackItemResolutions as $errorFeedbackItemResolution) {
                    foreach ($directiveFields[$directive] as $field) {
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
                foreach ($directiveFields[$directive] as $field) {
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
                foreach ($directiveFields[$directive] as $field) {
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

            // Validate if the directive can be executed multiple times on each field
            if (!$directiveResolverInstance->isRepeatable()) {
                // Check if the directive is already processing any of the fields
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
                                        $directive->getName(),
                                        implode(
                                            '\', \'',
                                            array_map(
                                                fn (FieldInterface $field) => $field->asFieldOutputQueryString(),
                                                $alreadyProcessingFields
                                            )
                                        ),
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
            $instances[$directiveResolverInstance] = $directiveResolverFields;
        }
        return $instances;
    }

    /**
     * @param FieldInterface[] $fields
     * @return SplObjectStorage<FieldInterface,DirectiveResolverInterface>|null
     */
    public function getFieldDirectiveResolvers(Directive $directive, array $fields): ?SplObjectStorage
    {
        $directiveName = $directive->getName();
        $directiveNameResolvers = $this->getDirectiveNameResolvers();
        $directiveResolvers = $directiveNameResolvers[$directiveName] ?? null;
        if ($directiveResolvers === null) {
            return null;
        }

        // Only consider the directiveResolvers that can satisfy this directive
        $directiveResolvers = array_filter(
            $directiveResolvers,
            fn (DirectiveResolverInterface $directiveResolver) => $directiveResolver->resolveCanProcess($this, $directive)
        );
        if ($directiveResolvers === []) {
            return null;
        }

        // Calculate directiveResolvers per field
        /** @var SplObjectStorage<FieldInterface,DirectiveResolverInterface> */
        $fieldDirectiveResolvers = new SplObjectStorage();
        foreach ($fields as $field) {
            /**
             * Check that at least one class which deals with this directiveName can satisfy
             * the directive (for instance, validating that a required directiveArg is present)
             */
            $fieldName = $field->getName();
            foreach ($directiveResolvers as $directiveResolver) {
                $directiveSupportedFieldNames = $directiveResolver->getFieldNamesToApplyTo();
                // If this field is not supported by the directive, skip
                if ($directiveSupportedFieldNames && !in_array($fieldName, $directiveSupportedFieldNames)) {
                    continue;
                }
                /**
                 * Create a non-shared directiveResolver instance to handle
                 * this specific $directive object instance.
                 */
                $fieldDirectiveResolvers[$field] = $this->getUniqueDirectiveResolverForDirective(
                    $directiveResolver,
                    $directive,
                );
                // This instance can process the directive, end the loop
                break;
            }
        }
        return $fieldDirectiveResolvers;
    }

    /**
     * The instance from the container is shared. We need a non-shared instance
     * to set the unique $directive. So clone the service.
     */
    protected function getUniqueDirectiveResolverForDirective(
        DirectiveResolverInterface $directiveResolver,
        Directive $directive,
    ): DirectiveResolverInterface {
        $directiveResolverClass = get_class($directiveResolver);
        // Get the instance from the cache if it exists, or create it if not
        if (!isset($this->directiveResolverClassDirectivesCache[$directiveResolverClass]) || !$this->directiveResolverClassDirectivesCache[$directiveResolverClass]->contains($directive)) {
            $uniqueDirectiveResolver = clone $directiveResolver;
            $uniqueDirectiveResolver->setDirective($directive);
            $this->directiveResolverClassDirectivesCache[$directiveResolverClass] ??= new SplObjectStorage();
            $this->directiveResolverClassDirectivesCache[$directiveResolverClass][$directive] = $uniqueDirectiveResolver;
        }
        return $this->directiveResolverClassDirectivesCache[$directiveResolverClass][$directive];
    }

    /**
     * @param array<string|int,EngineIterationFieldSet> $idFieldSet
     * @return mixed[]
     */
    protected function getIDsToQuery(array $idFieldSet): array
    {
        return array_keys($idFieldSet);
    }

    /**
     * @param array<string|int> $objectIDs
     * @return array<string|int>
     */
    protected function getResolvedObjectIDs(array $objectIDs): array
    {
        return $objectIDs;
    }

    /**
     * @param array<string|int,EngineIterationFieldSet> $idFieldSet
     * @param array<string,array<string|int,SplObjectStorage<FieldInterface,mixed>>> $previouslyResolvedIDFieldValues
     * @param array<string|int,SplObjectStorage<FieldInterface,mixed>> $resolvedIDFieldValues
     */
    public function fillObjects(
        array $idFieldSet,
        array $unionTypeOutputKeyIDs,
        array $previouslyResolvedIDFieldValues,
        array &$resolvedIDFieldValues,
        array &$variables,
        array &$messages,
        EngineIterationFeedbackStore $engineIterationFeedbackStore,
    ): array {
        // Obtain the data for the required object IDs
        $idObjects = [];
        $ids = $this->getIDsToQuery($idFieldSet);
        $typeDataLoader = $this->getRelationalTypeDataLoader();
        // If any ID cannot be resolved, the object will be null
        $objects = array_filter($typeDataLoader->getObjects($ids));
        foreach ($objects as $object) {
            $objectID = $this->getID($object);
            // If the UnionTypeResolver doesn't have a TypeResolver to process this element, the ID will be null, and an error will be show below
            if ($objectID === null) {
                continue;
            }
            $idObjects[$objectID] = $object;
            /**
             * If no fields are queried, the entry will be null.
             * Initialize it to [] to simplify typing/null-checking
             */
            $resolvedIDFieldValues[$objectID] ??= new SplObjectStorage();
        }
        // Show an error for all objects that couldn't be processed
        $resolvedObjectIDs = $this->getResolvedObjectIDs(array_keys($idObjects));
        $unresolvedObjectIDs = [];
        $schemaFeedbackStore = $engineIterationFeedbackStore->schemaFeedbackStore;
        foreach (array_diff($ids, $resolvedObjectIDs) as $unresolvedObjectID) {
            // If a UnionTypeResolver fails to load an object, the fields will be NULL
            $failedFields = $idFieldSet[$unresolvedObjectID]->fields ?? [];
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
        // Because these are IDs (eg: 223) and $idFieldSet contains qualified or typed IDs (eg: post/223), we must convert them first
        if ($unresolvedObjectIDs) {
            if ($this->qualifyDBObjectIDsToRemoveFromErrors()) {
                $unresolvedObjectIDs = $this->getQualifiedDBObjectIDOrIDs($unresolvedObjectIDs);
            }
            $idFieldSet = array_filter(
                $idFieldSet,
                fn (int | string $id) => !in_array($id, $unresolvedObjectIDs),
                ARRAY_FILTER_USE_KEY
            );
        }

        // Enqueue the items
        $this->enqueueFillingObjectsFromIDs($idFieldSet);

        // Process them
        $this->processFillingObjectsFromIDs(
            $unionTypeOutputKeyIDs,
            $idObjects,
            $previouslyResolvedIDFieldValues,
            $resolvedIDFieldValues,
            $variables,
            $messages,
            $engineIterationFeedbackStore,
        );

        return $idObjects;
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
     *
     * @param Directive[] $directives
     * @return Directive[]
     */
    protected function addMandatoryDirectivesForDirectives(array $directives): array
    {
        $precedingMandatoryDirectivesForDirectives = $this->getAllPrecedingMandatoryDirectivesForDirectives();
        $succeedingMandatoryDirectivesForDirectives = $this->getAllSucceedingMandatoryDirectivesForDirectives();
        if ($precedingMandatoryDirectivesForDirectives === [] && $succeedingMandatoryDirectivesForDirectives === []) {
            return $directives;
        }

        $allDirectives = [];
        foreach ($directives as $directive) {
            $directiveName = $directive->getName();
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

    /**
     * @return array<string,Directive[]> Key: '*' (for all) or directiveName, Value: List of directives
     */
    protected function getAllPrecedingMandatoryDirectivesForDirectives(): array
    {
        if ($this->precedingMandatoryDirectivesForDirectives === null) {
            $this->precedingMandatoryDirectivesForDirectives = $this->calculateAllPrecedingMandatoryDirectivesForDirectives();
        }
        return $this->precedingMandatoryDirectivesForDirectives;
    }

    /**
     * @return array<string,Directive[]> Key: '*' (for all) or directiveName, Value: List of directives
     */
    protected function calculateAllPrecedingMandatoryDirectivesForDirectives(): array
    {
        $precedingMandatoryDirectivesForDirectives = [];
        $typeResolverDecorators = $this->getAllRelationalTypeResolverDecorators();
        foreach ($typeResolverDecorators as $typeResolverDecorator) {
            /**
             * `array_merge_recursive` so that if 2 different decorators add a directive
             * for the same directive, the results are merged together, not override each other.
             */
            if ($typeResolverDecorator->enabled($this)) {
                $precedingMandatoryDirectivesForDirectives = array_merge_recursive(
                    $precedingMandatoryDirectivesForDirectives,
                    $typeResolverDecorator->getPrecedingMandatoryDirectivesForDirectives($this)
                );
            }
        }

        return $precedingMandatoryDirectivesForDirectives;
    }

    /**
     * @return array<string,Directive[]> Key: '*' (for all) or directiveName, Value: List of directives
     */
    protected function getAllSucceedingMandatoryDirectivesForDirectives(): array
    {
        if ($this->succeedingMandatoryDirectivesForDirectives === null) {
            $this->succeedingMandatoryDirectivesForDirectives = $this->calculateAllSucceedingMandatoryDirectivesForDirectives();
        }
        return $this->succeedingMandatoryDirectivesForDirectives;
    }

    /**
     * @return array<string,Directive[]> Key: '*' (for all) or directiveName, Value: List of directives
     */
    protected function calculateAllSucceedingMandatoryDirectivesForDirectives(): array
    {
        $succeedingMandatoryDirectivesForDirectives = [];
        $typeResolverDecorators = $this->getAllRelationalTypeResolverDecorators();
        foreach ($typeResolverDecorators as $typeResolverDecorator) {
            /**
             * `array_merge_recursive` so that if 2 different decorators add a directive
             * for the same directive, the results are merged together, not override each other.
             */
            if ($typeResolverDecorator->enabled($this)) {
                $succeedingMandatoryDirectivesForDirectives = array_merge_recursive(
                    $succeedingMandatoryDirectivesForDirectives,
                    $typeResolverDecorator->getSucceedingMandatoryDirectivesForDirectives($this)
                );
            }
        }

        return $succeedingMandatoryDirectivesForDirectives;
    }

    /**
     * @return RelationalTypeResolverDecoratorInterface[]
     */
    protected function getAllRelationalTypeResolverDecorators(): array
    {
        if ($this->typeResolverDecorators === null) {
            $this->typeResolverDecorators = $this->calculateAllRelationalTypeResolverDecorators();
        }
        return $this->typeResolverDecorators;
    }

    /**
     * @return RelationalTypeResolverDecoratorInterface[]
     */
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
     *
     * @return FieldInterface[]
     */
    protected function getFieldsToEnqueueFillingObjectsFromIDs(EngineIterationFieldSet $engineIterationFieldSet): array
    {
        /**
         * Watch out: If there are conditional fields,
         * these will be processed by this directive too.
         * Hence, collect all these fields, and add them
         * as if they were direct
         */
        $allConditionalFields = [];
        foreach ($engineIterationFieldSet->conditionalFields as $conditionField) {
            $conditionalFields = $engineIterationFieldSet->conditionalFields[$conditionField];
            $allConditionalFields = array_merge(
                $allConditionalFields,
                $conditionalFields
            );
        }
        return array_unique(array_merge(
            $engineIterationFieldSet->fields,
            $allConditionalFields
        ));
    }

    /**
     * Split function, so it can be invoked both from here and from the UnionTypeResolver
     *
     * @param FieldInterface[] $fields
     * @param array<string,Directive[]> $mandatoryDirectivesForFields Key: '*' (for all) or fieldName, Value: List of Directives
     * @param Directive[] $mandatorySystemDirectives
     */
    protected function doEnqueueFillingObjectsFromIDs(array $fields, array $mandatoryDirectivesForFields, array $mandatorySystemDirectives, string | int $id, EngineIterationFieldSet $fieldSet): void
    {
        foreach ($fields as $field) {
            if (!$this->fieldDirectives->contains($field)) {
                $directives = $field->getDirectives();

                // Add the mandatory directives defined for this field or for any field in this typeResolver
                $fieldName = $field->getName();
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

                $this->fieldDirectives[$field] = $directives;
            }

            // Store which fields do the directives process
            /** @var Directive[] */
            $directives = $this->fieldDirectives[$field];
            foreach ($directives as $directive) {
                $idFieldSet = $this->directiveIDFieldSet[$directive] ?? [];
                $idFieldSet[$id] ??= new EngineIterationFieldSet();
                // Store which ID/field this directive must process
                if (in_array($field, $fieldSet->fields)) {
                    $idFieldSet[$id]->fields[] = $field;
                }
                /** @var FieldInterface[]|null */
                $conditionalFields = $fieldSet->conditionalFields[$field] ?? null;
                if (!($conditionalFields === null || $conditionalFields === [])) {
                    $idFieldSet[$id]->addConditionalFields($field, $conditionalFields);
                }
                $this->directiveIDFieldSet[$directive] = $idFieldSet;
            }
        }
    }

    /**
     * Execute the directive pipeline to resolve the data
     * for all IDs and fields.
     *
     * The data under variable $resolvedIDFieldValues will undergo
     * 2 stages:
     *
     *   1. Resolve the field (for each ID) via ObjectTypeFieldResolvers,
     *      which may produce an object (eg: DateTime for `Post.date`)
     *   2. Serialize the leaf values, to print the response
     *      (via directive SerializeLeafOutputTypeValues, executed at the end of the pipeline)
     *
     * Hence, the type of this variable can change throughout the
     * lifecycle of this script, and its type is then declared as `mixed`.
     *
     * @param array<string,array<string|int,SplObjectStorage<FieldInterface,mixed>>> $previouslyResolvedIDFieldValues
     * @param array<string|int,SplObjectStorage<FieldInterface,mixed>> $resolvedIDFieldValues
     */
    protected function processFillingObjectsFromIDs(
        array $unionTypeOutputKeyIDs,
        array $idObjects,
        array $previouslyResolvedIDFieldValues,
        array &$resolvedIDFieldValues,
        array &$variables,
        array &$messages,
        EngineIterationFeedbackStore $engineIterationFeedbackStore,
    ): void {
        // Iterate while there are directives with data to be processed
        while ($this->directiveIDFieldSet->count() > 0) {
            /**
             * @var SplObjectStorage<Directive,array<string|int,EngineIterationFieldSet>>
             */
            $directiveIDFieldSet = $this->directiveIDFieldSet;
            // Now that we have all data, remove all entries from the inner stack.
            // It may be filled again with composed directives, when resolving the pipeline
            $this->directiveIDFieldSet = new SplObjectStorage();

            $directives = iterator_to_array($directiveIDFieldSet);

            // Calculate all the fields on which the directive will be applied.
            /** @var SplObjectStorage<Directive,FieldInterface[]> */
            $directiveFields = new SplObjectStorage();
            /** @var SplObjectStorage<Directive,SplObjectStorage<FieldInterface,array<string|int>>> */
            $directiveFieldIDs = new SplObjectStorage();
            /** @var FieldInterface[] */
            $directiveDirectFields = [];
            foreach ($directives as $directive) {
                /** @var SplObjectStorage<FieldInterface,array<string|int>> */
                $fieldIDsSplObjectStorage = $directiveFieldIDs[$directive] ?? new SplObjectStorage();
                /** @var FieldInterface[] */
                $fields = [];
                foreach ($directiveIDFieldSet[$directive] as $id => $fieldSet) {
                    $directiveDirectFields = array_merge(
                        $directiveDirectFields,
                        $fieldSet->fields
                    );
                    $conditionalFields = [];
                    foreach ($fieldSet->conditionalFields as $conditionField) {
                        $conditionalFields = array_merge(
                            $conditionalFields,
                            $fieldSet->conditionalFields[$conditionField]
                        );
                    }
                    $idFieldDirectiveIDFields = array_unique(array_merge(
                        $fieldSet->fields,
                        $conditionalFields
                    ));
                    $fields = array_merge(
                        $fields,
                        $idFieldDirectiveIDFields
                    );
                    // Also transpose the array to match field to IDs later on
                    foreach ($idFieldDirectiveIDFields as $field) {
                        $ids = $fieldIDsSplObjectStorage[$field] ?? [];
                        $ids[] = $id;
                        $fieldIDsSplObjectStorage[$field] = $ids;
                    }
                }
                $directiveFieldIDs[$directive] = $fieldIDsSplObjectStorage;
                $directiveFields[$directive] = array_unique($fields);
            }
            $directiveDirectFields = array_unique($directiveDirectFields);

            // Validate and resolve the directives into instances and fields they operate on
            $separateEngineIterationFeedbackStore = new EngineIterationFeedbackStore();
            $directivePipelineData = $this->resolveDirectivesIntoPipelineData(
                $directives,
                $directiveFields,
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
                foreach ($directives as $directive) {
                    foreach ($directiveIDFieldSet[$directive] as $id => $fieldSet) {
                        $resolvedIDFieldValues[$id] ??= new SplObjectStorage();
                        $failingFields = array_intersect(
                            $fieldSet->fields,
                            $schemaErrorFailingFields
                        );
                        foreach ($failingFields as $field) {
                            $resolvedIDFieldValues[$id][$field] = null;
                        }
                    }
                }
            }

            // From the fields, reconstitute the $idFieldSet for each directive,
            // and build the array to pass to the pipeline, for each directive (stage)
            $directiveResolverInstances = [];
            /** @var array<array<string|int,EngineIterationFieldSet>> */
            $pipelineIDFieldSet = [];
            /** @var DirectiveResolverInterface $directiveResolverInstance */
            foreach ($directivePipelineData as $directiveResolverInstance) {
                /** @var FieldInterface[] */
                $directiveFields = $directivePipelineData[$directiveResolverInstance];
                $directive = $directiveResolverInstance->getDirective();
                // Only process the direct fields
                $directiveDirectFieldsToProcess = array_intersect(
                    $directiveFields,
                    $directiveDirectFields
                );
                // Remove those fields which have a failing directive
                $directiveDirectFieldsToProcess = array_diff(
                    $directiveDirectFieldsToProcess,
                    $schemaErrorFailingFields
                );
                // From the fields, reconstitute the $idFieldSet for each directive, and build the array to pass to the pipeline, for each directive (stage)
                /** @var array<string|int,EngineIterationFieldSet> */
                $idFieldSet = [];
                foreach ($directiveDirectFieldsToProcess as $field) {
                    $ids = $directiveFieldIDs[$directive][$field];
                    foreach ($ids as $id) {
                        $idFieldSet[$id] ??= new EngineIterationFieldSet();
                        $idFieldSet[$id]->fields[] = $field;
                        /** @var FieldInterface[]|null */
                        $fieldConditionalFields = $directiveIDFieldSet[$directive][$id]->conditionalFields[$field] ?? null;
                        if ($fieldConditionalFields === null || $fieldConditionalFields === []) {
                            continue;
                        }
                        $idFieldSet[$id]->conditionalFields[$field] = $fieldConditionalFields;
                    }
                }
                $pipelineIDFieldSet[] = $idFieldSet;
                $directiveResolverInstances[] = $directiveResolverInstance;
            }

            // We can finally resolve the pipeline, passing along an array with the ID and fields for each directive
            $directivePipeline = $this->getDirectivePipelineService()->getDirectivePipeline($directiveResolverInstances);
            $directivePipeline->resolveDirectivePipeline(
                $this,
                $pipelineIDFieldSet,
                $directiveResolverInstances,
                $idObjects,
                $unionTypeOutputKeyIDs,
                $previouslyResolvedIDFieldValues,
                $resolvedIDFieldValues,
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
                /**
                 * Add them to the results. We keep the list of all resolvers,
                 * so that if the first one cannot process the directive
                 * (eg: through `resolveCanProcess`, the next one can do it)
                 */
                foreach ($attachedDirectiveResolvers as $directiveResolver) {
                    if (!$directiveResolver->isDirectiveEnabled()) {
                        // Skip disabled directives
                        continue;
                    }
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
