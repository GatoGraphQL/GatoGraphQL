<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolvers;

use PoP\ComponentModel\AttachableExtensions\AttachableExtensionGroups;
use PoP\ComponentModel\Constants\ConfigurationValues;
use PoP\ComponentModel\DirectivePipeline\DirectivePipelineServiceInterface;
use PoP\ComponentModel\DirectiveResolvers\FieldDirectiveResolverInterface;
use PoP\ComponentModel\DirectiveResolvers\ResolveValueAndMergeFieldDirectiveResolver;
use PoP\ComponentModel\DirectiveResolvers\SerializeLeafOutputTypeValuesFieldDirectiveResolver;
use PoP\ComponentModel\DirectiveResolvers\ValidateFieldDirectiveResolver;
use PoP\ComponentModel\Directives\FieldDirectiveBehaviors;
use PoP\ComponentModel\Engine\EngineIterationFieldSet;
use PoP\ComponentModel\FeedbackItemProviders\DeprecationFeedbackItemProvider;
use PoP\ComponentModel\FeedbackItemProviders\ErrorFeedbackItemProvider;
use PoP\ComponentModel\Feedback\EngineIterationFeedbackStore;
use PoP\ComponentModel\Feedback\SchemaFeedback;
use PoP\ComponentModel\QueryResolution\FieldDataAccessProvider;
use PoP\ComponentModel\Registries\MandatoryFieldDirectiveResolverRegistryInterface;
use PoP\ComponentModel\RelationalTypeResolverDecorators\RelationalTypeResolverDecoratorInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeHelpers;
use PoP\GraphQLParser\ASTNodes\ASTNodesFactory;
use PoP\GraphQLParser\FeedbackItemProviders\GraphQLSpecErrorFeedbackItemProvider;
use PoP\GraphQLParser\Spec\Parser\Ast\Directive;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\Root\App;
use PoP\ComponentModel\Feedback\FeedbackItemResolution;
use SplObjectStorage;

abstract class AbstractRelationalTypeResolver extends AbstractTypeResolver implements RelationalTypeResolverInterface
{
    use ExcludeFieldNamesFromSchemaTypeResolverTrait;

    /**
     * @var array<string,Directive>
     */
    protected array $directives = [];

    /**
     * @var array<string,FieldDirectiveResolverInterface[]>|null
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
     * @var array<string,SplObjectStorage<Directive,FieldDirectiveResolverInterface>>
     */
    private array $directiveResolverClassDirectivesCache = [];
    /**
     * @var SplObjectStorage<FieldInterface,array<string,SplObjectStorage<ObjectTypeResolverInterface,SplObjectStorage<object,array<string,mixed>>>|null>>
     */
    private SplObjectStorage $objectTypeResolverObjectFieldDataCache;

    private ?MandatoryFieldDirectiveResolverRegistryInterface $mandatoryFieldDirectiveResolverRegistry = null;
    private ?DirectivePipelineServiceInterface $directivePipelineService = null;

    final public function setMandatoryFieldDirectiveResolverRegistry(MandatoryFieldDirectiveResolverRegistryInterface $mandatoryFieldDirectiveResolverRegistry): void
    {
        $this->mandatoryFieldDirectiveResolverRegistry = $mandatoryFieldDirectiveResolverRegistry;
    }
    final protected function getMandatoryFieldDirectiveResolverRegistry(): MandatoryFieldDirectiveResolverRegistryInterface
    {
        if ($this->mandatoryFieldDirectiveResolverRegistry === null) {
            /** @var MandatoryFieldDirectiveResolverRegistryInterface */
            $mandatoryFieldDirectiveResolverRegistry = $this->instanceManager->getInstance(MandatoryFieldDirectiveResolverRegistryInterface::class);
            $this->mandatoryFieldDirectiveResolverRegistry = $mandatoryFieldDirectiveResolverRegistry;
        }
        return $this->mandatoryFieldDirectiveResolverRegistry;
    }
    final public function setDirectivePipelineService(DirectivePipelineServiceInterface $directivePipelineService): void
    {
        $this->directivePipelineService = $directivePipelineService;
    }
    final protected function getDirectivePipelineService(): DirectivePipelineServiceInterface
    {
        if ($this->directivePipelineService === null) {
            /** @var DirectivePipelineServiceInterface */
            $directivePipelineService = $this->instanceManager->getInstance(DirectivePipelineServiceInterface::class);
            $this->directivePipelineService = $directivePipelineService;
        }
        return $this->directivePipelineService;
    }

    public function __construct()
    {
        $this->directiveIDFieldSet = new SplObjectStorage();
        $this->fieldDirectives = new SplObjectStorage();
        $this->objectTypeResolverObjectFieldDataCache = new SplObjectStorage();
    }

    /**
     * @return array<string,FieldDirectiveResolverInterface[]>
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
    public function getQualifiedDBObjectIDOrIDs(string|int|array $objectIDOrIDs): string|int|array
    {
        // Add the type before the ID
        $objectIDs = is_array($objectIDOrIDs) ? $objectIDOrIDs : [$objectIDOrIDs];
        $qualifiedDBObjectIDs = array_map(
            fn (int|string $id) => UnionTypeHelpers::getObjectComposedTypeAndID(
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
     *   1. ResolveAndMerge: to resolve the field and place the data into the DB object
     *   2. SerializeLeafOutputTypeValues: to serialize Scalar and Enum Type values
     *
     * Additionally to these 2, we can add other mandatory directives, such as:
     *   - setSelfInAppState
     *   - cacheControl
     *
     * Because it may be more convenient to add the directive or the class,
     * there are 2 methods.
     *
     * @return Directive[]
     */
    final protected function getMandatoryDirectives(): array
    {
        return array_map(
            fn (FieldDirectiveResolverInterface $directiveResolver) => $this->getDirective($directiveResolver->getDirectiveName()),
            array_merge(
                $this->getMandatorySystemFieldDirectiveResolvers(),
                $this->getMandatoryFieldOrOperationDirectiveResolvers()
            )
        );
    }

    /**
     * Mandatory system directives
     *
     * @return FieldDirectiveResolverInterface[]
     */
    final protected function getMandatorySystemFieldDirectiveResolvers(): array
    {
        return array_map(
            function (string $directiveResolverClass): FieldDirectiveResolverInterface {
                /** @var FieldDirectiveResolverInterface */
                return $this->instanceManager->getInstance($directiveResolverClass);
            },
            $this->getMandatorySystemFieldDirectiveResolverClasses()
        );
    }

    /**
     * Mandatory system directives
     *
     * @return array<class-string<FieldDirectiveResolverInterface>>
     */
    final protected function getMandatorySystemFieldDirectiveResolverClasses(): array
    {
        return [
            ValidateFieldDirectiveResolver::class,
            ResolveValueAndMergeFieldDirectiveResolver::class,
            SerializeLeafOutputTypeValuesFieldDirectiveResolver::class,
        ];
    }

    /**
     * By default, handle mandatory directives for Fields.
     * This method will be overridden by SuperRoot, to handle
     * mandatory directives for Operations.
     *
     * @return FieldDirectiveResolverInterface[]
     */
    protected function getMandatoryFieldOrOperationDirectiveResolvers(): array
    {
        return $this->getMandatoryFieldDirectiveResolverRegistry()->getMandatoryFieldDirectiveResolvers();
    }

    protected function getDirective(string $directiveName): Directive
    {
        if (!isset($this->directives[$directiveName])) {
            $this->directives[$directiveName] = new Directive(
                $directiveName,
                [],
                ASTNodesFactory::getNonSpecificLocation()
            );
        }
        return $this->directives[$directiveName];
    }

    /**
     * Validate and resolve the directives into an array, each item containing:
     *
     *   1. the DirectiveResolver instance
     *   2. its directive
     *   3. the fields it affects
     *
     * @param Directive[] $directives
     * @param SplObjectStorage<Directive,FieldInterface[]> $directiveFields
     * @return SplObjectStorage<FieldDirectiveResolverInterface,FieldInterface[]>
     */
    public function resolveDirectivesIntoPipelineData(
        array $directives,
        SplObjectStorage $directiveFields,
        EngineIterationFeedbackStore $engineIterationFeedbackStore,
    ): SplObjectStorage {
        /**
         * All directives are placed somewhere in the pipeline.
         *
         *   1. At the very beginning
         *   2. Before the Validate directive
         *   3. Between the Validate and Resolve directives
         *   4. Between the Resolve and Serialize directives
         *   5. After the Serialize directive
         *   6. At the very end
         */
        $directiveResolversByPosition = $fieldDirectivesByPosition = $directiveFieldsByPosition = [
            PipelinePositions::BEGINNING => [],
            PipelinePositions::BEFORE_VALIDATE => [],
            PipelinePositions::AFTER_VALIDATE => [],
            PipelinePositions::BEFORE_RESOLVE => [],
            PipelinePositions::AFTER_RESOLVE => [],
            PipelinePositions::BEFORE_SERIALIZE => [],
            PipelinePositions::AFTER_SERIALIZE => [],
            PipelinePositions::END => [],
        ];

        // Resolve from directive into their actual object instance.
        $directiveResolverFields = $this->validateAndResolveFieldDirectiveResolverToFields(
            $directives,
            $directiveFields,
            $engineIterationFeedbackStore,
        );

        // Create an array with the dataFields affected by each directive, in order in which they will be invoked
        foreach ($directiveResolverFields as $directiveResolver) {
            /** @var FieldDirectiveResolverInterface $directiveResolver */
            $fields = $directiveResolverFields[$directiveResolver];
            /** @var FieldInterface[] $fields */
            // Add the directive in its required position in the pipeline, and retrieve what fields it will process
            $pipelinePosition = $directiveResolver->getPipelinePosition();
            $directiveResolversByPosition[$pipelinePosition][] = $directiveResolver;
            $fieldDirectivesByPosition[$pipelinePosition][] = $directiveResolver->getDirective();
            $directiveFieldsByPosition[$pipelinePosition][] = $fields;
        }

        // Once we have them ordered, we can simply discard the positions, keep only the values
        // Each item has 2 elements: the DirectiveResolver instance and the fields it affects
        /** @var SplObjectStorage<FieldDirectiveResolverInterface,FieldInterface[]> */
        $pipelineData = new SplObjectStorage();
        foreach ($directiveResolversByPosition as $position => $directiveResolvers) {
            for ($i = 0; $i < count($directiveResolvers); $i++) {
                $pipelineData[$directiveResolvers[$i]] = $directiveFieldsByPosition[$position][$i];
            }
        }

        return $pipelineData;
    }

    /**
     * @param Directive[] $directives
     * @param SplObjectStorage<Directive,FieldInterface[]> $directiveFields
     * @return SplObjectStorage<FieldDirectiveResolverInterface,FieldInterface[]>
     */
    protected function validateAndResolveFieldDirectiveResolverToFields(
        array $directives,
        SplObjectStorage $directiveFields,
        EngineIterationFeedbackStore $engineIterationFeedbackStore,
    ): SplObjectStorage {
        /** @var SplObjectStorage<FieldDirectiveResolverInterface,FieldInterface[]> */
        $instances = new SplObjectStorage();
        // Count how many times each directive is added
        /** @var array<string,FieldInterface[]> */
        $directiveFieldTrack = [];
        /** @var SplObjectStorage<FieldDirectiveResolverInterface,FieldInterface[]> */
        $directiveResolverInstanceFields = new SplObjectStorage();
        foreach ($directives as $directive) {
            $fieldDirectiveResolvers = $this->getFieldDirectiveResolvers(
                $directive,
                $directiveFields[$directive],
            );
            // If there is no directive with this name, show an error and skip it
            if ($fieldDirectiveResolvers === null) {
                $fields = $directiveFields[$directive];
                $engineIterationFeedbackStore->schemaFeedbackStore->addError(
                    new SchemaFeedback(
                        new FeedbackItemResolution(
                            GraphQLSpecErrorFeedbackItemProvider::class,
                            GraphQLSpecErrorFeedbackItemProvider::E_5_7_1,
                            [
                                $directive->getName(),
                            ]
                        ),
                        $directive,
                        $this,
                        $fields,
                    )
                );
                continue;
            }
            if ($fieldDirectiveResolvers->count() === 0) {
                $fields = $directiveFields[$directive];
                $engineIterationFeedbackStore->schemaFeedbackStore->addError(
                    new SchemaFeedback(
                        new FeedbackItemResolution(
                            GraphQLSpecErrorFeedbackItemProvider::class,
                            GraphQLSpecErrorFeedbackItemProvider::E_5_7_2,
                            [
                                $directive->getName(),
                            ]
                        ),
                        $directive,
                        $this,
                        $fields,
                    )
                );
                continue;
            }

            foreach ($directiveFields[$directive] as $field) {
                $fieldDirectiveResolver = $fieldDirectiveResolvers[$field] ?? null;
                if (
                    $fieldDirectiveResolver === null
                    || !$this->isFieldDirectiveResolverInRightDirectiveLocation($fieldDirectiveResolver, $field)
                ) {
                    $engineIterationFeedbackStore->schemaFeedbackStore->addError(
                        new SchemaFeedback(
                            new FeedbackItemResolution(
                                GraphQLSpecErrorFeedbackItemProvider::class,
                                GraphQLSpecErrorFeedbackItemProvider::E_5_7_2,
                                [
                                    $directive->getName(),
                                ]
                            ),
                            $directive,
                            $this,
                            [$field],
                        )
                    );
                    continue;
                }

                // Consolidate the same DirectiveResolvers for different fields,
                // as to do the validation only once on each of them
                $directiveResolverFieldsSplObjectStorage = $directiveResolverInstanceFields[$fieldDirectiveResolver] ?? [];
                $directiveResolverFieldsSplObjectStorage[] = $field;
                $directiveResolverInstanceFields[$fieldDirectiveResolver] = $directiveResolverFieldsSplObjectStorage;
            }
        }

        /**
         * Validate all the directiveResolvers in the field.
         */
        /** @var FieldDirectiveResolverInterface $directiveResolver */
        foreach ($directiveResolverInstanceFields as $directiveResolver) {
            /** @var FieldInterface[] */
            $directiveResolverFields = $directiveResolverInstanceFields[$directiveResolver];
            $directiveResolver->prepareDirective(
                $this,
                $directiveResolverFields,
                $engineIterationFeedbackStore,
            );

            /**
             * If the DirectiveResolver has errors, they have just been
             * added to the FeedbackStore, so just skip.
             */
            if ($directiveResolver->hasValidationErrors()) {
                continue;
            }

            $directive = $directiveResolver->getDirective();
            $directiveName = $directive->getName();

            // Check for deprecations
            if ($deprecationMessage = $directiveResolver->getDirectiveDeprecationMessage($this)) {
                $fields = $directiveFields[$directive];
                $engineIterationFeedbackStore->schemaFeedbackStore->addDeprecation(
                    new SchemaFeedback(
                        new FeedbackItemResolution(
                            DeprecationFeedbackItemProvider::class,
                            DeprecationFeedbackItemProvider::D2,
                            [
                                $directiveName,
                                $deprecationMessage,
                            ]
                        ),
                        $directive,
                        $this,
                        $fields,
                    )
                );
            }

            // Validate if the directive can be executed multiple times on each field
            if (!$directiveResolver->isRepeatable()) {
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
                    if ($alreadyProcessingFields !== []) {
                        $engineIterationFeedbackStore->schemaFeedbackStore->addError(
                            new SchemaFeedback(
                                new FeedbackItemResolution(
                                    GraphQLSpecErrorFeedbackItemProvider::class,
                                    GraphQLSpecErrorFeedbackItemProvider::E_5_7_3,
                                    [
                                        $directive->getName(),
                                    ]
                                ),
                                $directive,
                                $this,
                                $alreadyProcessingFields,
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

            // Directive is valid. Add it under its instanceID, which enables to add fields under the same directiveResolver
            $instances[$directiveResolver] = $directiveResolverFields;
        }
        return $instances;
    }

    /**
     * Override by SuperRoot to satisfy for Operation Directives
     */
    protected function isFieldDirectiveResolverInRightDirectiveLocation(
        FieldDirectiveResolverInterface $fieldDirectiveResolver,
        FieldInterface $field
    ): bool {
        return in_array(
            $fieldDirectiveResolver->getFieldDirectiveBehavior(),
            $this->getSupportedDirectiveLocationsByBehavior()
        );
    }

    /**
     * Override by SuperRoot to satisfy for Operation Directives
     *
     * @return string[]
     */
    protected function getSupportedDirectiveLocationsByBehavior(): array
    {
        return [
            FieldDirectiveBehaviors::FIELD,
            FieldDirectiveBehaviors::FIELD_AND_OPERATION,
        ];
    }

    /**
     * @param FieldInterface[] $fields
     * @return SplObjectStorage<FieldInterface,FieldDirectiveResolverInterface>|null
     */
    protected function getFieldDirectiveResolvers(
        Directive $directive,
        array $fields,
    ): ?SplObjectStorage {
        $directiveName = $directive->getName();
        $directiveNameResolvers = $this->getDirectiveNameResolvers();
        $directiveResolvers = $directiveNameResolvers[$directiveName] ?? null;
        if ($directiveResolvers === null) {
            return null;
        }

        // Only consider the directiveResolvers that can satisfy this directive
        $directiveResolvers = array_filter(
            $directiveResolvers,
            fn (FieldDirectiveResolverInterface $directiveResolver) => $directiveResolver->resolveCanProcessDirective($this, $directive)
        );
        if ($directiveResolvers === []) {
            return null;
        }

        /**
         * Calculate directiveResolvers per field
         *
         * @var SplObjectStorage<FieldInterface,FieldDirectiveResolverInterface>
         */
        $fieldDirectiveResolvers = new SplObjectStorage();
        foreach ($fields as $field) {
            /**
             * Check that at least one class which deals with this directiveName can satisfy
             * the directive (for instance, validating that a required directiveArg is present)
             */
            foreach ($directiveResolvers as $directiveResolver) {
                // If this field is not supported by the directive, skip
                if (!$directiveResolver->resolveCanProcessField($this, $field)) {
                    continue;
                }

                /**
                 * Create a non-shared directiveResolver instance to handle
                 * this specific $directive object instance.
                 */
                $fieldDirectiveResolvers[$field] = $this->getUniqueFieldDirectiveResolverForDirective(
                    $directiveResolver,
                    $directive,
                );

                // As this instance can process the directive and the field, we found it, then end the loop
                break;
            }
        }
        return $fieldDirectiveResolvers;
    }

    /**
     * The instance from the container is shared. We need a non-shared instance
     * to set the unique $directive. So clone the service.
     */
    protected function getUniqueFieldDirectiveResolverForDirective(
        FieldDirectiveResolverInterface $directiveResolver,
        Directive $directive,
    ): FieldDirectiveResolverInterface {
        $directiveResolverClass = get_class($directiveResolver);
        // Get the instance from the cache if it exists, or create it if not
        if (!isset($this->directiveResolverClassDirectivesCache[$directiveResolverClass]) || !$this->directiveResolverClassDirectivesCache[$directiveResolverClass]->contains($directive)) {
            $uniqueFieldDirectiveResolver = clone $directiveResolver;
            $uniqueFieldDirectiveResolver->setDirective(
                $directive,
            );
            /**
             * @var SplObjectStorage<Directive,FieldDirectiveResolverInterface>
             */
            $directivesCache = $this->directiveResolverClassDirectivesCache[$directiveResolverClass] ?? new SplObjectStorage();
            $directivesCache[$directive] = $uniqueFieldDirectiveResolver;
            $this->directiveResolverClassDirectivesCache[$directiveResolverClass] = $directivesCache;
        }
        return $this->directiveResolverClassDirectivesCache[$directiveResolverClass][$directive];
    }

    /**
     * @param array<string|int,EngineIterationFieldSet> $idFieldSet
     * @return array<string|int>
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
     * @return array<string|int,object>
     * @param array<string,array<string|int,SplObjectStorage<FieldInterface,array<string|int>>>> $unionTypeOutputKeyIDs
     * @param array<string,mixed> $messages
     */
    public function fillObjects(
        array $idFieldSet,
        array $unionTypeOutputKeyIDs,
        array $previouslyResolvedIDFieldValues,
        array &$resolvedIDFieldValues,
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
        foreach (array_diff($ids, $resolvedObjectIDs) as $unresolvedObjectID) {
            /**
             * If a UnionTypeResolver fails to load an object,
             * the fields will be NULL
             */
            $failedFields = $idFieldSet[$unresolvedObjectID]->fields ?? [];
            $errorFeedbackItemResolution = $this->getUnresolvedObjectIDErrorFeedbackItemResolution($unresolvedObjectID);
            /**
             * Add in $schemaErrors instead of $objectErrors because in the
             * latter one it will attempt to fetch the ID from the object,
             * which it can't do.
             */
            foreach ($failedFields as $failedField) {
                $engineIterationFeedbackStore->schemaFeedbackStore->addError(
                    new SchemaFeedback(
                        $errorFeedbackItemResolution,
                        $failedField,
                        $this,
                        [$failedField],
                    )
                );
            }

            // Indicate that this ID must be removed from the results
            $unresolvedObjectIDs[] = $unresolvedObjectID;
        }
        /**
         * Remove all the IDs that failed from the elements to process,
         * so it doesn't show a "Corrupted Data" error.
         *
         * Because these are IDs (eg: 223) and $idFieldSet contains qualified
         * or typed IDs (eg: post/223), we must convert them first.
         */
        if ($unresolvedObjectIDs) {
            if ($this->qualifyDBObjectIDsToRemoveFromErrors()) {
                /** @var array<string|int> */
                $unresolvedObjectIDs = $this->getQualifiedDBObjectIDOrIDs($unresolvedObjectIDs);
            }
            $idFieldSet = array_filter(
                $idFieldSet,
                fn (int|string $id) => !in_array($id, $unresolvedObjectIDs),
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
            $messages,
            $engineIterationFeedbackStore,
        );

        return $idObjects;
    }

    protected function getUnresolvedObjectIDErrorFeedbackItemResolution(string|int $objectID): FeedbackItemResolution
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
                    $precedingMandatoryDirectivesForDirectives[ConfigurationValues::ANY] ?? [],
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
                    $succeedingMandatoryDirectivesForDirectives[ConfigurationValues::ANY] ?? [],
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
            if (!$typeResolverDecorator->enabled($this)) {
                continue;
            }
            /**
             * `array_merge_recursive` so that if 2 different decorators add a directive
             * for the same directive, the results are merged together, not override each other.
             */
            $precedingMandatoryDirectivesForDirectives = array_merge_recursive(
                $precedingMandatoryDirectivesForDirectives,
                $typeResolverDecorator->getPrecedingMandatoryDirectivesForDirectives($this)
            );
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
            if (!$typeResolverDecorator->enabled($this)) {
                continue;
            }
            /**
             * `array_merge_recursive` so that if 2 different decorators add a directive
             * for the same directive, the results are merged together, not override each other.
             */
            $succeedingMandatoryDirectivesForDirectives = array_merge_recursive(
                $succeedingMandatoryDirectivesForDirectives,
                $typeResolverDecorator->getSucceedingMandatoryDirectivesForDirectives($this)
            );
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
        /**
         * Order them by object hash, as to return a single instance from each
         *
         * @var array<string,RelationalTypeResolverDecoratorInterface>
         */
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
            $relationalTypeResolverDecorators = $this->calculateAllRelationalTypeResolverDecoratorsForRelationalTypeOrInterfaceTypeResolverClass($class);
            foreach ($relationalTypeResolverDecorators as $relationalTypeResolverDecorator) {
                $relationalTypeResolverDecoratorObjectHash = spl_object_hash($relationalTypeResolverDecorator);
                if (isset($typeResolverDecorators[$relationalTypeResolverDecoratorObjectHash])) {
                    continue;
                }
                $typeResolverDecorators[$relationalTypeResolverDecoratorObjectHash] = $relationalTypeResolverDecorator;
            }
        }

        return array_values($typeResolverDecorators);
    }

    /**
     * @return RelationalTypeResolverDecoratorInterface[]
     */
    protected function calculateAllRelationalTypeResolverDecoratorsForRelationalTypeOrInterfaceTypeResolverClass(string $class): array
    {
        /**
         * Order them by object hash, as to return a single instance from each
         *
         * @var array<string,RelationalTypeResolverDecoratorInterface>
         */
        $typeResolverDecorators = [];

        $attachableExtensionManager = $this->getAttachableExtensionManager();

        // Iterate classes from the current class towards the parent classes until finding typeResolver that satisfies processing this field
        do {
            // Important: do array_reverse to enable more specific hooks, which are initialized later on in the project, to be the chosen ones (if their priority is the same)
            /** @var RelationalTypeResolverDecoratorInterface[] */
            $attachedRelationalTypeResolverDecorators = array_reverse($attachableExtensionManager->getAttachedExtensions($class, AttachableExtensionGroups::RELATIONAL_TYPE_RESOLVER_DECORATORS));
            // Order them by priority: higher priority are evaluated first
            $extensionPriorities = array_map(
                fn (RelationalTypeResolverDecoratorInterface $typeResolverDecorator) => $typeResolverDecorator->getPriorityToAttachToClasses(),
                $attachedRelationalTypeResolverDecorators
            );
            array_multisort($extensionPriorities, SORT_DESC, SORT_NUMERIC, $attachedRelationalTypeResolverDecorators);
            /**
             * Add them to the results.
             *
             * A class and its parent class could be attached the
             * same decorator, then do array_unique
             */
            foreach ($attachedRelationalTypeResolverDecorators as $attachedRelationalTypeResolverDecorator) {
                $attachedRelationalTypeResolverDecoratorObjectHash = spl_object_hash($attachedRelationalTypeResolverDecorator);
                if (isset($typeResolverDecorators[$attachedRelationalTypeResolverDecoratorObjectHash])) {
                    continue;
                }
                $typeResolverDecorators[$attachedRelationalTypeResolverDecoratorObjectHash] = $attachedRelationalTypeResolverDecorator;
            }

            // Continue iterating for the class parents
        } while ($class = get_parent_class($class));

        return array_values($typeResolverDecorators);
    }

    /**
     * Execute a hook to allow to disable directives (eg: to implement a private schema)
     *
     * @param array<string,FieldDirectiveResolverInterface[]> $directiveNameResolvers
     * @return array<string,FieldDirectiveResolverInterface[]> $directiveNameResolvers
     */
    protected function filterDirectiveNameResolvers(array $directiveNameResolvers): array
    {
        // Execute a hook, allowing to filter them out (eg: removing fieldNames from a private schema)
        return array_filter(
            $directiveNameResolvers,
            function (string $directiveName) use ($directiveNameResolvers) {
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
    protected function doEnqueueFillingObjectsFromIDs(array $fields, array $mandatoryDirectivesForFields, array $mandatorySystemDirectives, string|int $id, EngineIterationFieldSet $fieldSet): void
    {
        foreach ($fields as $field) {
            if (!$this->fieldDirectives->contains($field)) {
                $directives = $field->getDirectives();

                // Add the mandatory directives defined for this field or for any field in this typeResolver
                $fieldName = $field->getName();
                if (
                    $mandatoryDirectivesForField = array_merge(
                        $mandatoryDirectivesForFields[ConfigurationValues::ANY] ?? [],
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
     * @param array<string,array<string|int,SplObjectStorage<FieldInterface,array<string|int>>>> $unionTypeOutputKeyIDs
     * @param array<string|int,object> $idObjects
     * @param array<string,mixed> $messages
     */
    protected function processFillingObjectsFromIDs(
        array $unionTypeOutputKeyIDs,
        array $idObjects,
        array $previouslyResolvedIDFieldValues,
        array &$resolvedIDFieldValues,
        array &$messages,
        EngineIterationFeedbackStore $engineIterationFeedbackStore,
    ): void {
        $directivePipelineService = $this->getDirectivePipelineService();
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
                $separateEngineIterationFeedbackStore,
            );
            $engineIterationFeedbackStore->incorporate($separateEngineIterationFeedbackStore);

            // If any directive failed validation and the field must be set to `null`,
            // then skip processing that field altogether
            /** @var array<string|int,FieldInterface[]> */
            $errorIDFields = [];
            if ($separateEngineIterationFeedbackStore->objectResolutionFeedbackStore->getErrors() !== []) {
                foreach ($separateEngineIterationFeedbackStore->objectResolutionFeedbackStore->getErrors() as $objectResolutionFeedback) {
                    foreach ($objectResolutionFeedback->getIDFieldSet() as $id => $fieldSet) {
                        $errorIDFields[$id] = array_merge(
                            $errorIDFields[$id] ?? [],
                            $fieldSet->fields
                        );
                    }
                }
            }
            if ($separateEngineIterationFeedbackStore->schemaFeedbackStore->getErrors() !== []) {
                // Extract the failing fields from the errors
                $schemaErrorFailingFields = [];
                foreach ($separateEngineIterationFeedbackStore->schemaFeedbackStore->getErrors() as $schemaFeedback) {
                    $schemaErrorFailingFields = array_merge(
                        $schemaErrorFailingFields,
                        $schemaFeedback->getFields()
                    );
                }
                $schemaErrorFailingFields = array_unique($schemaErrorFailingFields);
                // Set those fields as null
                foreach ($directives as $directive) {
                    foreach ($directiveIDFieldSet[$directive] as $id => $fieldSet) {
                        $failingFields = array_intersect(
                            $fieldSet->fields,
                            $schemaErrorFailingFields
                        );
                        $errorIDFields[$id] = array_merge(
                            $errorIDFields[$id] ?? [],
                            $failingFields
                        );
                    }
                }
            }

            // From the fields, reconstitute the $idFieldSet for each directive,
            // and build the array to pass to the pipeline, for each directive (stage)
            $directiveResolvers = [];
            /** @var array<array<string|int,EngineIterationFieldSet>> */
            $pipelineIDFieldSet = [];
            /**
             * For each of the elements in the pipeline, convert the FieldArgs
             * into its corresponding FieldDataAccessor, which integrates
             * within the default values and coerces them according to the schema.
             *
             * This object is provided via a FieldDataAccessProvider, which can handle
             * 3 different cases:
             *
             *   1. Data from a Field in an ObjectTypeResolver
             *   2. Data from a Field in an UnionTypeResolver
             *   3. Data for a specific object (eg: for nested mutations)
             *
             * @see FieldDataAccessProvider
             *
             * @var FieldDataAccessProvider[]
             */
            $pipelineFieldDataAccessProviders = [];
            /** @var FieldDirectiveResolverInterface $directiveResolver */
            foreach ($directivePipelineData as $directiveResolver) {
                /** @var FieldInterface[] */
                $directiveFields = $directivePipelineData[$directiveResolver];
                $directive = $directiveResolver->getDirective();

                // Only process the direct fields
                $directiveDirectFieldsToProcess = array_intersect(
                    $directiveFields,
                    $directiveDirectFields
                );

                $directiveResolvers[] = $directiveResolver;

                /**
                 * Generate the Field Data, and for those Fields that produced
                 * some error (which are the ones not present in the SplObjectStorage),
                 * remove them already from the pipeline.
                 */
                $fieldObjectTypeResolverObjectFieldData = $this->getFieldObjectTypeResolverObjectFieldData(
                    $directiveDirectFieldsToProcess,
                    $directiveFieldIDs[$directive],
                    $idObjects,
                    $engineIterationFeedbackStore,
                );
                $pipelineFieldDataAccessProviders[] = new FieldDataAccessProvider($fieldObjectTypeResolverObjectFieldData);

                /**
                 * From the fields, reconstitute the $idFieldSet for each directive,
                 * and build the array to pass to the pipeline, for each directive (stage)
                 *
                 * @var array<string|int,EngineIterationFieldSet>
                 */
                $idFieldSet = [];
                foreach ($directiveDirectFieldsToProcess as $field) {
                    $ids = $directiveFieldIDs[$directive][$field];
                    // Skip fields that already produced some error
                    if (!$fieldObjectTypeResolverObjectFieldData->contains($field)) {
                        foreach ($ids as $id) {
                            $errorIDFields[$id][] = $field;
                        }
                        continue;
                    }
                    foreach ($ids as $id) {
                        // If the $id/$field had an error, skip
                        if (isset($errorIDFields[$id]) && in_array($field, $errorIDFields[$id])) {
                            continue;
                        }
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
            }

            /**
             * All ID/Fields with error, set them in null in the response
             */
            foreach ($errorIDFields as $id => $fields) {
                $resolvedIDFieldValues[$id] ??= new SplObjectStorage();
                foreach ($fields as $field) {
                    $resolvedIDFieldValues[$id][$field] = null;
                }
            }

            // We can finally resolve the pipeline, passing along an array with the ID and fields for each directive
            $directivePipeline = $directivePipelineService->getDirectivePipeline($directiveResolvers);
            $directivePipeline->resolveDirectivePipeline(
                $this,
                $pipelineIDFieldSet,
                $pipelineFieldDataAccessProviders,
                $directiveResolvers,
                $idObjects,
                $unionTypeOutputKeyIDs,
                $previouslyResolvedIDFieldValues,
                $resolvedIDFieldValues,
                $messages,
                $engineIterationFeedbackStore,
            );
        }
    }

    /**
     * Convert the FieldArgs into its corresponding FieldDataAccessor, which integrates
     * within the default values and coerces them according to the schema.
     *
     * @see FieldDataAccessProvider
     *
     * @param FieldInterface[] $fields
     * @param SplObjectStorage<FieldInterface,array<string|int>> $fieldIDs
     * @param array<string|int,object> $idObjects
     * @return SplObjectStorage<FieldInterface,SplObjectStorage<ObjectTypeResolverInterface,SplObjectStorage<object,array<string,mixed>>>>
     */
    protected function getFieldObjectTypeResolverObjectFieldData(
        array $fields,
        SplObjectStorage $fieldIDs,
        array $idObjects,
        EngineIterationFeedbackStore $engineIterationFeedbackStore,
    ): SplObjectStorage {
        /** @var SplObjectStorage<FieldInterface,SplObjectStorage<ObjectTypeResolverInterface,SplObjectStorage<object,array<string,mixed>>>> */
        $fieldObjectTypeResolverObjectFieldData = new SplObjectStorage();

        foreach ($fields as $field) {
            $objectTypeResolverObjectFieldData = $this->getObjectTypeResolverObjectFieldData(
                $field,
                $fieldIDs[$field],
                $idObjects,
                $engineIterationFeedbackStore,
            );
            /**
             * If the field does not exist in the schema, then skip it.
             *
             * @see function doGetObjectTypeResolverObjectFieldData
             */
            if ($objectTypeResolverObjectFieldData === null) {
                continue;
            }
            $fieldObjectTypeResolverObjectFieldData[$field] = $objectTypeResolverObjectFieldData;
        }

        return $fieldObjectTypeResolverObjectFieldData;
    }

    /**
     * Convert the FieldArgs into its corresponding FieldDataAccessor, which integrates
     * within the default values and coerces them according to the schema.
     *
     * Attempt to get the value from the cache first, as the same field, with the same
     * set of IDs, will be called multiple times for the several directives processing
     * them (@resolveValueAndMerge, @serialize, etc)
     *
     * @see FieldDataAccessProvider
     *
     * @param array<string|int> $ids
     * @param array<string|int,object> $idObjects
     * @return SplObjectStorage<ObjectTypeResolverInterface,SplObjectStorage<object,array<string,mixed>>>|null
     */
    protected function getObjectTypeResolverObjectFieldData(
        FieldInterface $field,
        array $ids,
        array $idObjects,
        EngineIterationFeedbackStore $engineIterationFeedbackStore,
    ): ?SplObjectStorage {
        $cacheKey = implode('|', $ids);
        if (
            $this->objectTypeResolverObjectFieldDataCache->contains($field)
            // The cached value can be `null` (in case of error), so can't use `isset`
            && array_key_exists($cacheKey, $this->objectTypeResolverObjectFieldDataCache[$field])
        ) {
            return $this->objectTypeResolverObjectFieldDataCache[$field][$cacheKey];
        }
        $objectTypeResolverObjectFieldData = $this->doGetObjectTypeResolverObjectFieldData(
            $field,
            $ids,
            $idObjects,
            $engineIterationFeedbackStore,
        );
        $fieldObjectTypeResolverObjectFieldDataCache = $this->objectTypeResolverObjectFieldDataCache[$field] ?? [];
        $fieldObjectTypeResolverObjectFieldDataCache[$cacheKey] = $objectTypeResolverObjectFieldData;
        $this->objectTypeResolverObjectFieldDataCache[$field] = $fieldObjectTypeResolverObjectFieldDataCache;
        return $objectTypeResolverObjectFieldData;
    }

    /**
     * Convert the FieldArgs into its corresponding FieldDataAccessor, which integrates
     * within the default values and coerces them according to the schema.
     *
     * This object is provided via a FieldDataAccessProvider, which can handle
     * 3 different cases:
     *
     *   1. Data from a Field in an ObjectTypeResolver
     *   2. Data from a Field in an UnionTypeResolver
     *   3. Data for a specific object (eg: for nested mutations)
     *
     * The format of the response of this function is defined in class FieldDataAccessProvider
     *
     * @see FieldDataAccessProvider
     *
     * @param array<string|int> $ids
     * @param array<string|int,object> $idObjects
     * @return SplObjectStorage<ObjectTypeResolverInterface,SplObjectStorage<object,array<string,mixed>>>|null
     */
    abstract protected function doGetObjectTypeResolverObjectFieldData(
        FieldInterface $field,
        array $ids,
        array $idObjects,
        EngineIterationFeedbackStore $engineIterationFeedbackStore,
    ): ?SplObjectStorage;

    /**
     * @return array<string,FieldDirectiveResolverInterface>
     */
    public function getSchemaFieldDirectiveResolvers(bool $global): array
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

    /**
     * @return array<string,FieldDirectiveResolverInterface[]>
     */
    protected function calculateFieldDirectiveNameResolvers(): array
    {
        $directiveNameResolvers = [];

        $attachableExtensionManager = $this->getAttachableExtensionManager();

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
                /** @var FieldDirectiveResolverInterface[] */
                $attachedFieldDirectiveResolvers = array_reverse($attachableExtensionManager->getAttachedExtensions($class, AttachableExtensionGroups::DIRECTIVE_RESOLVERS));
                // Order them by priority: higher priority are evaluated first
                $extensionPriorities = array_map(
                    fn (FieldDirectiveResolverInterface $directiveResolver) => $directiveResolver->getPriorityToAttachToClasses(),
                    $attachedFieldDirectiveResolvers
                );
                array_multisort($extensionPriorities, SORT_DESC, SORT_NUMERIC, $attachedFieldDirectiveResolvers);
                /**
                 * Add them to the results. We keep the list of all resolvers,
                 * so that if the first one cannot process the directive
                 * (eg: through `resolveCanProcessDirective`, the next one can do it)
                 */
                foreach ($attachedFieldDirectiveResolvers as $directiveResolver) {
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
