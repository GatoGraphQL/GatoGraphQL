<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolvers\ObjectType;

use Exception;
use PoP\ComponentModel\App;
use PoP\ComponentModel\AttachableExtensions\AttachableExtensionGroups;
use PoP\ComponentModel\Checkpoints\CheckpointInterface;
use PoP\ComponentModel\Checkpoints\EnabledMutationsCheckpoint;
use PoP\ComponentModel\Engine\EngineInterface;
use PoP\ComponentModel\Engine\EngineIterationFieldSet;
use PoP\ComponentModel\Environment;
use PoP\ComponentModel\Feedback\EngineIterationFeedbackStore;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedback;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\Feedback\SchemaFeedback;
use PoP\ComponentModel\FeedbackItemProviders\ErrorFeedbackItemProvider;
use PoP\ComponentModel\FeedbackItemProviders\FieldResolutionErrorFeedbackItemProvider;
use PoP\ComponentModel\FieldResolvers\InterfaceType\InterfaceTypeFieldResolverInterface;
use PoP\ComponentModel\FieldResolvers\ObjectType\ObjectTypeFieldResolverInterface;
use PoP\ComponentModel\Module;
use PoP\ComponentModel\ModuleConfiguration;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP\ComponentModel\ObjectSerialization\ObjectSerializationManagerInterface;
use PoP\ComponentModel\QueryResolution\FieldDataAccessor;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\QueryResolution\FieldDataAccessWildcardObjectFactory;
use PoP\ComponentModel\QueryResolution\InputObjectSubpropertyFieldDataAccessor;
use PoP\ComponentModel\Resolvers\ObjectTypeOrDirectiveResolverTrait;
use PoP\ComponentModel\Response\OutputServiceInterface;
use PoP\ComponentModel\Schema\SchemaCastingServiceInterface;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\AbstractRelationalTypeResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InterfaceType\InterfaceTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\DangerouslyNonSpecificScalarTypeScalarTypeResolver;
use PoP\GraphQLParser\Exception\AbstractDeferredValuePromiseException;
use PoP\GraphQLParser\Spec\Parser\Ast\Directive;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\LeafField;
use PoP\GraphQLParser\StaticHelpers\LocationHelper;
use PoP\Root\Exception\AbstractClientException;
use PoP\Root\Feedback\FeedbackItemResolution;
use SplObjectStorage;
use stdClass;

abstract class AbstractObjectTypeResolver extends AbstractRelationalTypeResolver implements ObjectTypeResolverInterface
{
    use ObjectTypeOrDirectiveResolverTrait;

    /**
     * Cache of which objectTypeFieldResolvers will process the given field
     *
     * @var array<string,ObjectTypeFieldResolverInterface[]>
     */
    protected array $objectTypeFieldResolversForFieldOrFieldNameCache = [];
    /**
     * @var array<string,Directive[]>|null
     */
    protected ?array $mandatoryDirectivesForFields = null;
    /**
     * @var array<string,ObjectTypeFieldResolverInterface[]>|null
     */
    protected ?array $allObjectTypeFieldResolversByFieldCache = null;
    /**
     * @var array<string,array<string,ObjectTypeFieldResolverInterface[]>>
     */
    protected array $objectTypeFieldResolversByFieldCache = [];
    /**
     * @var array<string,array<string,ObjectTypeFieldResolverInterface>>
     */
    protected array $executableObjectTypeFieldResolversByFieldCache = [];
    /**
     * @var InterfaceTypeResolverInterface[]|null
     */
    protected ?array $implementedInterfaceTypeResolversCache = null;
    /**
     * @var array<string,array>
     */
    private array $fieldNamesResolvedByObjectTypeFieldResolver = [];
    /**
     * @var InterfaceTypeFieldResolverInterface[]|null
     */
    protected ?array $implementedInterfaceTypeFieldResolversCache = null;
    /**
     * @var SplObjectStorage<FieldInterface,array<string,mixed>|null>
     */
    protected SplObjectStorage $fieldDataCache;
    /**
     * @var SplObjectStorage<FieldDataAccessorInterface,FieldDataAccessorInterface>
     */
    protected SplObjectStorage $fieldDataAccessorForMutationCache;

    /**
     * @var SplObjectStorage<FieldInterface,SplObjectStorage<ObjectTypeResolverInterface,SplObjectStorage<object,array<string,mixed>>>|null>
     */
    protected SplObjectStorage $fieldObjectTypeResolverObjectFieldDataCache;

    private ?DangerouslyNonSpecificScalarTypeScalarTypeResolver $dangerouslyNonSpecificScalarTypeScalarTypeResolver = null;
    private ?OutputServiceInterface $outputService = null;
    private ?ObjectSerializationManagerInterface $objectSerializationManager = null;
    private ?SchemaCastingServiceInterface $schemaCastingService = null;
    private ?EngineInterface $engine = null;
    private ?EnabledMutationsCheckpoint $enabledMutationsCheckpoint = null;

    final public function setDangerouslyNonSpecificScalarTypeScalarTypeResolver(DangerouslyNonSpecificScalarTypeScalarTypeResolver $dangerouslyNonSpecificScalarTypeScalarTypeResolver): void
    {
        $this->dangerouslyNonSpecificScalarTypeScalarTypeResolver = $dangerouslyNonSpecificScalarTypeScalarTypeResolver;
    }
    final protected function getDangerouslyNonSpecificScalarTypeScalarTypeResolver(): DangerouslyNonSpecificScalarTypeScalarTypeResolver
    {
        return $this->dangerouslyNonSpecificScalarTypeScalarTypeResolver ??= $this->instanceManager->getInstance(DangerouslyNonSpecificScalarTypeScalarTypeResolver::class);
    }
    final public function setOutputService(OutputServiceInterface $outputService): void
    {
        $this->outputService = $outputService;
    }
    final protected function getOutputService(): OutputServiceInterface
    {
        return $this->outputService ??= $this->instanceManager->getInstance(OutputServiceInterface::class);
    }
    final public function setObjectSerializationManager(ObjectSerializationManagerInterface $objectSerializationManager): void
    {
        $this->objectSerializationManager = $objectSerializationManager;
    }
    final protected function getObjectSerializationManager(): ObjectSerializationManagerInterface
    {
        return $this->objectSerializationManager ??= $this->instanceManager->getInstance(ObjectSerializationManagerInterface::class);
    }
    final public function setSchemaCastingService(SchemaCastingServiceInterface $schemaCastingService): void
    {
        $this->schemaCastingService = $schemaCastingService;
    }
    final protected function getSchemaCastingService(): SchemaCastingServiceInterface
    {
        return $this->schemaCastingService ??= $this->instanceManager->getInstance(SchemaCastingServiceInterface::class);
    }
    final public function setEngine(EngineInterface $engine): void
    {
        $this->engine = $engine;
    }
    final protected function getEngine(): EngineInterface
    {
        return $this->engine ??= $this->instanceManager->getInstance(EngineInterface::class);
    }
    final public function setEnabledMutationsCheckpoint(EnabledMutationsCheckpoint $enabledMutationsCheckpoint): void
    {
        $this->enabledMutationsCheckpoint = $enabledMutationsCheckpoint;
    }
    final protected function getEnabledMutationsCheckpoint(): EnabledMutationsCheckpoint
    {
        return $this->enabledMutationsCheckpoint ??= $this->instanceManager->getInstance(EnabledMutationsCheckpoint::class);
    }

    public function __construct()
    {
        $this->fieldDataCache = new SplObjectStorage();
        $this->fieldObjectTypeResolverObjectFieldDataCache = new SplObjectStorage();
        $this->fieldDataAccessorForMutationCache = new SplObjectStorage();
        parent::__construct();
    }

    /**
     * Watch out! This function will be overridden for the UnionTypeResolver
     *
     * Collect all directives for all fields, and then build a single directive pipeline for all fields,
     * including all directives, even if they don't apply to all fields
     * Eg: id|title<skip>|excerpt<translate> will produce a pipeline [Skip, Translate] where they apply
     * to different fields. After producing the pipeline, add the mandatory items
     *
     * @param array<string|int,EngineIterationFieldSet> $idFieldSet
     */
    final public function enqueueFillingObjectsFromIDs(array $idFieldSet): void
    {
        $mandatoryDirectivesForFields = $this->getAllMandatoryDirectivesForFields();
        $mandatorySystemDirectives = $this->getMandatoryDirectives();
        foreach ($idFieldSet as $id => $fieldSet) {
            $fields = $this->getFieldsToEnqueueFillingObjectsFromIDs($fieldSet);
            $this->doEnqueueFillingObjectsFromIDs($fields, $mandatoryDirectivesForFields, $mandatorySystemDirectives, $id, $fieldSet);
        }
    }

    /**
     * @return array<string,Directive[]> Key: '*' (for all) or fieldName, Value: List of directives
     */
    final public function getAllMandatoryDirectivesForFields(): array
    {
        if ($this->mandatoryDirectivesForFields === null) {
            $this->mandatoryDirectivesForFields = $this->calculateAllMandatoryDirectivesForFields();
        }
        return $this->mandatoryDirectivesForFields;
    }

    /**
     * @return array<string,Directive[]> Key: '*' (for all) or fieldName, Value: List of directives
     */
    private function calculateAllMandatoryDirectivesForFields(): array
    {
        $mandatoryDirectivesForFields = [];
        $typeResolverDecorators = $this->getAllRelationalTypeResolverDecorators();
        foreach ($typeResolverDecorators as $typeResolverDecorator) {
            /**
             * `array_merge_recursive` so that if 2 different decorators add a directive
             * for the same field, the results are merged together, not override each other.
             */
            if ($typeResolverDecorator->enabled($this)) {
                $mandatoryDirectivesForFields = array_merge_recursive(
                    $mandatoryDirectivesForFields,
                    $typeResolverDecorator->getMandatoryDirectivesForFields($this)
                );
            }
        }
        return $mandatoryDirectivesForFields;
    }

    /**
     * @return array<string,mixed>|null `null` if there are no objectTypeFieldResolvers for the field
     */
    final public function getFieldSchemaDefinition(FieldInterface $field): ?array
    {
        $executableObjectTypeFieldResolver = $this->getExecutableObjectTypeFieldResolverForField($field);
        if ($executableObjectTypeFieldResolver === null) {
            return null;
        }

        return $executableObjectTypeFieldResolver->getFieldSchemaDefinition($this, $field->getName());
    }

    final public function collectFieldValidationWarnings(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        $executableObjectTypeFieldResolver = $this->getExecutableObjectTypeFieldResolverForField($fieldDataAccessor->getField());
        if ($executableObjectTypeFieldResolver === null) {
            return;
        }

        if ($maybeWarningFeedbackItemResolutions = $executableObjectTypeFieldResolver->resolveFieldValidationWarnings($this, $fieldDataAccessor)) {
            foreach ($maybeWarningFeedbackItemResolutions as $warningFeedbackItemResolution) {
                $objectTypeFieldResolutionFeedbackStore->addWarning(
                    new ObjectTypeFieldResolutionFeedback(
                        $warningFeedbackItemResolution,
                        $fieldDataAccessor->getField(),
                    )
                );
            }
        }
    }

    final public function collectFieldDeprecations(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        $executableObjectTypeFieldResolver = $this->getExecutableObjectTypeFieldResolverForField($fieldDataAccessor->getField());
        if ($executableObjectTypeFieldResolver === null) {
            return;
        }
    }

    final public function getFieldTypeResolver(
        FieldInterface $field,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): ?ConcreteTypeResolverInterface {
        $executableObjectTypeFieldResolver = $this->getExecutableObjectTypeFieldResolverForField($field);
        if ($executableObjectTypeFieldResolver === null) {
            return null;
        }

        return $executableObjectTypeFieldResolver->getFieldTypeResolver($this, $field->getName());
    }

    final public function getFieldTypeModifiers(
        FieldInterface $field,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): ?int {
        $executableObjectTypeFieldResolver = $this->getExecutableObjectTypeFieldResolverForField($field);
        if ($executableObjectTypeFieldResolver === null) {
            return null;
        }

        return $executableObjectTypeFieldResolver->getFieldTypeModifiers($this, $field->getName());
    }

    final public function getFieldMutationResolver(
        FieldInterface $field,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): ?MutationResolverInterface {
        $executableObjectTypeFieldResolver = $this->getExecutableObjectTypeFieldResolverForField($field);
        if ($executableObjectTypeFieldResolver === null) {
            return null;
        }

        return $executableObjectTypeFieldResolver->getFieldMutationResolver($this, $field->getName());
    }

    final public function isFieldAMutation(FieldInterface|string $fieldOrFieldName): ?bool
    {
        $executableObjectTypeFieldResolver = $this->getExecutableObjectTypeFieldResolverForField($fieldOrFieldName);
        if ($executableObjectTypeFieldResolver === null) {
            return null;
        }

        if ($fieldOrFieldName instanceof FieldInterface) {
            $field = $fieldOrFieldName;
            $fieldName = $field->getName();
        } else {
            $fieldName = $fieldOrFieldName;
        }

        $fieldMutationResolver = $executableObjectTypeFieldResolver->getFieldMutationResolver($this, $fieldName);
        return $fieldMutationResolver !== null;
    }

    /**
     * When coming from @resolveAndMerge, we will receive a
     * FieldDataAccessor with the fieldData already normalized.
     *
     * If executed within a FieldResolver we will (most likely)
     * receive a Field, and we can assume there's no need to
     * normalize the values, they will be coded/provided as required.
     *
     * @param array<string,mixed> $options
     */
    final public function resolveValue(
        object $object,
        FieldInterface|FieldDataAccessorInterface $fieldOrFieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
        array $options = [],
    ): mixed {
        $engineState = App::getEngineState();
        if ($fieldOrFieldDataAccessor instanceof FieldDataAccessorInterface) {
            /** @var FieldDataAccessorInterface */
            $fieldDataAccessor = $fieldOrFieldDataAccessor;
            $field = $fieldDataAccessor->getField();
        } else {
            /** @var FieldInterface */
            $field = $fieldOrFieldDataAccessor;
        }
        if (!$engineState->hasObjectTypeResolvedValue($this, $object, $field)) {
            $value = $this->doResolveValue(
                $object,
                $fieldOrFieldDataAccessor,
                $objectTypeFieldResolutionFeedbackStore,
                $options,
            );
            $engineState->setObjectTypeResolvedValue($this, $object, $field, $value);
        }
        return $engineState->getObjectTypeResolvedValue($this, $object, $field);
    }

    /**
     * @param array<string,mixed> $options
     */
    final protected function doResolveValue(
        object $object,
        FieldInterface|FieldDataAccessorInterface $fieldOrFieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
        array $options = [],
    ): mixed {
        $fieldDataAccessor = null;
        $isFieldDataAccessor = $fieldOrFieldDataAccessor instanceof FieldDataAccessorInterface;
        if ($isFieldDataAccessor) {
            /** @var FieldDataAccessorInterface */
            $fieldDataAccessor = $fieldOrFieldDataAccessor;
            $field = $fieldDataAccessor->getField();
        } else {
            /** @var FieldInterface */
            $field = $fieldOrFieldDataAccessor;
        }
        $objectTypeFieldResolver = $this->getExecutableObjectTypeFieldResolverForField($field);
        if ($objectTypeFieldResolver === null) {
            /**
             * Return an error to indicate that no fieldResolver processes this field,
             * which is different than returning a null value.
             * Needed for compatibility with CustomPostUnionTypeResolver
             * (so that data-fields aimed for another post_type are not retrieved)
             */
            $objectTypeFieldResolutionFeedbackStore->addError(
                new ObjectTypeFieldResolutionFeedback(
                    new FeedbackItemResolution(
                        FieldResolutionErrorFeedbackItemProvider::class,
                        FieldResolutionErrorFeedbackItemProvider::E1,
                        [
                            $field->getName(),
                            $this->getMaybeNamespacedTypeName(),
                            $this->getID($object),
                        ]
                    ),
                    $field,
                )
            );
            return null;
        }

        /**
         * If executed within a FieldResolver we will (most likely)
         * receive a Field.
         */
        if (!$isFieldDataAccessor) {
            $fieldData = $this->getFieldData(
                $field,
                $objectTypeFieldResolutionFeedbackStore
            );
            if ($fieldData === null) {
                return null;
            }
            $fieldDataAccessor = $this->createFieldDataAccessor(
                $field,
                $fieldData
            );
        }

        /**
         * Among Promises, we have:
         *
         * 1. Resolved Field Value Reference: it is resolved at the object level
         * 2. Dynamic Variable Reference: it is resolved at the Engine iteration level
         *
         * Then #2 could be optimized, executing the casting/validation below
         * only once for all fields/objects in the current Engine iteration,
         * instead of once per object.
         *
         * But for simplicity, it's done here.
         */
        $hasAnyArgumentReferencingValuePromise = $field->hasAnyArgumentReferencingValuePromise();
        $validateSchemaOnObject = $options[self::OPTION_VALIDATE_SCHEMA_ON_RESULT_ITEM] ?? false;
        if ($hasAnyArgumentReferencingValuePromise || $validateSchemaOnObject) {
            $fieldData = null;
            try {
                $fieldData = $fieldDataAccessor->getFieldArgs();
            } catch (AbstractDeferredValuePromiseException $deferredValuePromiseException) {
                $objectTypeFieldResolutionFeedbackStore->addError(
                    new ObjectTypeFieldResolutionFeedback(
                        $deferredValuePromiseException->getFeedbackItemResolution(),
                        $deferredValuePromiseException->getAstNode(),
                    )
                );
                return null;
            }

            /**
             * Cast the Arguments, return if any of them produced an error
             */
            $fieldArgsSchemaDefinition = $this->getFieldArgumentsSchemaDefinition($field);
            $fieldData = $this->getSchemaCastingService()->castArguments(
                $fieldData,
                $fieldArgsSchemaDefinition,
                $field,
                $objectTypeFieldResolutionFeedbackStore,
            );
            if ($objectTypeFieldResolutionFeedbackStore->getErrors() !== []) {
                return null;
            }

            $this->validateVariableOnObjectResolutionFieldData(
                $fieldData,
                $field,
                false, // Mutation validation will be performed always in validateFieldDataForObject
                $objectTypeFieldResolutionFeedbackStore,
            );
            if ($objectTypeFieldResolutionFeedbackStore->getErrors() !== []) {
                return null;
            }

            /**
             * Re-recreate the data, containing the casted arguments
             */
            $fieldDataAccessor = $this->createFieldDataAccessor(
                $field,
                $fieldData,
            );
        }

        /**
         * Custom validations by the field resolver
         */
        $this->validateFieldDataForObject(
            $fieldDataAccessor,
            $objectTypeFieldResolver,
            $object,
            $objectTypeFieldResolutionFeedbackStore,
        );
        if ($objectTypeFieldResolutionFeedbackStore->getErrors() !== []) {
            return null;
        }

        // Resolve the value. If the field resolver throws an Exception,
        // catch it and return the equivalent GraphQL error so that it
        // fails gracefully in production (but not on development!)
        $value = null;
        try {
            $value = $objectTypeFieldResolver->resolveValue(
                $this,
                $object,
                $fieldDataAccessor,
                $objectTypeFieldResolutionFeedbackStore,
            );
        } catch (Exception $e) {
            /** @var ModuleConfiguration */
            $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
            if ($moduleConfiguration->logExceptionErrorMessagesAndTraces()) {
                $objectTypeFieldResolutionFeedbackStore->addLog(
                    new ObjectTypeFieldResolutionFeedback(
                        new FeedbackItemResolution(
                            ErrorFeedbackItemProvider::class,
                            ErrorFeedbackItemProvider::E3A,
                            [
                                $field->getName(),
                                $e->getMessage(),
                                $e->getTraceAsString()
                            ]
                        ),
                        $field,
                    )
                );
            }
            $sendExceptionErrorMessages = $e instanceof AbstractClientException
                || $moduleConfiguration->sendExceptionErrorMessages();
            $feedbackItemResolution = $sendExceptionErrorMessages
                ? ($moduleConfiguration->sendExceptionTraces()
                    ? new FeedbackItemResolution(
                        ErrorFeedbackItemProvider::class,
                        ErrorFeedbackItemProvider::E3A,
                        [
                            $field->getName(),
                            $e->getMessage(),
                            $e->getTraceAsString(),
                        ]
                    )
                    : new FeedbackItemResolution(
                        ErrorFeedbackItemProvider::class,
                        ErrorFeedbackItemProvider::E3,
                        [
                            $field->getName(),
                            $e->getMessage(),
                        ]
                    )
                )
                : new FeedbackItemResolution(
                    ErrorFeedbackItemProvider::class,
                    ErrorFeedbackItemProvider::E4,
                    [
                        $field->getName(),
                    ]
                );
            $objectTypeFieldResolutionFeedbackStore->addError(
                new ObjectTypeFieldResolutionFeedback(
                    $feedbackItemResolution,
                    $field,
                )
            );
        }

        /**
         * If there were errors, return already
         */
        if ($objectTypeFieldResolutionFeedbackStore->getErrors() !== []) {
            return null;
        }

        /**
         * Validate that the value is what was defined in the schema, or throw a corresponding error.
         *
         * Items being validated:
         *
         * - Is it null?
         * - Is it an array when it should be?
         * - Is it not an array when it should not be?
         *
         * Items NOT being validated:
         *
         * - Is the returned type (String, Int, some Object, etc) the expected one?
         *
         * According to the GraphQL speck, checking if a non-null field returns null
         * is handled always:
         *
         *   If the result of resolving a field is null (either because the function
         *   to resolve the field returned null or because a field error was raised),
         *   and that field is of a Non-Null type, then a field error is raised.
         *   The error must be added to the "errors" list in the response.
         *
         * @see https://spec.graphql.org/draft/#sec-Handling-Field-Errors
         *
         * All other conditions, check them when enabled by configuration.
         */
        if ($value === null) {
            $fieldTypeModifiers = $objectTypeFieldResolver->getFieldTypeModifiers($this, $field->getName());
            $fieldTypeIsNonNullable = ($fieldTypeModifiers & SchemaTypeModifiers::NON_NULLABLE) === SchemaTypeModifiers::NON_NULLABLE;
            if ($fieldTypeIsNonNullable) {
                $objectTypeFieldResolutionFeedbackStore->addError(
                    new ObjectTypeFieldResolutionFeedback(
                        new FeedbackItemResolution(
                            FieldResolutionErrorFeedbackItemProvider::class,
                            FieldResolutionErrorFeedbackItemProvider::E3,
                            [
                                $field->getName(),
                            ]
                        ),
                        $field,
                    )
                );
                return null;
            }
        } elseif (
            $objectTypeFieldResolver->validateResolvedFieldType(
                $this,
                $field,
            )
        ) {
            $fieldSchemaDefinition = $objectTypeFieldResolver->getFieldSchemaDefinition($this, $field->getName());
            $fieldTypeResolver = $fieldSchemaDefinition[SchemaDefinition::TYPE_RESOLVER];

            /**
             * `DangerouslyNonSpecificScalar` is a special scalar type which is not coerced or validated.
             * In particular, it does not need to validate if it is an array or not,
             * as according to the applied WrappingType.
             *
             * This is to enable it to have an array as value, which is not
             * allowed by GraphQL unless the array is explicitly defined.
             *
             * For instance, type `DangerouslyNonSpecificScalar` could have values
             * `"hello"` and `["hello"]`, but in GraphQL we must differentiate
             * these values by types `String` and `[String]`.
             */
            if ($fieldTypeResolver === $this->getDangerouslyNonSpecificScalarTypeScalarTypeResolver()) {
                return $value;
            }

            /**
             * Execute the validation, return an error if it fails
             */
            $fieldIsArrayType = $fieldSchemaDefinition[SchemaDefinition::IS_ARRAY] ?? false;
            if (
                !$fieldIsArrayType
                && is_array($value)
            ) {
                $objectTypeFieldResolutionFeedbackStore->addError(
                    new ObjectTypeFieldResolutionFeedback(
                        new FeedbackItemResolution(
                            FieldResolutionErrorFeedbackItemProvider::class,
                            FieldResolutionErrorFeedbackItemProvider::E4,
                            [
                                $field->getName(),
                                $this->getOutputService()->jsonEncodeArrayOrStdClassValue($value)
                            ]
                        ),
                        $field,
                    )
                );
                return null;
            }
            if (
                $fieldIsArrayType
                && !is_array($value)
            ) {
                if ($value instanceof stdClass) {
                    $valueAsString = $this->getOutputService()->jsonEncodeArrayOrStdClassValue($value);
                } elseif (is_object($value)) {
                    $valueAsString = $this->getObjectSerializationManager()->serialize($value);
                } else {
                    $valueAsString = (string) $value;
                }
                $objectTypeFieldResolutionFeedbackStore->addError(
                    new ObjectTypeFieldResolutionFeedback(
                        new FeedbackItemResolution(
                            FieldResolutionErrorFeedbackItemProvider::class,
                            FieldResolutionErrorFeedbackItemProvider::E5,
                            [
                                $field->getName(),
                                $valueAsString,
                            ]
                        ),
                        $field,
                    )
                );
                return null;
            }
            $fieldIsNonNullArrayItemsType = $fieldSchemaDefinition[SchemaDefinition::IS_NON_NULLABLE_ITEMS_IN_ARRAY] ?? false;
            if (
                $fieldIsNonNullArrayItemsType
                && is_array($value)
                && array_filter(
                    $value,
                    fn (mixed $arrayItem) => $arrayItem === null
                )
            ) {
                $objectTypeFieldResolutionFeedbackStore->addError(
                    new ObjectTypeFieldResolutionFeedback(
                        new FeedbackItemResolution(
                            FieldResolutionErrorFeedbackItemProvider::class,
                            FieldResolutionErrorFeedbackItemProvider::E6,
                            [
                                $field->getName(),
                            ]
                        ),
                        $field,
                    )
                );
                return null;
            }
            $fieldIsArrayOfArraysType = $fieldSchemaDefinition[SchemaDefinition::IS_ARRAY_OF_ARRAYS] ?? false;
            if (
                !$fieldIsArrayOfArraysType
                && is_array($value)
                && array_filter(
                    $value,
                    fn (mixed $arrayItem) => is_array($arrayItem)
                )
            ) {
                $objectTypeFieldResolutionFeedbackStore->addError(
                    new ObjectTypeFieldResolutionFeedback(
                        new FeedbackItemResolution(
                            FieldResolutionErrorFeedbackItemProvider::class,
                            FieldResolutionErrorFeedbackItemProvider::E7,
                            [
                                $field->getName(),
                                $this->getOutputService()->jsonEncodeArrayOrStdClassValue($value),
                            ]
                        ),
                        $field,
                    )
                );
                return null;
            }
            if (
                $fieldIsArrayOfArraysType
                && is_array($value)
                && array_filter(
                    $value,
                    // `null` could be accepted as an array! (Validation against null comes next)
                    fn (mixed $arrayItem) => !is_array($arrayItem) && $arrayItem !== null
                )
            ) {
                $objectTypeFieldResolutionFeedbackStore->addError(
                    new ObjectTypeFieldResolutionFeedback(
                        new FeedbackItemResolution(
                            FieldResolutionErrorFeedbackItemProvider::class,
                            FieldResolutionErrorFeedbackItemProvider::E8,
                            [
                                $field->getName(),
                                $this->getOutputService()->jsonEncodeArrayOrStdClassValue($value),
                            ]
                        ),
                        $field,
                    )
                );
                return null;
            }
            $fieldIsNonNullArrayOfArraysItemsType = $fieldSchemaDefinition[SchemaDefinition::IS_NON_NULLABLE_ITEMS_IN_ARRAY_OF_ARRAYS] ?? false;
            if (
                $fieldIsNonNullArrayOfArraysItemsType
                && is_array($value)
                && array_filter(
                    $value,
                    fn (?array $arrayItem) => $arrayItem === null ? false : array_filter(
                        $arrayItem,
                        fn (mixed $arrayItemItem) => $arrayItemItem === null
                    ) !== [],
                )
            ) {
                $objectTypeFieldResolutionFeedbackStore->addError(
                    new ObjectTypeFieldResolutionFeedback(
                        new FeedbackItemResolution(
                            FieldResolutionErrorFeedbackItemProvider::class,
                            FieldResolutionErrorFeedbackItemProvider::E9,
                            [
                                $field->getName(),
                            ]
                        ),
                        $field,
                    )
                );
                return null;
            }
        }

        // Everything is good, return the value
        return $value;
    }

    /**
     * Validate the field data for the object
     */
    protected function validateFieldDataForObject(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolverInterface $objectTypeFieldResolver,
        object $object,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        $fieldArgs = null;
        try {
            $fieldArgs = $fieldDataAccessor->getFieldArgs();
        } catch (AbstractDeferredValuePromiseException $deferredValuePromiseException) {
            $objectTypeFieldResolutionFeedbackStore->addError(
                new ObjectTypeFieldResolutionFeedback(
                    $deferredValuePromiseException->getFeedbackItemResolution(),
                    $deferredValuePromiseException->getAstNode(),
                )
            );
            return;
        }

        /**
         * Perform validation through checkpoints
         */
        if (
            $checkpoints = $objectTypeFieldResolver->getValidationCheckpoints(
                $this,
                $object,
                $fieldDataAccessor->getFieldName(),
                $fieldArgs,
            )
        ) {
            $feedbackItemResolution = $this->getEngine()->validateCheckpoints($checkpoints);
            if ($feedbackItemResolution !== null) {
                $objectTypeFieldResolutionFeedbackStore->addError(
                    new ObjectTypeFieldResolutionFeedback(
                        $feedbackItemResolution,
                        $fieldDataAccessor->getField(),
                    )
                );
                return;
            }
        }

        /**
         * Custom validations by the field resolver
         */
        $objectTypeFieldResolver->validateFieldDataForObject($this, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);

        /**
         * If a MutationResolver is declared, let it validate the schema
         */
        $mutationResolver = $objectTypeFieldResolver->getFieldMutationResolver($this, $fieldDataAccessor->getFieldName());
        if ($mutationResolver !== null && $objectTypeFieldResolver->validateMutationOnObject($this, $fieldDataAccessor->getFieldName())) {
            // Validate on the object
            $fieldDataAccessorForMutation = null;
            try {
                $objectTypeFieldResolver->prepareFieldDataForMutationForObject(
                    $fieldArgs,
                    $this,
                    $fieldDataAccessor->getField(),
                    $object,
                );
                $fieldDataAccessorForMutation = $this->getFieldDataAccessorForMutation(
                    $this->createFieldDataAccessor(
                        $fieldDataAccessor->getField(),
                        $fieldArgs
                    )
                );
            } catch (AbstractDeferredValuePromiseException $deferredValuePromiseException) {
                $objectTypeFieldResolutionFeedbackStore->addError(
                    new ObjectTypeFieldResolutionFeedback(
                        $deferredValuePromiseException->getFeedbackItemResolution(),
                        $deferredValuePromiseException->getAstNode(),
                    )
                );
                return;
            }
            $mutationResolver->validateErrors($fieldDataAccessorForMutation, $objectTypeFieldResolutionFeedbackStore);
        }
    }

    final protected function getFieldArgumentsSchemaDefinition(FieldInterface $field): ?array
    {
        $fieldSchemaDefinition = $this->getFieldSchemaDefinition($field);
        if ($fieldSchemaDefinition === null) {
            return null;
        }
        return $fieldSchemaDefinition[SchemaDefinition::ARGS] ?? [];
    }

    final public function getExecutableObjectTypeFieldResolversByField(bool $global): array
    {
        $cacheKey = $global ? 'global' : 'non-global';
        if (($this->executableObjectTypeFieldResolversByFieldCache[$cacheKey] ?? null) === null) {
            $this->executableObjectTypeFieldResolversByFieldCache[$cacheKey] = $this->doGetExecutableObjectTypeFieldResolversByField($global);
        }
        return $this->executableObjectTypeFieldResolversByFieldCache[$cacheKey];
    }

    private function doGetExecutableObjectTypeFieldResolversByField(bool $global): array
    {
        $objectTypeFieldResolvers = [];
        foreach ($this->getObjectTypeFieldResolversByField($global) as $fieldName => $fieldObjectTypeFieldResolvers) {
            // Get the first item from the list of resolvers. That's the one that will be executed
            $objectTypeFieldResolvers[$fieldName] = $fieldObjectTypeFieldResolvers[0];
        }
        return $objectTypeFieldResolvers;
    }

    final public function getObjectTypeFieldResolversByField(bool $global): array
    {
        $cacheKey = $global ? 'global' : 'non-global';
        if (($this->objectTypeFieldResolversByFieldCache[$cacheKey] ?? null) === null) {
            $this->objectTypeFieldResolversByFieldCache[$cacheKey] = $this->doGetObjectTypeFieldResolversByField($global);
        }
        return $this->objectTypeFieldResolversByFieldCache[$cacheKey];
    }

    private function doGetObjectTypeFieldResolversByField(bool $global): array
    {
        $objectTypeFieldResolvers = [];
        foreach ($this->getAllObjectTypeFieldResolversByField() as $fieldName => $fieldObjectTypeFieldResolvers) {
            $matchesGlobalFieldObjectTypeFieldResolvers = array_filter(
                $fieldObjectTypeFieldResolvers,
                fn (ObjectTypeFieldResolverInterface $objectTypeFieldResolver) => $global === $objectTypeFieldResolver->isGlobal($this, $fieldName)
            );
            if ($matchesGlobalFieldObjectTypeFieldResolvers !== []) {
                $objectTypeFieldResolvers[$fieldName] = $matchesGlobalFieldObjectTypeFieldResolvers;
            }
        }
        return $objectTypeFieldResolvers;
    }

    /**
     * Return the fieldNames resolved by the fieldResolver, adding a hook to disable each of them (eg: to implement a private schema)
     *
     * @return string[]
     */
    final protected function getFieldNamesResolvedByObjectTypeFieldResolver(ObjectTypeFieldResolverInterface $objectTypeFieldResolver): array
    {
        $objectTypeFieldResolverClass = get_class($objectTypeFieldResolver);
        if (!isset($this->fieldNamesResolvedByObjectTypeFieldResolver[$objectTypeFieldResolverClass])) {
            // Merge the fieldNames resolved by this field resolver class, and the interfaces it implements
            $fieldNames = array_values(array_unique(array_merge(
                $objectTypeFieldResolver->getFieldNamesToResolve(),
                $objectTypeFieldResolver->getFieldNamesFromInterfaces()
            )));
            $fieldNames = $this->maybeExcludeFieldNamesFromSchema(
                $this,
                $objectTypeFieldResolver,
                $fieldNames
            );
            $this->fieldNamesResolvedByObjectTypeFieldResolver[$objectTypeFieldResolverClass] = $fieldNames;
        }
        return $this->fieldNamesResolvedByObjectTypeFieldResolver[$objectTypeFieldResolverClass];
    }

    final protected function getAllObjectTypeFieldResolversByField(): array
    {
        if ($this->allObjectTypeFieldResolversByFieldCache === null) {
            $this->allObjectTypeFieldResolversByFieldCache = $this->calculateAllObjectTypeFieldResolvers();
        }
        return $this->allObjectTypeFieldResolversByFieldCache;
    }

    private function calculateAllObjectTypeFieldResolvers(): array
    {
        $schemaObjectTypeFieldResolvers = [];

        // Get the ObjectTypeFieldResolvers attached to this ObjectTypeResolver
        $class = get_class($this->getTypeResolverToCalculateSchema());
        // Iterate classes from the current class towards the parent classes until finding typeResolver that satisfies processing this field
        do {
            /** @var ObjectTypeFieldResolverInterface[] */
            $attachedObjectTypeFieldResolvers = $this->getAttachableExtensionManager()->getAttachedExtensions($class, AttachableExtensionGroups::OBJECT_TYPE_FIELD_RESOLVERS);
            foreach ($attachedObjectTypeFieldResolvers as $objectTypeFieldResolver) {
                // Process the fields which have not been processed yet
                $extensionFieldNames = $this->getFieldNamesResolvedByObjectTypeFieldResolver($objectTypeFieldResolver);
                foreach (array_diff($extensionFieldNames, array_keys($schemaObjectTypeFieldResolvers)) as $fieldName) {
                    // Watch out here: no fieldArgs!!!! So this deals with the base case (static), not with all cases (runtime)
                    // If using an ACL to remove a field from an interface,
                    // getting the ObjectTypeFieldResolvers for that field will be empty
                    // Then ignore adding the field, it must not be added to the schema
                    $objectTypeFieldResolversForFieldName = $this->getObjectTypeFieldResolversForFieldOrFieldName($fieldName);
                    if (!$objectTypeFieldResolversForFieldName) {
                        continue;
                    }
                    $schemaObjectTypeFieldResolvers[$fieldName] = $objectTypeFieldResolversForFieldName;
                }
            }
            // Otherwise, continue iterating for the class parents
        } while ($class = get_parent_class($class));

        return $schemaObjectTypeFieldResolvers;
    }

    /**
     * @return InterfaceTypeFieldResolverInterface[]
     */
    final protected function getAllImplementedInterfaceTypeFieldResolvers(): array
    {
        if ($this->implementedInterfaceTypeFieldResolversCache === null) {
            $this->implementedInterfaceTypeFieldResolversCache = $this->calculateAllImplementedInterfaceTypeFieldResolvers();
        }
        return $this->implementedInterfaceTypeFieldResolversCache;
    }

    /**
     * @return InterfaceTypeFieldResolverInterface[]
     */
    private function calculateAllImplementedInterfaceTypeFieldResolvers(): array
    {
        $interfaceTypeFieldResolvers = [];
        $processedObjectTypeFieldResolverClasses = [];
        foreach ($this->getAllObjectTypeFieldResolversByField() as $fieldName => $objectTypeFieldResolvers) {
            foreach ($objectTypeFieldResolvers as $objectTypeFieldResolver) {
                $objectTypeFieldResolverClass = get_class($objectTypeFieldResolver);
                if (!in_array($objectTypeFieldResolverClass, $processedObjectTypeFieldResolverClasses)) {
                    $processedObjectTypeFieldResolverClasses[] = $objectTypeFieldResolverClass;
                    foreach ($objectTypeFieldResolver->getImplementedInterfaceTypeFieldResolvers() as $implementedInterfaceTypeFieldResolver) {
                        // Add under class as to mimick `array_unique` for object
                        $interfaceTypeFieldResolvers[get_class($implementedInterfaceTypeFieldResolver)] = $implementedInterfaceTypeFieldResolver;
                    }
                }
            }
        }
        return array_values($interfaceTypeFieldResolvers);
    }

    /**
     * @return InterfaceTypeResolverInterface[]
     */
    final public function getImplementedInterfaceTypeResolvers(): array
    {
        if ($this->implementedInterfaceTypeResolversCache === null) {
            $this->implementedInterfaceTypeResolversCache = $this->calculateAllImplementedInterfaceTypeResolvers();
        }
        return $this->implementedInterfaceTypeResolversCache;
    }

    /**
     * @return InterfaceTypeResolverInterface[]
     */
    private function calculateAllImplementedInterfaceTypeResolvers(): array
    {
        $interfaceTypeResolvers = [];
        foreach ($this->getAllImplementedInterfaceTypeFieldResolvers() as $interfaceTypeFieldResolver) {
            foreach ($interfaceTypeFieldResolver->getPartiallyImplementedInterfaceTypeResolvers() as $partiallyImplementedInterfaceTypeResolver) {
                // Add under class as to mimick `array_unique` for object
                $interfaceTypeResolvers[get_class($partiallyImplementedInterfaceTypeResolver)] = $partiallyImplementedInterfaceTypeResolver;
            }
        }
        $interfaceTypeResolvers = array_values($interfaceTypeResolvers);
        // Every InterfaceTypeResolver can be injected fields from many InterfaceTypeFieldResolvers
        // Make sure that this typeResolver implements all these InterfaceTypeFieldResolver
        // If not, the type does not fully satisfy the Interface
        $implementedInterfaceTypeFieldResolvers = $this->getAllImplementedInterfaceTypeFieldResolvers();
        return array_values(array_filter(
            $interfaceTypeResolvers,
            fn (InterfaceTypeResolverInterface $interfaceTypeResolver) => array_udiff(
                $interfaceTypeResolver->getInterfaceTypeFieldResolvers(),
                $implementedInterfaceTypeFieldResolvers,
                /**
                 * Use arrow function here, or there's an issue when downgrading to PHP 7.1:
                 * vars `$a` and `$b` are wrongly added as `use($a, $b)` to the first anonymous function,
                 * as if they were present in the original function scope, which they are not.
                 *
                 * This issue has been fixed for `fn` inside `fn`, but
                 * but not for anonymous function inside `fn`
                 *
                 * @see https://github.com/rectorphp/rector/issues/6730
                 */
                fn (object $a, object $b) => get_class($a) <=> get_class($b),
            ) === [],
        ));
    }

    /**
     * Get the first FieldResolver that resolves the field
     */
    final public function getExecutableObjectTypeFieldResolverForField(FieldInterface|string $fieldOrFieldName): ?ObjectTypeFieldResolverInterface
    {
        $objectTypeFieldResolversForFieldOrFieldName = $this->getObjectTypeFieldResolversForFieldOrFieldName($fieldOrFieldName);
        if ($objectTypeFieldResolversForFieldOrFieldName === []) {
            return null;
        }
        return $objectTypeFieldResolversForFieldOrFieldName[0];
    }

    /**
     * @return ObjectTypeFieldResolverInterface[]
     */
    final protected function getObjectTypeFieldResolversForFieldOrFieldName(FieldInterface|string $fieldOrFieldName): array
    {
        if ($fieldOrFieldName instanceof FieldInterface) {
            $field = $fieldOrFieldName;
            $cacheKey = $field->asFieldOutputQueryString();
        } else {
            $cacheKey = $fieldOrFieldName;
        }
        // Calculate the fieldResolver to process this field if not already in the cache
        // If none is found, this value will be set to NULL. This is needed to stop attempting to find the fieldResolver
        if (!isset($this->objectTypeFieldResolversForFieldOrFieldNameCache[$cacheKey])) {
            $this->objectTypeFieldResolversForFieldOrFieldNameCache[$cacheKey] = $this->calculateObjectTypeFieldResolversForFieldOrFieldName($fieldOrFieldName);
        }

        return $this->objectTypeFieldResolversForFieldOrFieldNameCache[$cacheKey];
    }

    final public function hasObjectTypeFieldResolversForField(FieldInterface $field): bool
    {
        return !empty($this->getObjectTypeFieldResolversForFieldOrFieldName($field));
    }

    protected function calculateObjectTypeFieldResolversForFieldOrFieldName(FieldInterface|string $fieldOrFieldName): array
    {
        if ($fieldOrFieldName instanceof FieldInterface) {
            $field = $fieldOrFieldName;
            $fieldName = $field->getName();
        } else {
            $fieldName = $fieldOrFieldName;
            /**
             * Please notice: $fieldName could be for either a Leaf or Relational Field,
             * but just to ask if the FieldResolver can resolve it, this doesn't make a
             * difference, so simply provide a LeafField always to make it simple.
             *
             * @var FieldInterface
             */
            $field = new LeafField(
                $fieldName,
                null,
                [],
                [],
                LocationHelper::getNonSpecificLocation()
            );
        }

        $objectTypeFieldResolvers = [];
        // Get the ObjectTypeFieldResolvers attached to this ObjectTypeResolver
        $class = get_class($this->getTypeResolverToCalculateSchema());
        // Iterate classes from the current class towards the parent classes until finding typeResolver that satisfies processing this field
        do {
            // All the Units and their priorities for this class level
            $classTypeResolverPriorities = [];
            $classObjectTypeFieldResolvers = [];

            // Important: do array_reverse to enable more specific hooks, which are initialized later on in the project, to be the chosen ones (if their priority is the same)
            /** @var ObjectTypeFieldResolverInterface[] */
            $attachedObjectTypeFieldResolvers = array_reverse($this->getAttachableExtensionManager()->getAttachedExtensions($class, AttachableExtensionGroups::OBJECT_TYPE_FIELD_RESOLVERS));
            foreach ($attachedObjectTypeFieldResolvers as $objectTypeFieldResolver) {
                $extensionFieldNames = $this->getFieldNamesResolvedByObjectTypeFieldResolver($objectTypeFieldResolver);
                if (!in_array($fieldName, $extensionFieldNames)) {
                    continue;
                }
                // Check that the fieldResolver can handle the field based on other parameters (eg: "version" in the fieldArgs)
                if (!$objectTypeFieldResolver->resolveCanProcess($this, $field)) {
                    continue;
                }
                $extensionPriority = $objectTypeFieldResolver->getPriorityToAttachToClasses();
                $classTypeResolverPriorities[] = $extensionPriority;
                $classObjectTypeFieldResolvers[] = $objectTypeFieldResolver;
            }
            // Sort the found units by their priority, and then add to the stack of all units, for all classes
            // Higher priority means they execute first!
            array_multisort($classTypeResolverPriorities, SORT_DESC, SORT_NUMERIC, $classObjectTypeFieldResolvers);
            // Add under class as to mimick `array_unique` for object
            foreach ($classObjectTypeFieldResolvers as $classObjectTypeFieldResolver) {
                $objectTypeFieldResolvers[get_class($classObjectTypeFieldResolver)] = $classObjectTypeFieldResolver;
            }
            // Continue iterating for the class parents
        } while ($class = get_parent_class($class));

        // Return all the units that resolve the fieldName
        return array_values($objectTypeFieldResolvers);
    }

    /**
     * Convert the FieldArgs into its corresponding FieldDataAccessor, which integrates
     * within the default values and coerces them according to the schema.
     *
     * @see FieldDataAccessProvider
     *
     * @param SplObjectStorage<FieldInterface,array<string|int>> $fieldIDs
     * @param array<string|int,object> $idObjects
     * @return SplObjectStorage<ObjectTypeResolverInterface,SplObjectStorage<object,array<string,mixed>>>|null
     */
    protected function doGetObjectTypeResolverObjectFieldData(
        FieldInterface $field,
        SplObjectStorage $fieldIDs,
        array $idObjects,
        EngineIterationFeedbackStore $engineIterationFeedbackStore,
    ): ?SplObjectStorage {
        $executableObjectTypeFieldResolver = $this->getExecutableObjectTypeFieldResolverForField($field);
        if ($executableObjectTypeFieldResolver === null) {
            /**
             * If the field does not exist in the schema, then add an error the first
             * time, and retrieve it from the cache from then on, so the error is
             * not added more than once to the response.
             */
            if (!$this->fieldObjectTypeResolverObjectFieldDataCache->contains($field)) {
                $this->fieldObjectTypeResolverObjectFieldDataCache[$field] = null;
                $engineIterationFeedbackStore->schemaFeedbackStore->addError(
                    new SchemaFeedback(
                        new FeedbackItemResolution(
                            ErrorFeedbackItemProvider::class,
                            ErrorFeedbackItemProvider::E16,
                            [
                                $field->getName(),
                                $this->getMaybeNamespacedTypeName()
                            ]
                        ),
                        $field,
                        $this,
                        [$field],
                    )
                );
            }
            return null;
        }
        if ($executableObjectTypeFieldResolver->validateMutationOnObject($this, $field->getName())) {
            return $this->getIndependentObjectTypeResolverObjectFieldData(
                $field,
                $fieldIDs[$field],
                $idObjects,
                $engineIterationFeedbackStore,
            );
        }
        return $this->getWildcardObjectTypeResolverObjectFieldData(
            $field,
            $engineIterationFeedbackStore,
        );
    }

    /**
     * Handle case:
     *
     * 1. Data from a Field in an ObjectTypeResolver: a single instance of the
     *    FieldArgs will satisfy all queried objects, since the same schema applies
     *    to all of them.
     *
     * @return SplObjectStorage<ObjectTypeResolverInterface,SplObjectStorage<object,array<string,mixed>>>|null null if there was an error casting the fieldArgs
     */
    public function getWildcardObjectTypeResolverObjectFieldData(
        FieldInterface $field,
        EngineIterationFeedbackStore $engineIterationFeedbackStore,
    ): ?SplObjectStorage {
        $wildcardObject = FieldDataAccessWildcardObjectFactory::getWildcardObject();
        /**
         * Check if can retrieve the values from the cache.
         * First for the case where all objects are handled all together.
         */
        if ($this->fieldObjectTypeResolverObjectFieldDataCache->contains($field)) {
            if ($this->fieldObjectTypeResolverObjectFieldDataCache[$field] === null) {
                return null;
            }
            if (
                $this->fieldObjectTypeResolverObjectFieldDataCache[$field]->contains($this)
                && $this->fieldObjectTypeResolverObjectFieldDataCache[$field][$this]->contains($wildcardObject)
            ) {
                return $this->fieldObjectTypeResolverObjectFieldDataCache[$field];
            }
        }

        /** @var SplObjectStorage<ObjectTypeResolverInterface,SplObjectStorage<object,array<string,mixed>>> */
        $objectTypeResolverObjectFieldData = $this->fieldObjectTypeResolverObjectFieldDataCache[$field] ?? new SplObjectStorage();

        /** @var SplObjectStorage<object,array<string,mixed>> */
        $objectFieldData = $objectTypeResolverObjectFieldData[$this] ?? new SplObjectStorage();

        $objectTypeFieldResolutionFeedbackStore = new ObjectTypeFieldResolutionFeedbackStore();
        $fieldData = $this->getFieldData(
            $field,
            $objectTypeFieldResolutionFeedbackStore,
        );
        $engineIterationFeedbackStore->schemaFeedbackStore->incorporateFromObjectTypeFieldResolutionFeedbackStore(
            $objectTypeFieldResolutionFeedbackStore,
            $this,
            [$field],
        );
        if ($objectTypeFieldResolutionFeedbackStore->getErrors() !== []) {
            $this->fieldObjectTypeResolverObjectFieldDataCache[$field] = null;
            return null;
        }
        /** @var array<string,mixed> $fieldData */

        $objectFieldData[$wildcardObject] = $fieldData;
        $objectTypeResolverObjectFieldData[$this] = $objectFieldData;
        // Store in the cache
        $this->fieldObjectTypeResolverObjectFieldDataCache[$field] = $objectTypeResolverObjectFieldData;
        return $objectTypeResolverObjectFieldData;
    }

    /**
     * Handle case:
     *
     * 3. Data for a specific object: When executing nested mutations, the FieldArgs
     *    for each object will be different, as it will contain implicit information
     *    belonging to the object.
     *    For instance, when querying `mutation { posts { update(title: "New title") { id } } }`,
     *    the value for the `$postID` is injected into the FieldArgs for each object,
     *    and the validation of the FieldArgs must also be executed for each object.
     *
     * @param array<string|int> $objectIDs
     * @param array<string|int,object> $idObjects
     * @return SplObjectStorage<ObjectTypeResolverInterface,SplObjectStorage<object,array<string,mixed>>>|null null if there was an error casting the fieldArgs
     */
    public function getIndependentObjectTypeResolverObjectFieldData(
        FieldInterface $field,
        array $objectIDs,
        array $idObjects,
        EngineIterationFeedbackStore $engineIterationFeedbackStore,
    ): ?SplObjectStorage {
        /** @var ObjectTypeFieldResolverInterface */
        $executableObjectTypeFieldResolver = $this->getExecutableObjectTypeFieldResolverForField($field);

        /** @var SplObjectStorage<ObjectTypeResolverInterface,SplObjectStorage<object,array<string,mixed>>> */
        $objectTypeResolverObjectFieldData = new SplObjectStorage();

        /** @var SplObjectStorage<object,array<string,mixed>> */
        $objectFieldData = new SplObjectStorage();

        /**
         * Check if can retrieve the values from the cache for each of
         * the objects, for when each of them has its own FieldArgs
         */
        $remainingObjectIDs = $objectIDs;
        if ($this->fieldObjectTypeResolverObjectFieldDataCache->contains($field)) {
            if ($this->fieldObjectTypeResolverObjectFieldDataCache[$field] === null) {
                return null;
            }
            if ($this->fieldObjectTypeResolverObjectFieldDataCache[$field]->contains($this)) {
                $remainingObjectIDs = [];
                foreach ($objectIDs as $id) {
                    $object = $idObjects[$id];
                    if ($this->fieldObjectTypeResolverObjectFieldDataCache[$field][$this]->contains($object)) {
                        $objectFieldData[$object] = $this->fieldObjectTypeResolverObjectFieldDataCache[$field][$this][$object];
                        continue;
                    }
                    $remainingObjectIDs[] = $id;
                }
                // Are all objects cached?
                if ($remainingObjectIDs === []) {
                    $objectTypeResolverObjectFieldData[$this] = $objectFieldData;
                    return $objectTypeResolverObjectFieldData;
                }
            }
        }

        /** @var SplObjectStorage<ObjectTypeResolverInterface,SplObjectStorage<object,array<string,mixed>>> */
        $objectTypeResolverObjectFieldDataCache = $this->fieldObjectTypeResolverObjectFieldDataCache[$field] ?? new SplObjectStorage();

        /** @var SplObjectStorage<object,array<string,mixed>> */
        $objectFieldDataCache = $objectTypeResolverObjectFieldDataCache[$this] ?? new SplObjectStorage();

        $objectTypeFieldResolutionFeedbackStore = new ObjectTypeFieldResolutionFeedbackStore();
        $fieldData = $this->getFieldData(
            $field,
            $objectTypeFieldResolutionFeedbackStore,
        );
        $engineIterationFeedbackStore->schemaFeedbackStore->incorporateFromObjectTypeFieldResolutionFeedbackStore(
            $objectTypeFieldResolutionFeedbackStore,
            $this,
            [$field],
        );
        if ($objectTypeFieldResolutionFeedbackStore->getErrors() !== []) {
            $this->fieldObjectTypeResolverObjectFieldDataCache[$field] = null;
            return null;
        }

        foreach ($remainingObjectIDs as $id) {
            $object = $idObjects[$id];
            // Clone array
            $fieldDataForObject = array_merge([], $fieldData);
            $executableObjectTypeFieldResolver->prepareFieldDataForObject(
                $fieldDataForObject,
                $this,
                $field,
                $object,
            );
            $objectFieldData[$object] = $fieldDataForObject;
            // Store in the cache
            $objectFieldDataCache[$object] = $fieldDataForObject;
        }
        $objectTypeResolverObjectFieldData[$this] = $objectFieldData;
        // Store in the cache
        $objectTypeResolverObjectFieldDataCache[$this] = $objectFieldDataCache;
        $this->fieldObjectTypeResolverObjectFieldDataCache[$field] = $objectTypeResolverObjectFieldDataCache;
        return $objectTypeResolverObjectFieldData;
    }

    /**
     * Extract the FieldArgs into its corresponding FieldDataAccessor, which integrates
     * within the default values and coerces them according to the schema.
     *
     * @return array<string,mixed>|null
     */
    protected function getFieldData(
        FieldInterface $field,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): ?array {
        if (!$this->fieldDataCache->contains($field)) {
            $this->fieldDataCache[$field] = $this->doGetFieldData(
                $field,
                $objectTypeFieldResolutionFeedbackStore,
            );
        }
        return $this->fieldDataCache[$field];
    }

    /**
     * Retrieve the Field Arguments data, adding the default values
     * coercing all values, and allowing to apply customizations
     *
     * @return array<string,mixed>|null null if there was a validation error
     */
    protected function doGetFieldData(
        FieldInterface $field,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): ?array {
        $fieldData = $field->getArgumentKeyValues();
        $hasAnyArgumentReferencingValuePromise = $field->hasAnyArgumentReferencingValuePromise();

        /**
         * Check that the field has been defined in the schema
         */
        $fieldArgsSchemaDefinition = $this->getFieldArgumentsSchemaDefinition($field);
        $objectTypeFieldResolver = $this->getExecutableObjectTypeFieldResolverForField($field);
        if ($fieldArgsSchemaDefinition === null || $objectTypeFieldResolver === null) {
            $objectTypeFieldResolutionFeedbackStore->addError(
                new ObjectTypeFieldResolutionFeedback(
                    $this->getFieldNotResolvedByObjectTypeFeedbackItemResolution(
                        $fieldData,
                        $field,
                    ),
                    $field,
                )
            );
            return null;
        }

        /**
         * Add the default Argument values
         */
        $fieldArgumentNameDefaultValues = $this->getFieldOrDirectiveArgumentNameDefaultValues($fieldArgsSchemaDefinition);
        $fieldData = $this->addDefaultFieldOrDirectiveArguments(
            $fieldData,
            $fieldArgumentNameDefaultValues,
        );

        /**
         * Cast the Arguments, return if any of them produced an error
         */
        $separateObjectTypeFieldResolutionFeedbackStore = new ObjectTypeFieldResolutionFeedbackStore();
        $fieldData = $this->getSchemaCastingService()->castArguments(
            $fieldData,
            $fieldArgsSchemaDefinition,
            $field,
            $separateObjectTypeFieldResolutionFeedbackStore,
        );
        $objectTypeFieldResolutionFeedbackStore->incorporate($separateObjectTypeFieldResolutionFeedbackStore);
        if ($separateObjectTypeFieldResolutionFeedbackStore->getErrors() !== []) {
            return null;
        }

        /**
         * Allow to inject additional arguments
         */
        $fieldData = $objectTypeFieldResolver->prepareFieldData(
            $fieldData,
            $this,
            $field,
            $objectTypeFieldResolutionFeedbackStore
        );
        if ($fieldData === null) {
            return null;
        }

        /**
         * Perform validations
         */
        $separateObjectTypeFieldResolutionFeedbackStore = new ObjectTypeFieldResolutionFeedbackStore();
        $this->validateInvariableOnObjectResolutionFieldData(
            $fieldData,
            $field,
            $separateObjectTypeFieldResolutionFeedbackStore,
        );
        $objectTypeFieldResolutionFeedbackStore->incorporate($separateObjectTypeFieldResolutionFeedbackStore);
        if ($separateObjectTypeFieldResolutionFeedbackStore->getErrors() !== []) {
            return null;
        }

        /**
         * If there is a promise, this method cannot be executed.
         * It will be done later, after promises are resolved
         * to an actual value when resolving the object.
         */
        if (!$hasAnyArgumentReferencingValuePromise) {
            $this->validateVariableOnObjectResolutionFieldData(
                $fieldData,
                $field,
                !$objectTypeFieldResolver->validateMutationOnObject($this, $field->getName()),
                $objectTypeFieldResolutionFeedbackStore,
            );
            if ($objectTypeFieldResolutionFeedbackStore->getErrors() !== []) {
                return null;
            }
        }

        return $fieldData;
    }

    /**
     * Validate those elements of the fieldData
     * which will not be different when evaluated on
     * the schema or the object.
     *
     * Hence, this function needs be called only once
     * at the beginning, and not when the fieldArgs
     * are resolved for the object (eg: because they
     * contain Promises).
     *
     * @param array<string,mixed> $fieldData
     */
    protected function validateInvariableOnObjectResolutionFieldData(
        array $fieldData,
        FieldInterface $field,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        /** @var array */
        $fieldArgsSchemaDefinition = $this->getFieldArgumentsSchemaDefinition($field);
        /** @var ObjectTypeFieldResolverInterface */
        $objectTypeFieldResolver = $this->getExecutableObjectTypeFieldResolverForField($field);

        // Collect the deprecations from the queried fields
        $objectTypeFieldResolver->collectFieldValidationDeprecationMessages($this, $field, $objectTypeFieldResolutionFeedbackStore);

        /**
         * Validations:
         *
         * - no non-existing arg has been provided
         */
        $this->validateOnlyExistingFieldArguments(
            $fieldData,
            $fieldArgsSchemaDefinition,
            $field,
            $objectTypeFieldResolutionFeedbackStore
        );
    }

    /**
     * Validate those elements of the fieldData which will be different
     * when evaluated on the schema or the object (eg: they contain Promises).
     *
     * There are 2 different opportunities when this method can be executed:
     *
     * 1. On the schema:
     *    When the fieldArgs already contain all the resolved data
     *
     * 2. On the object, either when:
     *
     *   1. The fieldArgs contain Promises, which will be resolved
     *      by the time ->resolveValue is executed on the object
     *   2. When a field is dynamically created (eg: `@applyFunction`)
     *
     * @param array<string,mixed> $fieldData
     */
    protected function validateVariableOnObjectResolutionFieldData(
        array $fieldData,
        FieldInterface $field,
        bool $validateMutation,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        /** @var array */
        $fieldArgsSchemaDefinition = $this->getFieldArgumentsSchemaDefinition($field);

        /**
         * Validations:
         *
         * - no mandatory arg is missing or is null
         */
        $separateObjectTypeFieldResolutionFeedbackStore = new ObjectTypeFieldResolutionFeedbackStore();
        $this->validateNonMissingOrNullMandatoryFieldArguments(
            $fieldData,
            $fieldArgsSchemaDefinition,
            $field,
            $separateObjectTypeFieldResolutionFeedbackStore
        );
        $objectTypeFieldResolutionFeedbackStore->incorporate($separateObjectTypeFieldResolutionFeedbackStore);
        if ($separateObjectTypeFieldResolutionFeedbackStore->getErrors() !== []) {
            return;
        }

        /**
         * Validations:
         *
         * - constraints of the arguments
         * - custom constraints of the arguments set by the field resolver
         * - mutation custom validations
         */
        $fieldDataAccessor = $this->createFieldDataAccessor(
            $field,
            $fieldData,
        );
        /** @var ObjectTypeFieldResolverInterface */
        $objectTypeFieldResolver = $this->getExecutableObjectTypeFieldResolverForField($field);
        $this->validateFieldArgumentConstraints(
            $fieldData,
            $objectTypeFieldResolver,
            $field,
            $objectTypeFieldResolutionFeedbackStore,
        );
        $objectTypeFieldResolver->validateFieldKeyValues(
            $this,
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );

        /**
         * If a MutationResolver is declared, let it validate the schema
         */
        $mutationResolver = $objectTypeFieldResolver->getFieldMutationResolver($this, $field->getName());
        if ($mutationResolver !== null && $validateMutation) {
            $fieldDataAccessorForMutation = null;
            try {
                $fieldDataAccessorForMutation = $this->getFieldDataAccessorForMutation($fieldDataAccessor);
            } catch (AbstractDeferredValuePromiseException $deferredValuePromiseException) {
                $objectTypeFieldResolutionFeedbackStore->addError(
                    new ObjectTypeFieldResolutionFeedback(
                        $deferredValuePromiseException->getFeedbackItemResolution(),
                        $deferredValuePromiseException->getAstNode(),
                    )
                );
                return;
            }
            $mutationResolver->validateErrors($fieldDataAccessorForMutation, $objectTypeFieldResolutionFeedbackStore);
        }

        /**
         * Perform validation through checkpoints
         */
        if (
            $checkpoints = $this->getValidationCheckpoints(
                $objectTypeFieldResolver,
                $fieldDataAccessor,
            )
        ) {
            $feedbackItemResolution = $this->getEngine()->validateCheckpoints($checkpoints);
            if ($feedbackItemResolution !== null) {
                $objectTypeFieldResolutionFeedbackStore->addError(
                    new ObjectTypeFieldResolutionFeedback(
                        $feedbackItemResolution,
                        $fieldDataAccessor->getField(),
                    )
                );
                return;
            }
        }
    }

    /**
     * @return CheckpointInterface[]
     */
    protected function getValidationCheckpoints(
        ObjectTypeFieldResolverInterface $objectTypeFieldResolver,
        FieldDataAccessorInterface $fieldDataAccessor,
    ): array {
        $validationCheckpoints = [];
        // Check that mutations can be executed
        $mutationResolver = $objectTypeFieldResolver->getFieldMutationResolver($this, $fieldDataAccessor->getFieldName());
        if ($mutationResolver !== null) {
            $validationCheckpoints[] = $this->getEnabledMutationsCheckpoint();
        }
        return $validationCheckpoints;
    }

    /**
     * Provide a different error message if a particular version was requested,
     * or if not.
     *
     * @param array<string,mixed> $fieldData
     */
    private function getFieldNotResolvedByObjectTypeFeedbackItemResolution(
        array $fieldData,
        FieldInterface $field,
    ): FeedbackItemResolution {
        $useSemanticVersionConstraints = Environment::enableSemanticVersionConstraints()
            && ($versionConstraint = $fieldData[SchemaDefinition::VERSION_CONSTRAINT] ?? null);
        if ($useSemanticVersionConstraints) {
            return new FeedbackItemResolution(
                ErrorFeedbackItemProvider::class,
                ErrorFeedbackItemProvider::E26,
                [
                    $field->getName(),
                    $this->getMaybeNamespacedTypeName(),
                    $versionConstraint,
                ]
            );
        }
        return new FeedbackItemResolution(
            ErrorFeedbackItemProvider::class,
            ErrorFeedbackItemProvider::E16,
            [
                $field->getName(),
                $this->getMaybeNamespacedTypeName(),
            ]
        );
    }

    /**
     * If the key is missing or is `null` then it's an error.
     *
     *   Eg (arg `tags` is mandatory):
     *   `{ setTagsOnPost(tags: null) }` or `{ setTagsOnPost }`
     *
     * If the value is empty, such as '""' or [], then it's OK.
     *
     *   Eg: `{ setTagsOnPost(tags: []) }`
     *
     * @param array<string,mixed> $fieldArgsSchemaDefinition
     */
    private function validateNonMissingOrNullMandatoryFieldArguments(
        array $fieldData,
        array $fieldArgsSchemaDefinition,
        FieldInterface $field,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        $mandatoryFieldArgNames = $this->getFieldOrDirectiveMandatoryArgumentNames($fieldArgsSchemaDefinition);
        $missingMandatoryFieldArgNames = array_values(array_filter(
            $mandatoryFieldArgNames,
            fn (string $fieldArgName) => ($fieldData[$fieldArgName] ?? null) === null
        ));
        foreach ($missingMandatoryFieldArgNames as $missingMandatoryFieldArgName) {
            $objectTypeFieldResolutionFeedbackStore->addError(
                new ObjectTypeFieldResolutionFeedback(
                    new FeedbackItemResolution(
                        ErrorFeedbackItemProvider::class,
                        ErrorFeedbackItemProvider::E29,
                        [
                            $missingMandatoryFieldArgName,
                            $field->getName(),
                            $this->getMaybeNamespacedTypeName(),
                        ]
                    ),
                    $field->getArgument($missingMandatoryFieldArgName)?->getValueAST()
                        ?? $field->getArgument($missingMandatoryFieldArgName)
                        ?? $field,
                )
            );
        }
    }

    /**
     * Return an error if the query contains argument(s) that
     * does not exist in the field.
     *
     * @param array<string,mixed> $fieldArgsSchemaDefinition
     */
    private function validateOnlyExistingFieldArguments(
        array $fieldData,
        array $fieldArgsSchemaDefinition,
        FieldInterface $field,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        $nonExistingArgNames = array_values(array_diff(
            array_keys($fieldData),
            array_keys($fieldArgsSchemaDefinition)
        ));
        foreach ($nonExistingArgNames as $nonExistingArgName) {
            $objectTypeFieldResolutionFeedbackStore->addError(
                new ObjectTypeFieldResolutionFeedback(
                    new FeedbackItemResolution(
                        ErrorFeedbackItemProvider::class,
                        ErrorFeedbackItemProvider::E27,
                        [
                            $field->getName(),
                            $this->getMaybeNamespacedTypeName(),
                            $nonExistingArgName,
                        ]
                    ),
                    $field->getArgument($nonExistingArgName) ?? $field,
                )
            );
        }
    }

    /**
     * Validate the constraints for the field arguments
     */
    private function validateFieldArgumentConstraints(
        array $fieldData,
        ObjectTypeFieldResolverInterface $objectTypeFieldResolver,
        FieldInterface $field,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        $fieldArgNameTypeResolvers = $objectTypeFieldResolver->getConsolidatedFieldArgNameTypeResolvers($this, $field->getName());
        foreach ($fieldData as $argName => $argValue) {
            $fieldArgTypeResolver = $fieldArgNameTypeResolvers[$argName];
            $astNode = $field->getArgument($argName) ?? $field;
            /**
             * If the arg is an InputObject, let it perform validations on its input fields.
             */
            if ($fieldArgTypeResolver instanceof InputObjectTypeResolverInterface) {
                $fieldArgTypeResolver->validateInputValue(
                    $argValue,
                    $astNode,
                    $objectTypeFieldResolutionFeedbackStore,
                );
            }
            $objectTypeFieldResolver->validateFieldArgValue(
                $this,
                $field->getName(),
                $argName,
                $argValue,
                $astNode,
                $objectTypeFieldResolutionFeedbackStore,
            );
        }
    }

    /**
     * @param array<string,mixed> $fieldData
     */
    public function createFieldDataAccessor(
        FieldInterface $field,
        array $fieldData,
    ): FieldDataAccessorInterface {
        return new FieldDataAccessor(
            $field,
            $fieldData,
        );
    }

    /**
     * The mutation resolver might expect to receive the data properties
     * directly (eg: "title", "content" and "status"), and these may be
     * contained under a subproperty (eg: "input") from the original fieldData.
     *
     * @throws AbstractDeferredValuePromiseException
     */
    public function getFieldDataAccessorForMutation(
        FieldDataAccessorInterface $fieldDataAccessor,
    ): FieldDataAccessorInterface {
        if (!$this->fieldDataAccessorForMutationCache->contains($fieldDataAccessor)) {
            $fieldDataAccessorForMutation = $fieldDataAccessor;
            $executableObjectTypeFieldResolver = $this->getExecutableObjectTypeFieldResolverForField($fieldDataAccessor->getField());
            if ($executableObjectTypeFieldResolver->extractInputObjectFieldForMutation($this, $fieldDataAccessor->getFieldName())) {
                $inputObjectSubpropertyName = $executableObjectTypeFieldResolver->getFieldDataInputObjectSubpropertyName($this, $fieldDataAccessor->getField());
                if ($inputObjectSubpropertyName) {
                    $fieldDataAccessorForMutation = new InputObjectSubpropertyFieldDataAccessor(
                        $fieldDataAccessor->getField(),
                        $inputObjectSubpropertyName,
                        $fieldDataAccessor->getFieldArgs(),
                    );
                }
            }
            $this->fieldDataAccessorForMutationCache[$fieldDataAccessor] = $fieldDataAccessorForMutation;
        }
        return $this->fieldDataAccessorForMutationCache[$fieldDataAccessor];
    }
}
