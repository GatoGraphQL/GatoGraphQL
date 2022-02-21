<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolvers\ObjectType;

use Exception;
use PoP\ComponentModel\App;
use PoP\ComponentModel\AttachableExtensions\AttachableExtensionGroups;
use PoP\ComponentModel\Component;
use PoP\ComponentModel\ComponentConfiguration;
use PoP\ComponentModel\Environment;
use PoP\ComponentModel\Feedback\FeedbackItemResolution;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedback;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\Feedback\Tokens;
use PoP\ComponentModel\FeedbackItemProviders\FeedbackItemProvider;
use PoP\ComponentModel\FeedbackItemProviders\FieldResolutionErrorFeedbackItemProvider;
use PoP\ComponentModel\FeedbackItemProviders\GenericFeedbackItemProvider;
use PoP\ComponentModel\FieldResolvers\InterfaceType\InterfaceTypeFieldResolverInterface;
use PoP\ComponentModel\FieldResolvers\ObjectType\ObjectTypeFieldResolverInterface;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP\ComponentModel\ObjectSerialization\ObjectSerializationManagerInterface;
use PoP\ComponentModel\Schema\FieldQueryUtils;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\AbstractRelationalTypeResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InterfaceType\InterfaceTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\DangerouslyDynamicScalarTypeResolver;
use PoP\GraphQLParser\Response\OutputServiceInterface;
use PoP\GraphQLParser\StaticHelpers\LocationHelper;
use PoP\Root\Exception\AbstractClientException;
use stdClass;

abstract class AbstractObjectTypeResolver extends AbstractRelationalTypeResolver implements ObjectTypeResolverInterface
{
    /**
     * Cache of which objectTypeFieldResolvers will process the given field
     *
     * @var array<string, ObjectTypeFieldResolverInterface[]>
     */
    protected array $objectTypeFieldResolversForFieldCache = [];
    /**
     * @var array<string, array>|null
     */
    protected ?array $mandatoryDirectivesForFields = null;
    /**
     * @var array<string, ObjectTypeFieldResolverInterface[]>|null
     */
    protected ?array $allObjectTypeFieldResolversByFieldCache = null;
    /**
     * @var array<string, array<string, ObjectTypeFieldResolverInterface[]>>
     */
    protected array $objectTypeFieldResolversByFieldCache = [];
    /**
     * @var array<string, array<string, ObjectTypeFieldResolverInterface>>
     */
    protected array $executableObjectTypeFieldResolversByFieldCache = [];
    /**
     * @var InterfaceTypeResolverInterface[]|null
     */
    protected ?array $implementedInterfaceTypeResolversCache = null;
    /**
     * @var array<string, array>
     */
    private array $dissectedFieldForSchemaCache = [];
    /**
     * @var array<string, array>
     */
    private array $fieldNamesResolvedByObjectTypeFieldResolver = [];
    /**
     * @var InterfaceTypeFieldResolverInterface[]|null
     */
    protected ?array $implementedInterfaceTypeFieldResolversCache = null;

    private ?DangerouslyDynamicScalarTypeResolver $dangerouslyDynamicScalarTypeResolver = null;
    private ?OutputServiceInterface $outputService = null;
    private ?ObjectSerializationManagerInterface $objectSerializationManager = null;

    final public function setDangerouslyDynamicScalarTypeResolver(DangerouslyDynamicScalarTypeResolver $dangerouslyDynamicScalarTypeResolver): void
    {
        $this->dangerouslyDynamicScalarTypeResolver = $dangerouslyDynamicScalarTypeResolver;
    }
    final protected function getDangerouslyDynamicScalarTypeResolver(): DangerouslyDynamicScalarTypeResolver
    {
        return $this->dangerouslyDynamicScalarTypeResolver ??= $this->instanceManager->getInstance(DangerouslyDynamicScalarTypeResolver::class);
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

    /**
     * Watch out! This function will be overridden for the UnionTypeResolver
     *
     * Collect all directives for all fields, and then build a single directive pipeline for all fields,
     * including all directives, even if they don't apply to all fields
     * Eg: id|title<skip>|excerpt<translate> will produce a pipeline [Skip, Translate] where they apply
     * to different fields. After producing the pipeline, add the mandatory items
     */
    final public function enqueueFillingObjectsFromIDs(array $ids_data_fields): void
    {
        $mandatoryDirectivesForFields = $this->getAllMandatoryDirectivesForFields();
        $mandatorySystemDirectives = $this->getMandatoryDirectives();
        foreach ($ids_data_fields as $id => $data_fields) {
            $fields = $this->getFieldsToEnqueueFillingObjectsFromIDs($data_fields);
            $this->doEnqueueFillingObjectsFromIDs($fields, $mandatoryDirectivesForFields, $mandatorySystemDirectives, $id, $data_fields);
        }
    }

    final public function getAllMandatoryDirectivesForFields(): array
    {
        if ($this->mandatoryDirectivesForFields === null) {
            $this->mandatoryDirectivesForFields = $this->calculateAllMandatoryDirectivesForFields();
        }
        return $this->mandatoryDirectivesForFields;
    }

    private function calculateAllMandatoryDirectivesForFields(): array
    {
        $mandatoryDirectivesForFields = [];
        $typeResolverDecorators = $this->getAllRelationalTypeResolverDecorators();
        foreach ($typeResolverDecorators as $typeResolverDecorator) {
            // array_merge_recursive so that if 2 different decorators add a directive for the same field, the results are merged together, not override each other
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
    final public function getFieldSchemaDefinition(string $field): ?array
    {
        $executableObjectTypeFieldResolver = $this->getExecutableObjectTypeFieldResolverForField($field);
        if ($executableObjectTypeFieldResolver === null) {
            return null;
        }

        $fieldName = $this->getFieldQueryInterpreter()->getFieldName($field);
        $fieldArgs = $this->getFieldQueryInterpreter()->extractStaticFieldArguments($field);
        return $executableObjectTypeFieldResolver->getFieldSchemaDefinition($this, $fieldName, $fieldArgs);
    }

    final public function resolveFieldValidationErrorQualifiedEntries(string $field, array &$variables = null): array
    {
        list(
            $validField,
            $fieldName,
            $fieldArgs,
            $schemaErrors,
        ) = $this->dissectFieldForSchema($field);
        // Dissecting the field may already fail, then already return the error
        if ($schemaErrors) {
            return $schemaErrors;
        }
        if ($executableObjectTypeFieldResolver = $this->getExecutableObjectTypeFieldResolverForField($field)) {
            if ($maybeErrors = $executableObjectTypeFieldResolver->resolveFieldValidationErrorDescriptions($this, $fieldName, $fieldArgs)) {
                foreach ($maybeErrors as $error) {
                    $schemaErrors[] = [
                        Tokens::PATH => [$field],
                        Tokens::MESSAGE => $error,
                    ];
                }
            }
            return $schemaErrors;
        }

        // If we reach here, no fieldResolver processes this field, which is an error
        /**
         * If the error happened from requesting a version that doesn't exist, show an appropriate error message
         */
        if (
            Environment::enableSemanticVersionConstraints()
            && ($versionConstraint = $fieldArgs[SchemaDefinition::VERSION_CONSTRAINT] ?? null)
        ) {
            $errorMessage = sprintf(
                $this->__(
                    'There is no field \'%s\' on type \'%s\' satisfying version constraint \'%s\'',
                    'component-model'
                ),
                $fieldName,
                $this->getMaybeNamespacedTypeName(),
                $versionConstraint,
            );
        } else {
            $errorMessage = sprintf(
                $this->__(
                    'There is no field \'%s\' on type \'%s\'',
                    'component-model'
                ),
                $fieldName,
                $this->getMaybeNamespacedTypeName()
            );
        }
        return [
            [
                Tokens::PATH => [$field],
                Tokens::MESSAGE => $errorMessage,
            ],
        ];
    }

    final public function resolveFieldValidationWarningQualifiedEntries(string $field, array &$variables = null): array
    {
        $executableObjectTypeFieldResolver = $this->getExecutableObjectTypeFieldResolverForField($field);
        if ($executableObjectTypeFieldResolver === null) {
            return [];
        }

        list(
            $validField,
            $fieldName,
            $fieldArgs,
            $schemaErrors,
            $schemaWarnings,
        ) = $this->dissectFieldForSchema($field);
        /**
         * If the field is not valid, the fieldArgs may be empty,
         * and getting warnings on the field may not work correctly
         */
        if ($validField === null) {
            return $schemaWarnings;
        }
        if ($maybeWarnings = $executableObjectTypeFieldResolver->resolveFieldValidationWarningDescriptions($this, $fieldName, $fieldArgs)) {
            foreach ($maybeWarnings as $warning) {
                $schemaWarnings[] = [
                    Tokens::PATH => [$field],
                    Tokens::MESSAGE => $warning,
                ];
            }
        }
        return $schemaWarnings;
    }

    final public function resolveFieldDeprecationQualifiedEntries(string $field, array &$variables = null): array
    {
        $executableObjectTypeFieldResolver = $this->getExecutableObjectTypeFieldResolverForField($field);
        if ($executableObjectTypeFieldResolver === null) {
            return [];
        }

        list(
            $validField,
            $fieldName,
            $fieldArgs,
            $schemaErrors,
            $schemaWarnings,
            $schemaDeprecations,
        ) = $this->dissectFieldForSchema($field);
        /**
         * If the field is not valid, the fieldArgs may be empty,
         * and getting deprecations on the field may not work correctly
         */
        if ($validField === null) {
            return $schemaDeprecations;
        }
        if ($maybeDeprecationMessages = $executableObjectTypeFieldResolver->resolveFieldValidationDeprecationMessages($this, $fieldName, $fieldArgs)) {
            foreach ($maybeDeprecationMessages as $deprecationMessage) {
                $schemaDeprecations[] = [
                    Tokens::PATH => [$field],
                    Tokens::MESSAGE => $deprecationMessage,
                ];
            }
        }
        return $schemaDeprecations;
    }

    final public function getFieldTypeResolver(string $field): ?ConcreteTypeResolverInterface
    {
        $executableObjectTypeFieldResolver = $this->getExecutableObjectTypeFieldResolverForField($field);
        if ($executableObjectTypeFieldResolver === null) {
            return null;
        }

        list(
            $validField,
            $fieldName,
        ) = $this->dissectFieldForSchema($field);
        return $executableObjectTypeFieldResolver->getFieldTypeResolver($this, $fieldName);
    }

    final public function getFieldTypeModifiers(string $field): ?int
    {
        $executableObjectTypeFieldResolver = $this->getExecutableObjectTypeFieldResolverForField($field);
        if ($executableObjectTypeFieldResolver === null) {
            return null;
        }

        list(
            $validField,
            $fieldName,
        ) = $this->dissectFieldForSchema($field);
        return $executableObjectTypeFieldResolver->getFieldTypeModifiers($this, $fieldName);
    }

    final public function getFieldMutationResolver(string $field): ?MutationResolverInterface
    {
        $executableObjectTypeFieldResolver = $this->getExecutableObjectTypeFieldResolverForField($field);
        if ($executableObjectTypeFieldResolver === null) {
            return null;
        }

        list(
            $validField,
            $fieldName,
        ) = $this->dissectFieldForSchema($field);
        return $executableObjectTypeFieldResolver->getFieldMutationResolver($this, $fieldName);
    }

    final public function isFieldAMutation(string $field): ?bool
    {
        $executableObjectTypeFieldResolver = $this->getExecutableObjectTypeFieldResolverForField($field);
        if ($executableObjectTypeFieldResolver === null) {
            return null;
        }

        list(
            $validField,
            $fieldName,
        ) = $this->dissectFieldForSchema($field);
        $fieldMutationResolver = $executableObjectTypeFieldResolver->getFieldMutationResolver($this, $fieldName);
        return $fieldMutationResolver !== null;
    }

    final protected function dissectFieldForSchema(string $field): array
    {
        if (!isset($this->dissectedFieldForSchemaCache[$field])) {
            $this->dissectedFieldForSchemaCache[$field] = $this->doDissectFieldForSchema($field);
        }
        return $this->dissectedFieldForSchemaCache[$field];
    }

    private function doDissectFieldForSchema(string $field): array
    {
        return $this->getFieldQueryInterpreter()->extractFieldArgumentsForSchema($this, $field);
    }

    /**
     * @param array<string, mixed> $variables
     * @param array<string, mixed> $expressions
     * @param array<string, mixed> $options
     */
    final public function resolveValue(
        object $object,
        string $field,
        array $variables,
        array $expressions,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
        array $options = []
    ): mixed {
        $objectTypeFieldResolvers = $this->getObjectTypeFieldResolversForField($field);
        if ($objectTypeFieldResolvers === []) {
            /**
             * Return an error to indicate that no fieldResolver processes this field,
             * which is different than returning a null value.
             * Needed for compatibility with CustomPostUnionTypeResolver
             * (so that data-fields aimed for another post_type are not retrieved)
             */
            $fieldName = $this->getFieldQueryInterpreter()->getFieldName($field);
            $objectTypeFieldResolutionFeedbackStore->addError(
                new ObjectTypeFieldResolutionFeedback(
                    new FeedbackItemResolution(
                        FieldResolutionErrorFeedbackItemProvider::class,
                        FieldResolutionErrorFeedbackItemProvider::E1,
                        [
                            $fieldName,
                            $this->getMaybeNamespacedTypeName(),
                            $this->getID($object),
                        ]
                    ),
                    LocationHelper::getNonSpecificLocation(),
                    $this,
                )
            );
            return null;
        }

        // Get the value from a fieldResolver, from the first one who can deliver the value
        // (The fact that they resolve the fieldName doesn't mean that they will always resolve it for that specific $object)
        // Important: $validField becomes $field: remove all invalid fieldArgs before executing `resolveValue` on the fieldResolver
        list(
            $field,
            $fieldName,
            $fieldArgs,
            $schemaErrors,
            $schemaWarnings,
        ) = $this->dissectFieldForSchema($field);

        // Store the warnings to be read if needed
        if ($schemaWarnings) {
            foreach ($schemaWarnings as $warningEntry) {
                $objectTypeFieldResolutionFeedbackStore->addWarning(
                    new ObjectTypeFieldResolutionFeedback(
                        new FeedbackItemResolution(
                            GenericFeedbackItemProvider::class,
                            GenericFeedbackItemProvider::W1,
                            [
                                $warningEntry[Tokens::MESSAGE],
                            ]
                        ),
                        LocationHelper::getNonSpecificLocation(),
                        $this,
                        $warningEntry[Tokens::EXTENSIONS] ?? [],
                        // $field, //$warningEntry[Tokens::PATH],
                    )
                );
            }
        }

        if ($schemaErrors) {
            $objectTypeFieldResolutionFeedbackStore->addError(
                new ObjectTypeFieldResolutionFeedback(
                    new FeedbackItemResolution(
                        FieldResolutionErrorFeedbackItemProvider::class,
                        FieldResolutionErrorFeedbackItemProvider::E2,
                        [
                            $fieldName,
                        ]
                    ),
                    LocationHelper::getNonSpecificLocation(),
                    $this,
                )
            );
            return null;
        }

        // Important: calculate 'isAnyFieldArgumentValueDynamic' before resolving the args for the object
        // That is because if when resolving there is an error, the fieldArgValue will be removed completely, then we don't know that we must validate the schema again
        // Eg: doing /?query=arrayUnique(extract(..., 0)) and extract fails, arrayUnique will have no fieldArgs. However its fieldArg is mandatory, but by then it doesn't know it needs to validate it
        // Before resolving the fieldArgValues which are fields:
        // Calculate $validateSchemaOnObject: if any value containes a field, then we must perform the schemaValidation on the item, such as checking that all mandatory fields are there
        // For instance: After resolving a field and being casted it may be incorrect, so the value is invalidated, and after the schemaValidation the proper error is shown
        // Also need to check for variables, since these must be resolved too
        // For instance: ?query=posts(limit:3),post(id:$id).id|title&id=112
        // We can also force it through an option. This is needed when the field is created on runtime.
        // Eg: through the <transform> directive, in which case no parameter is dynamic anymore by the time it reaches here, yet it was not validated statically either
        $validateSchemaOnObject =
            ($options[self::OPTION_VALIDATE_SCHEMA_ON_RESULT_ITEM] ?? null) ||
            FieldQueryUtils::isAnyFieldArgumentValueDynamic(
                array_values(
                    $this->getFieldQueryInterpreter()->extractFieldArguments($this, $field) ?? []
                )
            );

        // Once again, the $validField becomes the $field
        list(
            $field,
            $fieldName,
            $fieldArgs,
            $maybeObjectErrors,
            $maybeObjectWarnings,
            $maybeObjectDeprecations,
        ) = $this->getFieldQueryInterpreter()->extractFieldArgumentsForObject($this, $object, $field, $variables, $expressions);

        // Store the warnings to be read if needed
        if ($maybeObjectWarnings) {
            foreach ($maybeObjectWarnings as $warningEntry) {
                $objectTypeFieldResolutionFeedbackStore->addWarning(
                    new ObjectTypeFieldResolutionFeedback(
                        new FeedbackItemResolution(
                            FeedbackItemProvider::class,
                            FeedbackItemProvider::W1,
                            [
                                $warningEntry[Tokens::MESSAGE],
                            ]
                        ),
                        LocationHelper::getNonSpecificLocation(),
                        $this,
                        $warningEntry[Tokens::EXTENSIONS] ?? [],
                        // $field, //$warningEntry[Tokens::PATH],
                    )
                );
            }
        }
        if ($maybeObjectErrors) {
            $objectTypeFieldResolutionFeedbackStore->addError(
                new ObjectTypeFieldResolutionFeedback(
                    new FeedbackItemResolution(
                        FieldResolutionErrorFeedbackItemProvider::class,
                        FieldResolutionErrorFeedbackItemProvider::E2,
                        [
                            $fieldName,
                        ]
                    ),
                    LocationHelper::getNonSpecificLocation(),
                    $this,
                )
            );
            return null;
        }
        if ($maybeObjectDeprecations) {
            foreach ($maybeObjectDeprecations as $deprecationEntry) {
                $objectTypeFieldResolutionFeedbackStore->addDeprecation(
                    new ObjectTypeFieldResolutionFeedback(
                        new FeedbackItemResolution(
                            GenericFeedbackItemProvider::class,
                            GenericFeedbackItemProvider::D1,
                            [
                                $deprecationEntry[Tokens::MESSAGE],
                            ]
                        ),
                        LocationHelper::getNonSpecificLocation(),
                        $this,
                        $deprecationEntry[Tokens::EXTENSIONS] ?? [],
                        // $field, //$deprecationEntry[Tokens::PATH],
                    )
                );
            }
        }

        foreach ($objectTypeFieldResolvers as $objectTypeFieldResolver) {
            // Also send the typeResolver along, as to get the id of the $object being passed
            if (!$objectTypeFieldResolver->resolveCanProcessObject($this, $object, $fieldName, $fieldArgs)) {
                continue;
            }
            if ($validateSchemaOnObject) {
                if ($maybeErrors = $objectTypeFieldResolver->resolveFieldValidationErrorDescriptions($this, $fieldName, $fieldArgs)) {
                    // @todo Return FeedbackItemResolution instead of strings here, and bubble up directly
                    // Then uncomment/fix code below
                    // $objectTypeFieldResolutionFeedbackStore->addError(
                    //     new ObjectTypeFieldResolutionFeedback(
                    //         $this->getValidationFailedErrorMessage($fieldName, $maybeErrors),
                    //         ErrorCodes::VALIDATION_FAILED,
                    //         LocationHelper::getNonSpecificLocation(),
                    //         $this,
                    //     )
                    // );
                    return null;
                }
                if ($maybeDeprecations = $objectTypeFieldResolver->resolveFieldValidationDeprecationMessages($this, $fieldName, $fieldArgs)) {
                    foreach ($maybeDeprecations as $deprecation) {
                        $objectTypeFieldResolutionFeedbackStore->addDeprecation(
                            new ObjectTypeFieldResolutionFeedback(
                                new FeedbackItemResolution(
                                    GenericFeedbackItemProvider::class,
                                    GenericFeedbackItemProvider::D1,
                                    [
                                        $deprecation,
                                    ]
                                ),
                                LocationHelper::getNonSpecificLocation(),
                                $this,
                                // $field,
                            )
                        );
                    }
                }
            }
            if ($validationErrorDescriptions = $objectTypeFieldResolver->getValidationErrorDescriptions($this, $object, $fieldName, $fieldArgs)) {
                // @todo Return FeedbackItemResolution instead of strings here, and bubble up directly
                // Then uncomment/fix code below
                // $objectTypeFieldResolutionFeedbackStore->addError(
                //     new ObjectTypeFieldResolutionFeedback(
                //         $this->getValidationFailedErrorMessage($fieldName, $validationErrorDescriptions),
                //         ErrorCodes::VALIDATION_FAILED,
                //         LocationHelper::getNonSpecificLocation(),
                //         $this,
                //     )
                // );
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
                    $fieldName,
                    $fieldArgs,
                    $variables,
                    $expressions,
                    $objectTypeFieldResolutionFeedbackStore,
                    $options
                );
            } catch (Exception $e) {
                /** @var ComponentConfiguration */
                $componentConfiguration = App::getComponent(Component::class)->getConfiguration();
                if ($componentConfiguration->logExceptionErrorMessages()) {
                    // @todo: Implement for Log
                }
                if (
                    $e instanceof AbstractClientException
                    || $componentConfiguration->sendExceptionErrorMessages()
                ) {
                    $objectTypeFieldResolutionFeedbackStore->addError(
                        new ObjectTypeFieldResolutionFeedback(
                            new FeedbackItemResolution(
                                FeedbackItemProvider::class,
                                FeedbackItemProvider::E3,
                                [
                                    $fieldName,
                                    $e->getMessage(),
                                ]
                            ),
                            LocationHelper::getNonSpecificLocation(),
                            $this,
                        )
                    );
                    return null;
                }
                $objectTypeFieldResolutionFeedbackStore->addError(
                    new ObjectTypeFieldResolutionFeedback(
                        new FeedbackItemResolution(
                            FeedbackItemProvider::class,
                            FeedbackItemProvider::E4,
                            [
                                $fieldName,
                            ]
                        ),
                        LocationHelper::getNonSpecificLocation(),
                        $this,
                    )
                );
                return null;
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
                $fieldTypeModifiers = $objectTypeFieldResolver->getFieldTypeModifiers($this, $field);
                $fieldTypeIsNonNullable = ($fieldTypeModifiers & SchemaTypeModifiers::NON_NULLABLE) === SchemaTypeModifiers::NON_NULLABLE;
                if ($fieldTypeIsNonNullable) {
                    $objectTypeFieldResolutionFeedbackStore->addError(
                        new ObjectTypeFieldResolutionFeedback(
                            new FeedbackItemResolution(
                                FieldResolutionErrorFeedbackItemProvider::class,
                                FieldResolutionErrorFeedbackItemProvider::E3,
                                [
                                    $fieldName,
                                ]
                            ),
                            LocationHelper::getNonSpecificLocation(),
                            $this,
                        )
                    );
                    return null;
                }
            } elseif (
                $objectTypeFieldResolver->validateResolvedFieldType(
                    $this,
                    $fieldName,
                    $fieldArgs,
                )
            ) {
                $fieldSchemaDefinition = $objectTypeFieldResolver->getFieldSchemaDefinition($this, $fieldName, $fieldArgs);
                $fieldTypeResolver = $fieldSchemaDefinition[SchemaDefinition::TYPE_RESOLVER];

                /**
                 * `DangerouslyDynamic` is a special scalar type which is not coerced or validated.
                 * In particular, it does not need to validate if it is an array or not,
                 * as according to the applied WrappingType.
                 *
                 * This is to enable it to have an array as value, which is not
                 * allowed by GraphQL unless the array is explicitly defined.
                 *
                 * For instance, type `DangerouslyDynamic` could have values
                 * `"hello"` and `["hello"]`, but in GraphQL we must differentiate
                 * these values by types `String` and `[String]`.
                 */
                if ($fieldTypeResolver === $this->getDangerouslyDynamicScalarTypeResolver()) {
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
                                    $fieldName,
                                    $this->getOutputService()->jsonEncodeArrayOrStdClassValue($value)
                                ]
                            ),
                            LocationHelper::getNonSpecificLocation(),
                            $this,
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
                                    $fieldName,
                                    $valueAsString,
                                ]
                            ),
                            LocationHelper::getNonSpecificLocation(),
                            $this,
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
                                    $fieldName,
                                ]
                            ),
                            LocationHelper::getNonSpecificLocation(),
                            $this,
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
                                    $fieldName,
                                    $this->getOutputService()->jsonEncodeArrayOrStdClassValue($value),
                                ]
                            ),
                            LocationHelper::getNonSpecificLocation(),
                            $this,
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
                                    $fieldName,
                                    $this->getOutputService()->jsonEncodeArrayOrStdClassValue($value),
                                ]
                            ),
                            LocationHelper::getNonSpecificLocation(),
                            $this,
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
                                    $fieldName,
                                ]
                            ),
                            LocationHelper::getNonSpecificLocation(),
                            $this,
                        )
                    );
                    return null;
                }
            }

            // Everything is good, return the value
            return $value;
        }

        $objectTypeFieldResolutionFeedbackStore->addError(
            new ObjectTypeFieldResolutionFeedback(
                new FeedbackItemResolution(
                    FieldResolutionErrorFeedbackItemProvider::class,
                    FieldResolutionErrorFeedbackItemProvider::E10,
                    [
                        $this->getMaybeNamespacedTypeName(),
                        $fieldName,
                        $this->getID($object)
                    ]
                ),
                LocationHelper::getNonSpecificLocation(),
                $this,
            )
        );
        return null;
    }

    // @todo Return FeedbackItemResolution instead of strings here, and bubble up directly
    // Then remove code below
    // /**
    //  * Return an error to indicate that no fieldResolver processes this field,
    //  * which is different than returning a null value.
    //  * Needed for compatibility with CustomPostUnionTypeResolver
    //  * (so that data-fields aimed for another post_type are not retrieved)
    //  */
    // protected function getValidationFailedErrorMessage(string $fieldName, array $validationDescriptions): string
    // {
    //     if (count($validationDescriptions) == 1) {
    //         return $validationDescriptions[0];
    //     }
    //     return sprintf(
    //         $this->__('Field \'%s\' could not be processed due to previous error(s): \'%s\'', 'component-model'),
    //         $fieldName,
    //         implode($this->__('\', \'', 'component-model'), $validationDescriptions)
    //     );
    // }

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
                    if ($objectTypeFieldResolversForField = $this->getObjectTypeFieldResolversForField($fieldName)) {
                        $schemaObjectTypeFieldResolvers[$fieldName] = $objectTypeFieldResolversForField;
                    }
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
    final protected function getExecutableObjectTypeFieldResolverForField(string $field): ?ObjectTypeFieldResolverInterface
    {
        $objectTypeFieldResolversForField = $this->getObjectTypeFieldResolversForField($field);
        if ($objectTypeFieldResolversForField === []) {
            return null;
        }
        return $objectTypeFieldResolversForField[0];
    }

    /**
     * @return ObjectTypeFieldResolverInterface[]
     */
    final protected function getObjectTypeFieldResolversForField(string $field): array
    {
        // Calculate the fieldResolver to process this field if not already in the cache
        // If none is found, this value will be set to NULL. This is needed to stop attempting to find the fieldResolver
        if (!isset($this->objectTypeFieldResolversForFieldCache[$field])) {
            $this->objectTypeFieldResolversForFieldCache[$field] = $this->calculateObjectTypeFieldResolversForField($field);
        }

        return $this->objectTypeFieldResolversForFieldCache[$field];
    }

    final public function hasObjectTypeFieldResolversForField(string $field): bool
    {
        return !empty($this->getObjectTypeFieldResolversForField($field));
    }

    protected function calculateObjectTypeFieldResolversForField(string $field): array
    {
        // Important: here we CAN'T use `dissectFieldForSchema` to get the fieldArgs, because it will attempt to validate them
        // To validate them, the fieldQueryInterpreter needs to know the schema, so it once again calls functions from this typeResolver
        // Generating an infinite loop
        // Then, just to find out which ObjectTypeFieldResolvers will process this field, crudely obtain the fieldArgs, with NO schema-based validation!
        // list(
        //     $field,
        //     $fieldName,
        //     $fieldArgs,
        // ) = $this->dissectFieldForSchema($field);
        $fieldName = $this->getFieldQueryInterpreter()->getFieldName($field);
        $fieldArgs = $this->getFieldQueryInterpreter()->extractStaticFieldArguments($field);

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
                if (!$objectTypeFieldResolver->resolveCanProcess($this, $fieldName, $fieldArgs)) {
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
}
