<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolvers\ObjectType;

use Exception;
use PoP\ComponentModel\App;
use PoP\ComponentModel\AttachableExtensions\AttachableExtensionGroups;
use PoP\ComponentModel\Checkpoints\CheckpointInterface;
use PoP\ComponentModel\Engine\EngineInterface;
use PoP\ComponentModel\Engine\EngineIterationFieldSet;
use PoP\ComponentModel\FeedbackItemProviders\ErrorFeedbackItemProvider;
use PoP\ComponentModel\FeedbackItemProviders\FieldResolutionErrorFeedbackItemProvider;
use PoP\ComponentModel\Feedback\EngineIterationFeedbackStore;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedback;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\Feedback\SchemaFeedback;
use PoP\ComponentModel\FieldResolvers\InterfaceType\InterfaceTypeFieldResolverInterface;
use PoP\ComponentModel\FieldResolvers\ObjectType\ObjectTypeFieldResolverInterface;
use PoP\ComponentModel\Module;
use PoP\ComponentModel\ModuleConfiguration;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP\ComponentModel\ObjectSerialization\ObjectSerializationManagerInterface;
use PoP\ComponentModel\QueryResolution\FieldDataAccessWildcardObjectFactory;
use PoP\ComponentModel\QueryResolution\FieldDataAccessor;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\QueryResolution\InputObjectSubpropertyFieldDataAccessor;
use PoP\ComponentModel\Resolvers\ObjectTypeOrFieldDirectiveResolverTrait;
use PoP\ComponentModel\Response\OutputServiceInterface;
use PoP\ComponentModel\Schema\SchemaCastingServiceInterface;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\StandaloneCheckpoints\EnabledMutationsCheckpoint;
use PoP\ComponentModel\TypeResolvers\AbstractRelationalTypeResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InterfaceType\InterfaceTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\DangerouslyNonSpecificScalarTypeScalarTypeResolver;
use PoP\GraphQLParser\Exception\AbstractValueResolutionPromiseException;
use PoP\GraphQLParser\FeedbackItemProviders\GraphQLSpecErrorFeedbackItemProvider;
use PoP\GraphQLParser\Spec\Parser\Ast\Directive;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\LeafField;
use PoP\GraphQLParser\Spec\Parser\Location;
use PoP\Root\Exception\AbstractClientException;
use PoP\ComponentModel\Feedback\FeedbackItemResolution;
use SplObjectStorage;
use stdClass;

abstract class AbstractObjectTypeResolver extends AbstractRelationalTypeResolver implements ObjectTypeResolverInterface
{
    use ObjectTypeOrFieldDirectiveResolverTrait;

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
     * @var array<string,string[]>
     */
    private array $fieldNamesResolvedByObjectTypeFieldResolver = [];
    /**
     * @var InterfaceTypeFieldResolverInterface[]|null
     */
    protected ?array $implementedInterfaceTypeFieldResolversCache = null;
    /**
     * @var SplObjectStorage<FieldInterface,array<string,mixed>|null>
     */
    protected SplObjectStorage $fieldArgsCache;
    /**
     * @var SplObjectStorage<FieldDataAccessorInterface,FieldDataAccessorInterface>
     */
    protected SplObjectStorage $fieldDataAccessorForMutationCache;
    /**
     * @var SplObjectStorage<FieldInterface,?FieldDataAccessorInterface>
     */
    protected SplObjectStorage $fieldDataAccessorForObjectCorrespondingToEngineIterationCache;

    /**
     * @var SplObjectStorage<FieldInterface,SplObjectStorage<ObjectTypeResolverInterface,SplObjectStorage<object,array<string,mixed>>>|null>
     */
    protected SplObjectStorage $fieldObjectTypeResolverObjectFieldDataCache;

    protected ?Location $schemaGenerationLocation = null;

    private ?DangerouslyNonSpecificScalarTypeScalarTypeResolver $dangerouslyNonSpecificScalarTypeScalarTypeResolver = null;
    private ?OutputServiceInterface $outputService = null;
    private ?ObjectSerializationManagerInterface $objectSerializationManager = null;
    private ?SchemaCastingServiceInterface $schemaCastingService = null;
    private ?EngineInterface $engine = null;

    final protected function getDangerouslyNonSpecificScalarTypeScalarTypeResolver(): DangerouslyNonSpecificScalarTypeScalarTypeResolver
    {
        if ($this->dangerouslyNonSpecificScalarTypeScalarTypeResolver === null) {
            /** @var DangerouslyNonSpecificScalarTypeScalarTypeResolver */
            $dangerouslyNonSpecificScalarTypeScalarTypeResolver = $this->instanceManager->getInstance(DangerouslyNonSpecificScalarTypeScalarTypeResolver::class);
            $this->dangerouslyNonSpecificScalarTypeScalarTypeResolver = $dangerouslyNonSpecificScalarTypeScalarTypeResolver;
        }
        return $this->dangerouslyNonSpecificScalarTypeScalarTypeResolver;
    }
    final protected function getOutputService(): OutputServiceInterface
    {
        if ($this->outputService === null) {
            /** @var OutputServiceInterface */
            $outputService = $this->instanceManager->getInstance(OutputServiceInterface::class);
            $this->outputService = $outputService;
        }
        return $this->outputService;
    }
    final protected function getObjectSerializationManager(): ObjectSerializationManagerInterface
    {
        if ($this->objectSerializationManager === null) {
            /** @var ObjectSerializationManagerInterface */
            $objectSerializationManager = $this->instanceManager->getInstance(ObjectSerializationManagerInterface::class);
            $this->objectSerializationManager = $objectSerializationManager;
        }
        return $this->objectSerializationManager;
    }
    final protected function getSchemaCastingService(): SchemaCastingServiceInterface
    {
        if ($this->schemaCastingService === null) {
            /** @var SchemaCastingServiceInterface */
            $schemaCastingService = $this->instanceManager->getInstance(SchemaCastingServiceInterface::class);
            $this->schemaCastingService = $schemaCastingService;
        }
        return $this->schemaCastingService;
    }
    final protected function getEngine(): EngineInterface
    {
        if ($this->engine === null) {
            /** @var EngineInterface */
            $engine = $this->instanceManager->getInstance(EngineInterface::class);
            $this->engine = $engine;
        }
        return $this->engine;
    }

    public function __construct()
    {
        $this->fieldArgsCache = new SplObjectStorage();
        $this->fieldObjectTypeResolverObjectFieldDataCache = new SplObjectStorage();
        $this->fieldDataAccessorForMutationCache = new SplObjectStorage();
        $this->fieldDataAccessorForObjectCorrespondingToEngineIterationCache = new SplObjectStorage();
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
            if (!$typeResolverDecorator->enabled($this)) {
                continue;
            }
            /**
             * `array_merge_recursive` so that if 2 different decorators
             * add a directive for the same field, the results are merged
             * together, not override each other.
             */
            $objectTypeResolverMandatoryDirectivesForFields = $typeResolverDecorator->getMandatoryDirectivesForFields($this);
            $mandatoryDirectivesForFields = array_merge_recursive(
                $mandatoryDirectivesForFields,
                $objectTypeResolverMandatoryDirectivesForFields
            );
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

    final public function getFieldTypeResolver(
        FieldInterface $field,
    ): ?ConcreteTypeResolverInterface {
        $executableObjectTypeFieldResolver = $this->getExecutableObjectTypeFieldResolverForField($field);
        if ($executableObjectTypeFieldResolver === null) {
            return null;
        }

        return $executableObjectTypeFieldResolver->getFieldTypeResolver($this, $field->getName());
    }

    final public function getFieldTypeModifiers(
        FieldInterface $field,
    ): ?int {
        $executableObjectTypeFieldResolver = $this->getExecutableObjectTypeFieldResolverForField($field);
        if ($executableObjectTypeFieldResolver === null) {
            return null;
        }

        return $executableObjectTypeFieldResolver->getFieldTypeModifiers($this, $field->getName());
    }

    final public function getFieldMutationResolver(
        FieldInterface $field,
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
     * FieldDataAccessor with the fieldArgs already normalized.
     *
     * If executed within a FieldResolver we will (most likely)
     * receive a Field, and we can assume there's no need to
     * normalize the values, they will be coded/provided as required.
     */
    final public function resolveValue(
        object $object,
        FieldInterface|FieldDataAccessorInterface $fieldOrFieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): mixed {
        $fieldDataAccessor = null;
        $isFieldDataAccessor = $fieldOrFieldDataAccessor instanceof FieldDataAccessorInterface;
        if ($isFieldDataAccessor) {
            /** @var FieldDataAccessorInterface */
            $fieldDataAccessor = $fieldOrFieldDataAccessor;
            $field = $fieldDataAccessor->getField();

            /**
             * Already validated it exists in @validate
             *
             * @var ObjectTypeFieldResolverInterface
             */
            $objectTypeFieldResolver = $this->getExecutableObjectTypeFieldResolverForField($field);
        } else {
            /**
             * If executed within a FieldResolver we will (most certainly)
             * receive a Field and not a FieldDataAccessor
             *
             * @var FieldInterface
             */
            $field = $fieldOrFieldDataAccessor;

            /**
             * Validate the field exists
             */
            $objectTypeFieldResolver = $this->getExecutableObjectTypeFieldResolverForField($field);
            if ($objectTypeFieldResolver === null) {
                $objectTypeFieldResolutionFeedbackStore->addError(
                    new ObjectTypeFieldResolutionFeedback(
                        $this->getFieldNotResolvedByObjectTypeFeedbackItemResolution($field),
                        $field,
                    )
                );
                return null;
            }

            $fieldArgs = $this->getFieldArgs(
                $field,
                $objectTypeFieldResolutionFeedbackStore
            );
            if ($fieldArgs === null) {
                return null;
            }

            $fieldDataAccessor = $this->createFieldDataAccessor(
                $field,
                $fieldArgs
            );
        }

        $objectID = $this->getID($object);
        if ($objectID === null) {
            return null;
        }

        /**
         * Resolve promises, or customize the fieldArgs for the object
         */
        $fieldDataAccessor = $this->maybeGetFieldDataAccessorForObject(
            $fieldDataAccessor,
            $objectID,
            $objectTypeFieldResolutionFeedbackStore,
        );
        if ($fieldDataAccessor === null) {
            return null;
        }

        /**
         * Custom validations by the field resolver
         */
        $this->validateFieldArgsForObject(
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
                    $serializedValue = $this->getObjectSerializationManager()->serialize($value);
                    $valueAsString = $serializedValue instanceof stdClass
                        ? $this->getOutputService()->jsonEncodeArrayOrStdClassValue($serializedValue)
                        : $serializedValue;
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
     * Either if the fieldArgs references a Promise (eg: a value from `@export`),
     * or if the fieldArgs are customized to the object (eg: a nested mutation),
     * then produce the corresponding customized fieldDataAccessor.
     *
     * ------------------------------------------------------------------------
     *
     * Among Promises, we have:
     *
     * 1. Resolved Field Value Reference:
     *    It must be resolved at the Object level, so its resolution here
     *    is suitable.
     *
     * 2. Dynamic Variable Reference:
     *    It can be resolved at the Engine iteration level, but it's easier
     *    to resolve it here. To optimize it (and avoid executing the same
     *    validation/casting for different objects, when the results are
     *    always the same), the result is cached after the 1st object,
     *    and re-used thereafter.
     */
    protected function maybeGetFieldDataAccessorForObject(
        FieldDataAccessorInterface $fieldDataAccessor,
        string|int $id,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): ?FieldDataAccessorInterface {
        $field = $fieldDataAccessor->getField();
        if (!$field->hasArgumentReferencingPromise()) {
            return $fieldDataAccessor;
        }

        /**
         * If no Promise needs to be resolved on the object, then
         * we can use the same response for all objects.
         */
        if (
            !$field->hasArgumentReferencingResolvedOnObjectPromise()
            && $this->fieldDataAccessorForObjectCorrespondingToEngineIterationCache->contains($field)
        ) {
            return $this->fieldDataAccessorForObjectCorrespondingToEngineIterationCache[$field];
        }

        /**
         * The current object ID/Field for which to retrieve the dynamic variable for.
         */
        $this->loadObjectResolvedDynamicVariablesInAppState($field, $id);

        $fieldArgs = null;
        try {
            $fieldArgs = $fieldDataAccessor->getFieldArgs();
        } catch (AbstractValueResolutionPromiseException $valueResolutionPromiseException) {
            $objectTypeFieldResolutionFeedbackStore->addError(
                new ObjectTypeFieldResolutionFeedback(
                    FeedbackItemResolution::fromUpstreamFeedbackItemResolution($valueResolutionPromiseException->getFeedbackItemResolution()),
                    $valueResolutionPromiseException->getAstNode(),
                )
            );
        }

        $this->resetObjectResolvedDynamicVariablesInAppState();

        if ($fieldArgs === null) {
            $this->fieldDataAccessorForObjectCorrespondingToEngineIterationCache[$field] = null;
            return null;
        }

        /**
         * Cast the Arguments, return if any of them produced an error
         *
         * @var array<string,mixed>
         */
        $fieldArgsSchemaDefinition = $this->getFieldArgumentsSchemaDefinition($field);
        $fieldArgs = $this->getSchemaCastingService()->castArguments(
            $fieldArgs,
            $fieldArgsSchemaDefinition,
            $field,
            $objectTypeFieldResolutionFeedbackStore,
        );
        if ($objectTypeFieldResolutionFeedbackStore->getErrors() !== []) {
            $this->fieldDataAccessorForObjectCorrespondingToEngineIterationCache[$field] = null;
            return null;
        }

        $this->validateVariableOnObjectResolutionFieldData(
            $fieldArgs,
            $field,
            false, // Mutation validation will be performed always in validateFieldArgsForObject
            $objectTypeFieldResolutionFeedbackStore,
        );
        if ($objectTypeFieldResolutionFeedbackStore->getErrors() !== []) {
            $this->fieldDataAccessorForObjectCorrespondingToEngineIterationCache[$field] = null;
            return null;
        }

        /**
         * Re-recreate the data, containing the casted arguments
         */
        $fieldDataAccessorForObject = $this->createFieldDataAccessor(
            $field,
            $fieldArgs,
        );

        $this->fieldDataAccessorForObjectCorrespondingToEngineIterationCache[$field] = $fieldDataAccessorForObject;
        return $fieldDataAccessorForObject;
    }

    /**
     * Validate the field data for the object
     */
    protected function validateFieldArgsForObject(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolverInterface $objectTypeFieldResolver,
        object $object,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        $fieldArgs = null;
        try {
            $fieldArgs = $fieldDataAccessor->getFieldArgs();
        } catch (AbstractValueResolutionPromiseException $valueResolutionPromiseException) {
            $objectTypeFieldResolutionFeedbackStore->addError(
                new ObjectTypeFieldResolutionFeedback(
                    FeedbackItemResolution::fromUpstreamFeedbackItemResolution($valueResolutionPromiseException->getFeedbackItemResolution()),
                    $valueResolutionPromiseException->getAstNode(),
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
                $fieldDataAccessor,
                $object,
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
        $objectTypeFieldResolver->validateFieldArgsForObject($this, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);

        /**
         * If a MutationResolver is declared, let it validate the schema
         */
        $mutationResolver = $objectTypeFieldResolver->getFieldMutationResolver($this, $fieldDataAccessor->getFieldName());
        if ($mutationResolver !== null && $objectTypeFieldResolver->validateMutationOnObject($this, $fieldDataAccessor->getFieldName())) {
            // Validate on the object
            $fieldDataAccessorForMutation = null;
            try {
                $fieldArgsForMutationForObject = $objectTypeFieldResolver->prepareFieldArgsForMutationForObject(
                    $fieldArgs,
                    $this,
                    $fieldDataAccessor->getField(),
                    $object,
                );
                $fieldDataAccessorForMutation = $this->getFieldDataAccessorForMutation(
                    $this->createFieldDataAccessor(
                        $fieldDataAccessor->getField(),
                        $fieldArgsForMutationForObject
                    )
                );
            } catch (AbstractValueResolutionPromiseException $valueResolutionPromiseException) {
                $objectTypeFieldResolutionFeedbackStore->addError(
                    new ObjectTypeFieldResolutionFeedback(
                        FeedbackItemResolution::fromUpstreamFeedbackItemResolution($valueResolutionPromiseException->getFeedbackItemResolution()),
                        $valueResolutionPromiseException->getAstNode(),
                    )
                );
                return;
            }
            $mutationResolver->validate($fieldDataAccessorForMutation, $objectTypeFieldResolutionFeedbackStore);
        }
    }

    /**
     * @return array<string,mixed>|null
     */
    final protected function getFieldArgumentsSchemaDefinition(FieldInterface $field): ?array
    {
        $fieldSchemaDefinition = $this->getFieldSchemaDefinition($field);
        if ($fieldSchemaDefinition === null) {
            return null;
        }
        return $fieldSchemaDefinition[SchemaDefinition::ARGS] ?? [];
    }

    /**
     * The "executable" FieldResolver is the first one in the list
     * for each field, as according to their priority.
     *
     * @return array<string,ObjectTypeFieldResolverInterface> Key: fieldName, Value: FieldResolver
     */
    final public function getExecutableObjectTypeFieldResolversByField(bool $global): array
    {
        $cacheKey = $global ? 'global' : 'non-global';
        if (($this->executableObjectTypeFieldResolversByFieldCache[$cacheKey] ?? null) === null) {
            $this->executableObjectTypeFieldResolversByFieldCache[$cacheKey] = $this->doGetExecutableObjectTypeFieldResolversByField($global);
        }
        return $this->executableObjectTypeFieldResolversByFieldCache[$cacheKey];
    }

    /**
     * @return array<string,ObjectTypeFieldResolverInterface> Key: fieldName, Value: FieldResolver
     */
    private function doGetExecutableObjectTypeFieldResolversByField(bool $global): array
    {
        $objectTypeFieldResolvers = [];
        foreach ($this->getObjectTypeFieldResolversByField($global) as $fieldName => $fieldObjectTypeFieldResolvers) {
            // Get the first item from the list of resolvers. That's the one that will be executed
            $objectTypeFieldResolvers[$fieldName] = $fieldObjectTypeFieldResolvers[0];
        }
        return $objectTypeFieldResolvers;
    }

    /**
     * @return array<string,ObjectTypeFieldResolverInterface[]> Key: fieldName, Value: FieldResolver
     */
    final public function getObjectTypeFieldResolversByField(bool $global): array
    {
        $cacheKey = $global ? 'global' : 'non-global';
        if (($this->objectTypeFieldResolversByFieldCache[$cacheKey] ?? null) === null) {
            $this->objectTypeFieldResolversByFieldCache[$cacheKey] = $this->doGetObjectTypeFieldResolversByField($global);
        }
        return $this->objectTypeFieldResolversByFieldCache[$cacheKey];
    }

    /**
     * @return array<string,ObjectTypeFieldResolverInterface[]> Key: fieldName, Value: FieldResolver
     */
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

    /**
     * @return array<string,ObjectTypeFieldResolverInterface[]> Key: fieldName, Value: FieldResolver
     */
    final protected function getAllObjectTypeFieldResolversByField(): array
    {
        if ($this->allObjectTypeFieldResolversByFieldCache === null) {
            $this->allObjectTypeFieldResolversByFieldCache = $this->calculateAllObjectTypeFieldResolvers();
        }
        return $this->allObjectTypeFieldResolversByFieldCache;
    }

    /**
     * @return array<string,ObjectTypeFieldResolverInterface[]> Key: fieldName, Value: FieldResolver
     */
    private function calculateAllObjectTypeFieldResolvers(): array
    {
        $schemaObjectTypeFieldResolvers = [];

        $attachableExtensionManager = $this->getAttachableExtensionManager();

        // Get the ObjectTypeFieldResolvers attached to this ObjectTypeResolver
        $class = get_class($this->getTypeResolverToCalculateSchema());
        // Iterate classes from the current class towards the parent classes until finding typeResolver that satisfies processing this field
        do {
            /** @var ObjectTypeFieldResolverInterface[] */
            $attachedObjectTypeFieldResolvers = $attachableExtensionManager->getAttachedExtensions($class, AttachableExtensionGroups::OBJECT_TYPE_FIELD_RESOLVERS);
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
            $this->implementedInterfaceTypeFieldResolversCache = $this->consolidateAllImplementedInterfaceTypeFieldResolvers(
                $this->calculateAllImplementedInterfaceTypeFieldResolvers()
            );
        }
        return $this->implementedInterfaceTypeFieldResolversCache;
    }

    /**
     * Allow to override the interfaces, as for removing
     * the IdentifiableObject interface whenever not exposing field "id"
     * in an Object Type.
     *
     * @param InterfaceTypeFieldResolverInterface[] $interfaceTypeFieldResolvers
     * @return InterfaceTypeFieldResolverInterface[]
     */
    protected function consolidateAllImplementedInterfaceTypeFieldResolvers(
        array $interfaceTypeFieldResolvers,
    ): array {
        return $interfaceTypeFieldResolvers;
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
                        // Add under class as to mimic `array_unique` for object
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
                // Add under class as to mimic `array_unique` for object
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
            /**
             * Because field `_isTypeOrImplements` may be added sometimes
             * (when invoked internally) and otherwise not, then just the
             * field name is not enough to cache: The resolver is specific
             * to the Field object
             */
            $cacheKey = $field->getName() . ' #' . spl_object_hash($field);
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

    /**
     * @return ObjectTypeFieldResolverInterface[]
     */
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
                /**
                 * Do NOT use ASTNodesFactory::getNonSpecificLocation(), as that
                 * represents "field executed internally", but here is a different
                 * purpose, which is to calculate the fields for the Schema.
                 * Then, internal fields (such as `_implements`, `_isInUnionType`,
                 * `_isTypeOrImplements` and `_isTypeOrImplementsAll`) can be executed
                 * only internally.
                 *
                 * @see layers/Engine/packages/component-model/src/FieldResolvers/ObjectType/CoreGlobalObjectTypeFieldResolver.php
                 */
                $this->getSchemaGenerationLocation()
            );
        }

        $attachableExtensionManager = $this->getAttachableExtensionManager();

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
            $attachedObjectTypeFieldResolvers = array_reverse($attachableExtensionManager->getAttachedExtensions($class, AttachableExtensionGroups::OBJECT_TYPE_FIELD_RESOLVERS));
            foreach ($attachedObjectTypeFieldResolvers as $objectTypeFieldResolver) {
                $extensionFieldNames = $this->getFieldNamesResolvedByObjectTypeFieldResolver($objectTypeFieldResolver);
                if (!in_array($fieldName, $extensionFieldNames)) {
                    continue;
                }
                // Check that the fieldResolver can handle the field based on other parameters (eg: "version" in the fieldArgs)
                if (!$objectTypeFieldResolver->resolveCanProcessField($this, $field)) {
                    continue;
                }
                $extensionPriority = $objectTypeFieldResolver->getPriorityToAttachToClasses();
                $classTypeResolverPriorities[] = $extensionPriority;
                $classObjectTypeFieldResolvers[] = $objectTypeFieldResolver;
            }
            // Sort the found units by their priority, and then add to the stack of all units, for all classes
            // Higher priority means they execute first!
            array_multisort($classTypeResolverPriorities, SORT_DESC, SORT_NUMERIC, $classObjectTypeFieldResolvers);
            // Add under class as to mimic `array_unique` for object
            foreach ($classObjectTypeFieldResolvers as $classObjectTypeFieldResolver) {
                $objectTypeFieldResolvers[get_class($classObjectTypeFieldResolver)] = $classObjectTypeFieldResolver;
            }
            // Continue iterating for the class parents
        } while ($class = get_parent_class($class));

        // Return all the units that resolve the fieldName
        return array_values($objectTypeFieldResolvers);
    }

    protected function getSchemaGenerationLocation(): Location
    {
        if ($this->schemaGenerationLocation === null) {
            $this->schemaGenerationLocation = new Location(-2, -2);
        }
        return $this->schemaGenerationLocation;
    }

    /**
     * Convert the FieldArgs into its corresponding FieldDataAccessor, which integrates
     * within the default values and coerces them according to the schema.
     *
     * @see FieldDataAccessProvider
     *
     * @param array<string|int> $ids
     * @param array<string|int,object> $idObjects
     * @return SplObjectStorage<ObjectTypeResolverInterface,SplObjectStorage<object,array<string,mixed>>>|null
     */
    protected function doGetObjectTypeResolverObjectFieldData(
        FieldInterface $field,
        array $ids,
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
                        $this->getFieldNotResolvedByObjectTypeFeedbackItemResolution($field),
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
                $ids,
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
        $fieldArgs = $this->getFieldArgs(
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
        /** @var array<string,mixed> $fieldArgs */

        $objectFieldData[$wildcardObject] = $fieldArgs;
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
        $fieldArgs = $this->getFieldArgs(
            $field,
            $objectTypeFieldResolutionFeedbackStore,
        );
        $engineIterationFeedbackStore->schemaFeedbackStore->incorporateFromObjectTypeFieldResolutionFeedbackStore(
            $objectTypeFieldResolutionFeedbackStore,
            $this,
            [$field],
        );
        /** @var array<string,mixed> $fieldArgs */
        if ($objectTypeFieldResolutionFeedbackStore->getErrors() !== []) {
            $this->fieldObjectTypeResolverObjectFieldDataCache[$field] = null;
            return null;
        }

        foreach ($remainingObjectIDs as $id) {
            $object = $idObjects[$id];
            // Clone array
            $fieldArgsForObject = array_merge([], $fieldArgs);
            $fieldArgsForObject = $executableObjectTypeFieldResolver->prepareFieldArgsForObject(
                $fieldArgsForObject,
                $this,
                $field,
                $object,
            );
            $objectFieldData[$object] = $fieldArgsForObject;
            // Store in the cache
            $objectFieldDataCache[$object] = $fieldArgsForObject;
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
    protected function getFieldArgs(
        FieldInterface $field,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): ?array {
        if (!$this->fieldArgsCache->contains($field)) {
            $this->fieldArgsCache[$field] = $this->doGetFieldArgs(
                $field,
                $objectTypeFieldResolutionFeedbackStore,
            );
        }
        return $this->fieldArgsCache[$field];
    }

    /**
     * Retrieve the Field Arguments data, adding the default values
     * coercing all values, and allowing to apply customizations
     *
     * @return array<string,mixed>|null null if there was a validation error
     */
    protected function doGetFieldArgs(
        FieldInterface $field,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): ?array {
        $fieldArgs = $field->getArgumentKeyValues();

        /**
         * It has already been validated that the field exists
         * when parsing the Field Data in doGetObjectTypeResolverObjectFieldData
         *
         * @var ObjectTypeFieldResolverInterface
         */
        $objectTypeFieldResolver = $this->getExecutableObjectTypeFieldResolverForField($field);
        /** @var array<string,mixed> */
        $fieldArgsSchemaDefinition = $this->getFieldArgumentsSchemaDefinition($field);

        /**
         * Add the default Argument values
         */
        $fieldArgumentNameDefaultValues = $this->getFieldOrDirectiveArgumentNameDefaultValues($fieldArgsSchemaDefinition);
        $fieldArgs = $this->addDefaultFieldOrDirectiveArguments(
            $fieldArgs,
            $fieldArgumentNameDefaultValues,
        );

        /**
         * Cast the Arguments, return if any of them produced an error
         */
        $errorCount = $objectTypeFieldResolutionFeedbackStore->getErrorCount();
        $fieldArgs = $this->getSchemaCastingService()->castArguments(
            $fieldArgs,
            $fieldArgsSchemaDefinition,
            $field,
            $objectTypeFieldResolutionFeedbackStore,
        );
        if ($objectTypeFieldResolutionFeedbackStore->getErrorCount() > $errorCount) {
            return null;
        }

        /**
         * Allow to inject additional arguments
         */
        $fieldArgs = $objectTypeFieldResolver->prepareFieldArgs(
            $fieldArgs,
            $this,
            $field,
            $objectTypeFieldResolutionFeedbackStore
        );
        if ($fieldArgs === null) {
            return null;
        }

        /**
         * Perform validations
         */
        $this->validateInvariableOnObjectResolutionFieldData(
            $fieldArgs,
            $field,
            $objectTypeFieldResolutionFeedbackStore,
        );
        if ($objectTypeFieldResolutionFeedbackStore->getErrorCount() > $errorCount) {
            return null;
        }

        /**
         * If there is a promise, this method cannot be executed.
         * It will be done later, after promises are resolved
         * to an actual value when resolving the object.
         */
        if (!$field->hasArgumentReferencingPromise()) {
            $this->validateVariableOnObjectResolutionFieldData(
                $fieldArgs,
                $field,
                !$objectTypeFieldResolver->validateMutationOnObject($this, $field->getName()),
                $objectTypeFieldResolutionFeedbackStore,
            );
            if ($objectTypeFieldResolutionFeedbackStore->getErrors() !== []) {
                return null;
            }
        }

        return $fieldArgs;
    }

    /**
     * Validate those elements of the fieldArgs
     * which will not be different when evaluated on
     * the schema or the object.
     *
     * Hence, this function needs be called only once
     * at the beginning, and not when the fieldArgs
     * are resolved for the object (eg: because they
     * contain Promises).
     *
     * @param array<string,mixed> $fieldArgs
     */
    protected function validateInvariableOnObjectResolutionFieldData(
        array $fieldArgs,
        FieldInterface $field,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        /** @var array<string,mixed> */
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
            $fieldArgs,
            $fieldArgsSchemaDefinition,
            $field,
            $objectTypeFieldResolutionFeedbackStore
        );
    }

    /**
     * Validate those elements of the fieldArgs which will be different
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
     *   2. When a field is dynamically created (eg: `@applyField`)
     *
     * @param array<string,mixed> $fieldArgs
     */
    protected function validateVariableOnObjectResolutionFieldData(
        array $fieldArgs,
        FieldInterface $field,
        bool $validateMutation,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        /** @var array<string,mixed> */
        $fieldArgsSchemaDefinition = $this->getFieldArgumentsSchemaDefinition($field);

        /**
         * Validations:
         *
         * - no mandatory arg is missing or is null
         */
        $errorCount = $objectTypeFieldResolutionFeedbackStore->getErrorCount();
        $this->validateNonMissingOrNullMandatoryFieldArguments(
            $fieldArgs,
            $fieldArgsSchemaDefinition,
            $field,
            $objectTypeFieldResolutionFeedbackStore
        );
        if ($objectTypeFieldResolutionFeedbackStore->getErrorCount() > $errorCount) {
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
            $fieldArgs,
        );
        /** @var ObjectTypeFieldResolverInterface */
        $objectTypeFieldResolver = $this->getExecutableObjectTypeFieldResolverForField($field);
        $this->validateFieldArgumentConstraints(
            $fieldArgs,
            $fieldArgsSchemaDefinition,
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
            } catch (AbstractValueResolutionPromiseException $valueResolutionPromiseException) {
                $objectTypeFieldResolutionFeedbackStore->addError(
                    new ObjectTypeFieldResolutionFeedback(
                        FeedbackItemResolution::fromUpstreamFeedbackItemResolution($valueResolutionPromiseException->getFeedbackItemResolution()),
                        $valueResolutionPromiseException->getAstNode(),
                    )
                );
                return;
            }
            $mutationResolver->validate($fieldDataAccessorForMutation, $objectTypeFieldResolutionFeedbackStore);
        }

        if ($objectTypeFieldResolutionFeedbackStore->getErrorCount() > $errorCount) {
            return;
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
            $validationCheckpoints[] = new EnabledMutationsCheckpoint($fieldDataAccessor->getField());
        }
        return $validationCheckpoints;
    }

    /**
     * Provide a different error message if:
     *
     * - A particular version was requested on the Field
     * - (To be overridden by SuperRoot) It represents an Operation
     */
    public function getFieldNotResolvedByObjectTypeFeedbackItemResolution(
        FieldInterface $field,
    ): FeedbackItemResolution {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $useSemanticVersionConstraints = $moduleConfiguration->enableSemanticVersionConstraints()
            && $field->hasArgument(SchemaDefinition::VERSION_CONSTRAINT);
        if ($useSemanticVersionConstraints) {
            $versionConstraint = $field->getArgumentValue(SchemaDefinition::VERSION_CONSTRAINT);
            return new FeedbackItemResolution(
                ErrorFeedbackItemProvider::class,
                ErrorFeedbackItemProvider::E2,
                [
                    $field->getName(),
                    $this->getMaybeNamespacedTypeName(),
                    $versionConstraint,
                ]
            );
        }
        return new FeedbackItemResolution(
            GraphQLSpecErrorFeedbackItemProvider::class,
            GraphQLSpecErrorFeedbackItemProvider::E_5_3_1,
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
     * @param array<string,mixed> $fieldArgs
     */
    private function validateNonMissingOrNullMandatoryFieldArguments(
        array $fieldArgs,
        array $fieldArgsSchemaDefinition,
        FieldInterface $field,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        $mandatoryFieldArgNames = $this->getFieldOrDirectiveMandatoryArgumentNames($fieldArgsSchemaDefinition);
        $missingMandatoryFieldArgNames = array_values(array_filter(
            $mandatoryFieldArgNames,
            fn (string $fieldArgName) => !array_key_exists($fieldArgName, $fieldArgs)
        ));
        foreach ($missingMandatoryFieldArgNames as $missingMandatoryFieldArgName) {
            $objectTypeFieldResolutionFeedbackStore->addError(
                new ObjectTypeFieldResolutionFeedback(
                    new FeedbackItemResolution(
                        GraphQLSpecErrorFeedbackItemProvider::class,
                        GraphQLSpecErrorFeedbackItemProvider::E_5_4_2_1_A,
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

        $mandatoryButNullableFieldArgNames = $this->getFieldOrDirectiveMandatoryButNullableArgumentNames($fieldArgsSchemaDefinition);
        $nullNonNullableFieldArgNames = array_values(array_filter(
            $mandatoryFieldArgNames,
            fn (string $fieldArgName) => array_key_exists($fieldArgName, $fieldArgs) && $fieldArgs[$fieldArgName] === null && !in_array($fieldArgName, $mandatoryButNullableFieldArgNames)
        ));
        foreach ($nullNonNullableFieldArgNames as $nullNonNullableFieldArgName) {
            $objectTypeFieldResolutionFeedbackStore->addError(
                new ObjectTypeFieldResolutionFeedback(
                    new FeedbackItemResolution(
                        GraphQLSpecErrorFeedbackItemProvider::class,
                        GraphQLSpecErrorFeedbackItemProvider::E_5_4_2_1_B,
                        [
                            $nullNonNullableFieldArgName,
                            $field->getName(),
                            $this->getMaybeNamespacedTypeName(),
                        ]
                    ),
                    $field->getArgument($nullNonNullableFieldArgName)?->getValueAST()
                        ?? $field->getArgument($nullNonNullableFieldArgName)
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
     * @param array<string,mixed> $fieldArgs
     */
    private function validateOnlyExistingFieldArguments(
        array $fieldArgs,
        array $fieldArgsSchemaDefinition,
        FieldInterface $field,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        $nonExistingArgNames = array_values(array_diff(
            array_keys($fieldArgs),
            array_keys($fieldArgsSchemaDefinition)
        ));
        foreach ($nonExistingArgNames as $nonExistingArgName) {
            $objectTypeFieldResolutionFeedbackStore->addError(
                new ObjectTypeFieldResolutionFeedback(
                    new FeedbackItemResolution(
                        GraphQLSpecErrorFeedbackItemProvider::class,
                        GraphQLSpecErrorFeedbackItemProvider::E_5_4_1_A,
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
     * @param array<string,mixed> $fieldArgs
     * @param array<string,mixed> $fieldArgsSchemaDefinition
     */
    private function validateFieldArgumentConstraints(
        array $fieldArgs,
        array $fieldArgsSchemaDefinition,
        ObjectTypeFieldResolverInterface $objectTypeFieldResolver,
        FieldInterface $field,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        $fieldArgNameTypeResolvers = $objectTypeFieldResolver->getConsolidatedFieldArgNameTypeResolvers($this, $field->getName());
        foreach ($fieldArgs as $argName => $argValue) {
            if (!array_key_exists($argName, $fieldArgsSchemaDefinition)) {
                continue;
            }
            $fieldArgSchemaDefinition = $fieldArgsSchemaDefinition[$argName];

            $fieldArgTypeResolver = $fieldArgNameTypeResolvers[$argName];
            $astNode = $field->getArgument($argName) ?? $field;
            /**
             * If the arg is an InputObject, let it perform validations on its input fields.
             * Please notice the value can also be `null`, as the input
             * could be nullable (it could receive even an explicit `null`)
             */
            if ($fieldArgTypeResolver instanceof InputObjectTypeResolverInterface) {
                $fieldArgIsArrayOfArraysType = $fieldArgSchemaDefinition[SchemaDefinition::IS_ARRAY_OF_ARRAYS] ?? false;
                $fieldArgIsArrayType = $fieldArgSchemaDefinition[SchemaDefinition::IS_ARRAY] ?? false;
                if ($fieldArgIsArrayOfArraysType) {
                    /** @var array<array<stdClass|null>> $argValue */
                    foreach ($argValue as $arrayArgValue) {
                        foreach ($arrayArgValue as $arrayOfArraysArgValue) {
                            if ($arrayOfArraysArgValue === null) {
                                continue;
                            }
                            $fieldArgTypeResolver->validateInputValue(
                                $arrayOfArraysArgValue,
                                $astNode,
                                $objectTypeFieldResolutionFeedbackStore,
                            );
                        }
                    }
                } elseif ($fieldArgIsArrayType) {
                    /** @var array<stdClass|null> $argValue */
                    foreach ($argValue as $arrayArgValue) {
                        if ($arrayArgValue === null) {
                            continue;
                        }
                        $fieldArgTypeResolver->validateInputValue(
                            $arrayArgValue,
                            $astNode,
                            $objectTypeFieldResolutionFeedbackStore,
                        );
                    }
                } else {
                    /** @var stdClass|null $argValue */
                    if ($argValue === null) {
                        continue;
                    }
                    $fieldArgTypeResolver->validateInputValue(
                        $argValue,
                        $astNode,
                        $objectTypeFieldResolutionFeedbackStore,
                    );
                }
            }
            $objectTypeFieldResolver->validateFieldArgValue(
                $this,
                $field->getName(),
                $argName,
                $argValue,
                $astNode,
                $fieldArgs,
                $objectTypeFieldResolutionFeedbackStore,
            );
        }
    }

    /**
     * @param array<string,mixed> $fieldArgs
     */
    public function createFieldDataAccessor(
        FieldInterface $field,
        array $fieldArgs,
    ): FieldDataAccessorInterface {
        return new FieldDataAccessor(
            $field,
            $fieldArgs,
        );
    }

    /**
     * The mutation resolver might expect to receive the data properties
     * directly (eg: "title", "content" and "status"), and these may be
     * contained under a subproperty (eg: "input") from the original fieldArgs.
     *
     * @throws AbstractValueResolutionPromiseException
     */
    public function getFieldDataAccessorForMutation(
        FieldDataAccessorInterface $fieldDataAccessor,
    ): FieldDataAccessorInterface {
        if (!$this->fieldDataAccessorForMutationCache->contains($fieldDataAccessor)) {
            $fieldDataAccessorForMutation = $fieldDataAccessor;
            /** @var ObjectTypeFieldResolverInterface */
            $executableObjectTypeFieldResolver = $this->getExecutableObjectTypeFieldResolverForField($fieldDataAccessor->getField());
            if ($executableObjectTypeFieldResolver->extractInputObjectFieldForMutation($this, $fieldDataAccessor->getFieldName())) {
                $inputObjectSubpropertyName = $executableObjectTypeFieldResolver->getFieldArgsInputObjectSubpropertyName($this, $fieldDataAccessor->getField());
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
