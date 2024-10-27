<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolvers\InputObjectType;

use PoP\ComponentModel\FeedbackItemProviders\InputValueCoercionGraphQLSpecErrorFeedbackItemProvider;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedback;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\Module;
use PoP\ComponentModel\ModuleConfiguration;
use PoP\ComponentModel\Resolvers\TypeSchemaDefinitionResolverTrait;
use PoP\ComponentModel\Schema\InputCoercingServiceInterface;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\AbstractTypeResolver;
use PoP\ComponentModel\TypeResolvers\DeprecatableInputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\DangerouslyNonSpecificScalarTypeScalarTypeResolver;
use PoP\GraphQLParser\FeedbackItemProviders\GraphQLSpecErrorFeedbackItemProvider;
use PoP\GraphQLParser\Spec\Parser\Ast\AstInterface;
use PoP\Root\App;
use PoP\ComponentModel\Feedback\FeedbackItemResolution;
use stdClass;

abstract class AbstractInputObjectTypeResolver extends AbstractTypeResolver implements InputObjectTypeResolverInterface
{
    use TypeSchemaDefinitionResolverTrait;

    /** @var array<string,array<string,mixed>> */
    protected array $schemaDefinitionForInputFieldCache = [];
    /** @var array<string,InputTypeResolverInterface>|null */
    private ?array $consolidatedInputFieldNameTypeResolversCache = null;
    /** @var array<string,?string> */
    private array $consolidatedInputFieldDescriptionCache = [];
    /** @var array<string,mixed> */
    private array $consolidatedInputFieldDefaultValueCache = [];
    /** @var array<string,int> */
    private array $consolidatedInputFieldTypeModifiersCache = [];
    /** @var array<string,array<string,mixed>> */
    private array $consolidatedInputFieldExtensionsCache = [];
    /** @var string[]|null */
    private ?array $consolidatedSensitiveInputFieldNames = null;

    private ?DangerouslyNonSpecificScalarTypeScalarTypeResolver $dangerouslyNonSpecificScalarTypeScalarTypeResolver = null;
    private ?InputCoercingServiceInterface $inputCoercingService = null;

    final protected function getDangerouslyNonSpecificScalarTypeScalarTypeResolver(): DangerouslyNonSpecificScalarTypeScalarTypeResolver
    {
        if ($this->dangerouslyNonSpecificScalarTypeScalarTypeResolver === null) {
            /** @var DangerouslyNonSpecificScalarTypeScalarTypeResolver */
            $dangerouslyNonSpecificScalarTypeScalarTypeResolver = $this->instanceManager->getInstance(DangerouslyNonSpecificScalarTypeScalarTypeResolver::class);
            $this->dangerouslyNonSpecificScalarTypeScalarTypeResolver = $dangerouslyNonSpecificScalarTypeScalarTypeResolver;
        }
        return $this->dangerouslyNonSpecificScalarTypeScalarTypeResolver;
    }
    final protected function getInputCoercingService(): InputCoercingServiceInterface
    {
        if ($this->inputCoercingService === null) {
            /** @var InputCoercingServiceInterface */
            $inputCoercingService = $this->instanceManager->getInstance(InputCoercingServiceInterface::class);
            $this->inputCoercingService = $inputCoercingService;
        }
        return $this->inputCoercingService;
    }

    /**
     * @return string[]
     */
    public function getSensitiveInputFieldNames(): array
    {
        return [];
    }
    public function getInputFieldDescription(string $inputFieldName): ?string
    {
        return null;
    }
    public function getInputFieldDefaultValue(string $inputFieldName): mixed
    {
        return null;
    }
    public function getInputFieldTypeModifiers(string $inputFieldName): int
    {
        return SchemaTypeModifiers::NONE;
    }

    /**
     * Consolidation of the schema inputs. Call this function to read the data
     * instead of the individual functions, since it applies hooks to override/extend.
     *
     * @return array<string,InputTypeResolverInterface>
     */
    final public function getConsolidatedInputFieldNameTypeResolvers(): array
    {
        if ($this->consolidatedInputFieldNameTypeResolversCache !== null) {
            return $this->consolidatedInputFieldNameTypeResolversCache;
        }

        $consolidatedInputFieldNameTypeResolvers = App::applyFilters(
            HookNames::INPUT_FIELD_NAME_TYPE_RESOLVERS,
            $this->getInputFieldNameTypeResolvers(),
            $this,
        );

        // Maybe exclude the sensitive input fields
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        if (!$moduleConfiguration->exposeSensitiveDataInSchema()) {
            $sensitiveInputFieldNames = $this->getConsolidatedSensitiveInputFieldNames();
            $consolidatedInputFieldNameTypeResolvers = array_filter(
                $consolidatedInputFieldNameTypeResolvers,
                fn (string $inputFieldName) => !in_array($inputFieldName, $sensitiveInputFieldNames),
                ARRAY_FILTER_USE_KEY
            );
        }

        $this->consolidatedInputFieldNameTypeResolversCache = $consolidatedInputFieldNameTypeResolvers;
        return $this->consolidatedInputFieldNameTypeResolversCache;
    }

    /**
     * Consolidation of the schema inputs. Call this function to read the data
     * instead of the individual functions, since it applies hooks to override/extend.
     */
    final public function getConsolidatedSensitiveInputFieldNames(): array
    {
        if ($this->consolidatedSensitiveInputFieldNames !== null) {
            return $this->consolidatedSensitiveInputFieldNames;
        }

        $this->consolidatedSensitiveInputFieldNames = App::applyFilters(
            HookNames::SENSITIVE_INPUT_FIELD_NAMES,
            $this->getSensitiveInputFieldNames(),
            $this,
        );
        return $this->consolidatedSensitiveInputFieldNames;
    }

    /**
     * Consolidation of the schema inputs. Call this function to read the data
     * instead of the individual functions, since it applies hooks to override/extend.
     */
    final public function getConsolidatedInputFieldDescription(string $inputFieldName): ?string
    {
        if (array_key_exists($inputFieldName, $this->consolidatedInputFieldDescriptionCache)) {
            return $this->consolidatedInputFieldDescriptionCache[$inputFieldName];
        }
        $this->consolidatedInputFieldDescriptionCache[$inputFieldName] = App::applyFilters(
            HookNames::INPUT_FIELD_DESCRIPTION,
            $this->getInputFieldDescription($inputFieldName),
            $this,
            $inputFieldName,
        );
        return $this->consolidatedInputFieldDescriptionCache[$inputFieldName];
    }

    /**
     * Consolidation of the schema inputs. Call this function to read the data
     * instead of the individual functions, since it applies hooks to override/extend.
     */
    final public function getConsolidatedInputFieldDefaultValue(string $inputFieldName): mixed
    {
        if (array_key_exists($inputFieldName, $this->consolidatedInputFieldDefaultValueCache)) {
            return $this->consolidatedInputFieldDefaultValueCache[$inputFieldName];
        }
        $this->consolidatedInputFieldDefaultValueCache[$inputFieldName] = App::applyFilters(
            HookNames::INPUT_FIELD_DEFAULT_VALUE,
            $this->getInputFieldDefaultValue($inputFieldName),
            $this,
            $inputFieldName,
        );
        return $this->consolidatedInputFieldDefaultValueCache[$inputFieldName];
    }

    /**
     * Consolidation of the schema inputs. Call this function to read the data
     * instead of the individual functions, since it applies hooks to override/extend.
     */
    final public function getConsolidatedInputFieldTypeModifiers(string $inputFieldName): int
    {
        if (array_key_exists($inputFieldName, $this->consolidatedInputFieldTypeModifiersCache)) {
            return $this->consolidatedInputFieldTypeModifiersCache[$inputFieldName];
        }
        $this->consolidatedInputFieldTypeModifiersCache[$inputFieldName] = App::applyFilters(
            HookNames::INPUT_FIELD_TYPE_MODIFIERS,
            $this->getInputFieldTypeModifiers($inputFieldName),
            $this,
            $inputFieldName,
        );
        return $this->consolidatedInputFieldTypeModifiersCache[$inputFieldName];
    }

    final public function coerceValue(
        string|int|float|bool|stdClass $inputValue,
        AstInterface $astNode,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): string|int|float|bool|object|null {
        if (!($inputValue instanceof stdClass)) {
            $objectTypeFieldResolutionFeedbackStore->addError(
                new ObjectTypeFieldResolutionFeedback(
                    new FeedbackItemResolution(
                        InputValueCoercionGraphQLSpecErrorFeedbackItemProvider::class,
                        InputValueCoercionGraphQLSpecErrorFeedbackItemProvider::E_5_6_1_15,
                        [
                            $this->getMaybeNamespacedTypeName(),
                            $inputValue
                        ]
                    ),
                    $astNode,
                ),
            );
            return null;
        }
        return $this->coerceInputObjectValue($inputValue, $astNode, $objectTypeFieldResolutionFeedbackStore);
    }

    protected function coerceInputObjectValue(
        stdClass $inputValue,
        AstInterface $astNode,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): ?stdClass {
        $coercedInputValue = new stdClass();
        $inputFieldNameTypeResolvers = $this->getConsolidatedInputFieldNameTypeResolvers();

        $inputCoercingService = $this->getInputCoercingService();

        /**
         * Inject all properties with default value
         */
        foreach ($inputFieldNameTypeResolvers as $inputFieldName => $inputFieldTypeResolver) {
            // If it has set a value, skip it
            // Providing a `null` value is allowed
            if (property_exists($inputValue, $inputFieldName)) {
                continue;
            }
            // If it has default value, set it
            $inputFieldDefaultValue = $this->getConsolidatedInputFieldDefaultValue($inputFieldName);
            if ($inputFieldDefaultValue !== null) {
                $inputValue->$inputFieldName = $inputFieldDefaultValue;
                continue;
            }
            /**
             * If it is an InputObject, set it to {} so it has the chance
             * to set its own default values.
             *
             * Do it only if:
             *
             *   1. It is non-mandatory, or otherwise it's better to let the
             *      validation fail ("error: mandatory input ... was not provided")
             *
             *   2. All its inputs are non-mandatory, or otherwise the logic
             *      (eg: in `integrateInputValueToFilteringQueryArgs`) will assume
             *      that those values are provided (but they are not!), triggering an error
             *      (eg: "Warning: Undefined property: stdClass::$key in .../meta/src/TypeResolvers/InputObjectType/AbstractMetaQueryInputObjectTypeResolver.php on line 159")
             */
            if (
                $inputFieldTypeResolver instanceof InputObjectTypeResolverInterface
                && $this->initializeInputFieldInputObjectValue()
            ) {
                $inputObjectTypeResolver = $inputFieldTypeResolver;
                $inputFieldTypeModifiers = $this->getConsolidatedInputFieldTypeModifiers($inputFieldName);
                $inputFieldTypeModifiersIsMandatory = ($inputFieldTypeModifiers & SchemaTypeModifiers::MANDATORY) === SchemaTypeModifiers::MANDATORY
                    || ($inputFieldTypeModifiers & SchemaTypeModifiers::MANDATORY_BUT_NULLABLE) === SchemaTypeModifiers::MANDATORY_BUT_NULLABLE;
                if (!$inputFieldTypeModifiersIsMandatory && !$inputObjectTypeResolver->hasMandatoryWithNoDefaultValueInputFields()) {
                    $inputValue->$inputFieldName = new stdClass();
                }
            }
        }

        foreach ((array)$inputValue as $inputFieldName => $inputFieldValue) {
            // Check that the input field exists
            $inputFieldTypeResolver = $inputFieldNameTypeResolvers[$inputFieldName] ?? null;
            if ($inputFieldTypeResolver === null) {
                $objectTypeFieldResolutionFeedbackStore->addError(
                    new ObjectTypeFieldResolutionFeedback(
                        new FeedbackItemResolution(
                            GraphQLSpecErrorFeedbackItemProvider::class,
                            GraphQLSpecErrorFeedbackItemProvider::E_5_6_2,
                            [
                                $inputFieldName,
                                $this->getMaybeNamespacedTypeName(),
                            ]
                        ),
                        $astNode,
                    ),
                );
                continue;
            }

            $inputFieldTypeModifiers = $this->getConsolidatedInputFieldTypeModifiers($inputFieldName);
            $inputFieldIsNonNullable = ($inputFieldTypeModifiers & SchemaTypeModifiers::NON_NULLABLE) === SchemaTypeModifiers::NON_NULLABLE;
            $inputFieldIsArrayOfArraysType = ($inputFieldTypeModifiers & SchemaTypeModifiers::IS_ARRAY_OF_ARRAYS) === SchemaTypeModifiers::IS_ARRAY_OF_ARRAYS;
            $inputFieldIsNonNullArrayOfArraysItemsType = ($inputFieldTypeModifiers & SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY_OF_ARRAYS) === SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY_OF_ARRAYS;
            $inputFieldIsArrayType = $inputFieldIsArrayOfArraysType || ($inputFieldTypeModifiers & SchemaTypeModifiers::IS_ARRAY) === SchemaTypeModifiers::IS_ARRAY;
            $inputFieldIsNonNullArrayItemsType = ($inputFieldTypeModifiers & SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY) === SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY;

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
            $isDangerouslyNonSpecificScalar = $inputFieldTypeResolver === $this->getDangerouslyNonSpecificScalarTypeScalarTypeResolver();

            /**
             * DangerouslyNonScalar: Validate the cardinality, but only
             * if explicitly set to `true`. Otherwise change from `false`
             * to `null`, to indicate "do not validate".
             *
             *   - DangerouslyNonSpecificScalar does not need to validate anything => all null
             *   - [DangerouslyNonSpecificScalar] must certainly be an array, but it doesn't care
             *     inside if it's an array or not => $inputIsArrayType => true, $inputIsArrayOfArraysType => null
             *   - [[DangerouslyNonSpecificScalar]] must be array of arrays => $inputIsArrayType => true, $inputIsArrayOfArraysType => true
             */
            if ($isDangerouslyNonSpecificScalar) {
                if (!$inputFieldIsNonNullable) {
                    $inputFieldIsNonNullable = null;
                }
                if (!$inputFieldIsArrayOfArraysType) {
                    $inputFieldIsArrayOfArraysType = null;
                }
                if (!$inputFieldIsArrayType) {
                    $inputFieldIsArrayType = null;
                }
            } else {
                /**
                 * Support passing a single value where a list is expected:
                 * `{ posts(ids: 1) }` means `{ posts(ids: [1]) }`
                 *
                 * Defined in the GraphQL spec.
                 *
                 * @see https://spec.graphql.org/draft/#sec-List.Input-Coercion
                 */
                $inputFieldValue = $inputCoercingService->maybeConvertInputValueFromSingleToList(
                    $inputFieldValue,
                    $inputFieldIsNonNullable,
                    $inputFieldIsArrayType,
                    $inputFieldIsArrayOfArraysType,
                );
            }

            // Validate that the expected array/non-array input is provided
            $errorCount = $objectTypeFieldResolutionFeedbackStore->getErrorCount();
            $inputCoercingService->validateInputArrayModifiers(
                $inputFieldTypeResolver,
                $inputFieldValue,
                $inputFieldName,
                $inputFieldIsNonNullable,
                $inputFieldIsArrayType,
                $inputFieldIsNonNullArrayItemsType,
                $inputFieldIsArrayOfArraysType,
                $inputFieldIsNonNullArrayOfArraysItemsType,
                $astNode,
                $objectTypeFieldResolutionFeedbackStore,
            );
            if ($objectTypeFieldResolutionFeedbackStore->getErrorCount() > $errorCount) {
                continue;
            }

            /**
             * DangerouslyNonScalar: just validate the cardinality,
             * no need to coerce the value
             */
            if ($isDangerouslyNonSpecificScalar) {
                $coercedInputValue->$inputFieldName = $inputFieldValue;
                continue;
            }

            /**
             * Cast (or "coerce" in GraphQL terms) the value
             *
             * @var bool $inputFieldIsNonNullable
             * @var bool $inputFieldIsArrayType
             * @var bool $inputFieldIsArrayOfArraysType
             */
            $coercedInputFieldValue = $inputCoercingService->coerceInputValue(
                $inputFieldTypeResolver,
                $inputFieldValue,
                $inputFieldName,
                $inputFieldIsNonNullable,
                $inputFieldIsArrayType,
                $inputFieldIsArrayOfArraysType,
                $astNode,
                $objectTypeFieldResolutionFeedbackStore,
            );
            if ($objectTypeFieldResolutionFeedbackStore->getErrorCount() > $errorCount) {
                continue;
            }

            // Custom validations for the field
            $this->validateCoercedInputFieldValue(
                $inputFieldTypeResolver,
                $inputFieldName,
                $coercedInputFieldValue,
                $astNode,
                $objectTypeFieldResolutionFeedbackStore,
            );
            if ($objectTypeFieldResolutionFeedbackStore->getErrorCount() > $errorCount) {
                continue;
            }

            // The input field is valid, add to the resulting InputObject
            $coercedInputValue->$inputFieldName = $coercedInputFieldValue;
        }

        /**
         * Check that all mandatory properties have been provided
         */
        foreach ($inputFieldNameTypeResolvers as $inputFieldName => $inputFieldTypeResolver) {
            if (property_exists($inputValue, $inputFieldName) && $inputValue->$inputFieldName !== null) {
                continue;
            }
            $inputFieldTypeModifiers = $this->getConsolidatedInputFieldTypeModifiers($inputFieldName);
            $inputFieldTypeModifiersIsMandatory = ($inputFieldTypeModifiers & SchemaTypeModifiers::MANDATORY) === SchemaTypeModifiers::MANDATORY;
            if (property_exists($inputValue, $inputFieldName) && $inputValue->$inputFieldName === null) {
                $inputFieldTypeModifiersIsMandatoryButNullable = ($inputFieldTypeModifiers & SchemaTypeModifiers::MANDATORY_BUT_NULLABLE) === SchemaTypeModifiers::MANDATORY_BUT_NULLABLE;
                if (!$inputFieldTypeModifiersIsMandatory || $inputFieldTypeModifiersIsMandatoryButNullable) {
                    continue;
                }
                $objectTypeFieldResolutionFeedbackStore->addError(
                    new ObjectTypeFieldResolutionFeedback(
                        new FeedbackItemResolution(
                            GraphQLSpecErrorFeedbackItemProvider::class,
                            GraphQLSpecErrorFeedbackItemProvider::E_5_6_4_B,
                            [
                                $inputFieldName,
                                $this->getMaybeNamespacedTypeName(),
                            ]
                        ),
                        $astNode,
                    ),
                );
                continue;
            }
            if (!$inputFieldTypeModifiersIsMandatory) {
                continue;
            }
            $objectTypeFieldResolutionFeedbackStore->addError(
                new ObjectTypeFieldResolutionFeedback(
                    new FeedbackItemResolution(
                        GraphQLSpecErrorFeedbackItemProvider::class,
                        GraphQLSpecErrorFeedbackItemProvider::E_5_6_4_A,
                        [
                            $inputFieldName,
                            $this->getMaybeNamespacedTypeName(),
                        ]
                    ),
                    $astNode,
                ),
            );
            continue;
        }

        // If there was any error, return it
        if ($objectTypeFieldResolutionFeedbackStore->getErrors() !== []) {
            return null;
        }

        // Add all missing properties which have a default value
        return $coercedInputValue;
    }

    final public function hasMandatoryWithNoDefaultValueInputFields(): bool
    {
        $inputFieldNameTypeResolvers = $this->getConsolidatedInputFieldNameTypeResolvers();
        foreach (array_keys($inputFieldNameTypeResolvers) as $inputFieldName) {
            $inputFieldTypeModifiers = $this->getConsolidatedInputFieldTypeModifiers($inputFieldName);
            $inputFieldDefaultValue = $this->getConsolidatedInputFieldDefaultValue($inputFieldName);
            $inputFieldTypeModifiersIsMandatory = ($inputFieldTypeModifiers & SchemaTypeModifiers::MANDATORY) === SchemaTypeModifiers::MANDATORY
                || ($inputFieldTypeModifiers & SchemaTypeModifiers::MANDATORY_BUT_NULLABLE) === SchemaTypeModifiers::MANDATORY_BUT_NULLABLE;
            if ($inputFieldTypeModifiersIsMandatory && $inputFieldDefaultValue === null) {
                return true;
            }
        }
        return false;
    }

    /**
     * Indicate if the InputObject must be initialized to {}.
     * By default true, but will be false for OneofInputObject
     */
    protected function initializeInputFieldInputObjectValue(): bool
    {
        return true;
    }

    /**
     * Custom validations to execute on the input field.
     */
    protected function validateCoercedInputFieldValue(
        InputTypeResolverInterface $inputFieldTypeResolver,
        string $inputFieldName,
        mixed $coercedInputFieldValue,
        AstInterface $astNode,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
    }

    /**
     * Obtain the deprecation messages for an input value.
     *
     * @param string|int|float|bool|stdClass $inputValue the (custom) scalar in any format: itself (eg: an object) or its representation (eg: as a string)
     * @return string[] The deprecation messages
     */
    final public function getInputValueDeprecationMessages(string|int|float|bool|stdClass $inputValue): array
    {
        $inputValueDeprecationMessages = [];
        $inputFieldNameTypeResolvers = $this->getConsolidatedInputFieldNameTypeResolvers();
        $inputCoercingService = $this->getInputCoercingService();

        foreach ((array)$inputValue as $inputFieldName => $inputFieldValue) {
            // Check that the input field exists
            $inputFieldTypeResolver = $inputFieldNameTypeResolvers[$inputFieldName];
            if ($inputFieldTypeResolver instanceof DeprecatableInputTypeResolverInterface) {
                $inputFieldTypeModifiers = $this->getConsolidatedInputFieldTypeModifiers($inputFieldName);
                $inputFieldIsArrayOfArraysType = ($inputFieldTypeModifiers & SchemaTypeModifiers::IS_ARRAY_OF_ARRAYS) === SchemaTypeModifiers::IS_ARRAY_OF_ARRAYS;
                $inputFieldIsArrayType = $inputFieldIsArrayOfArraysType || ($inputFieldTypeModifiers & SchemaTypeModifiers::IS_ARRAY) === SchemaTypeModifiers::IS_ARRAY;

                $deprecationMessages = $inputCoercingService->getInputValueDeprecationMessages(
                    $inputFieldTypeResolver,
                    $inputFieldValue,
                    $inputFieldIsArrayType,
                    $inputFieldIsArrayOfArraysType,
                );
                $inputValueDeprecationMessages = array_merge(
                    $inputValueDeprecationMessages,
                    $deprecationMessages
                );
            }
        }
        return array_unique($inputValueDeprecationMessages);
    }

    /**
     * Input fields may not be directly visible in the schema,
     * eg: because they are used only by the application, and must not
     * be exposed to the user
     */
    public function skipExposingInputFieldInSchema(string $inputFieldName): bool
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        if ($moduleConfiguration->skipExposingDangerouslyNonSpecificScalarTypeTypeInSchema()) {
            /**
             * If `DangerouslyNonSpecificScalar` is disabled, do not expose the input field if:
             *
             *   - its type is `DangerouslyNonSpecificScalar`
             */
            $inputFieldNameTypeResolvers = $this->getConsolidatedInputFieldNameTypeResolvers();
            $inputFieldTypeResolver = $inputFieldNameTypeResolvers[$inputFieldName];
            if ($inputFieldTypeResolver === $this->getDangerouslyNonSpecificScalarTypeScalarTypeResolver()) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get the "schema" properties as for the inputFieldName
     *
     * @return array<string,mixed>
     */
    final public function getInputFieldSchemaDefinition(string $inputFieldName): array
    {
        // Cache the result
        if (isset($this->schemaDefinitionForInputFieldCache[$inputFieldName])) {
            return $this->schemaDefinitionForInputFieldCache[$inputFieldName];
        }

        $inputFieldNameTypeResolvers = $this->getConsolidatedInputFieldNameTypeResolvers();
        $inputFieldTypeResolver = $inputFieldNameTypeResolvers[$inputFieldName];
        $inputFieldDescription =
            $this->getConsolidatedInputFieldDescription($inputFieldName)
            ?? $inputFieldTypeResolver->getTypeDescription();
        $inputFieldSchemaDefinition = $this->getTypeSchemaDefinition(
            $inputFieldName,
            $inputFieldTypeResolver,
            $inputFieldDescription,
            $this->getConsolidatedInputFieldDefaultValue($inputFieldName),
            $this->getConsolidatedInputFieldTypeModifiers($inputFieldName),
        );
        $inputFieldSchemaDefinition[SchemaDefinition::EXTENSIONS] = $this->getConsolidatedInputFieldExtensionsSchemaDefinition($inputFieldName);
        $this->schemaDefinitionForInputFieldCache[$inputFieldName] = $inputFieldSchemaDefinition;
        return $this->schemaDefinitionForInputFieldCache[$inputFieldName];
    }

    /**
     * @return array<string,mixed>
     */
    protected function getInputFieldExtensionsSchemaDefinition(string $inputFieldName): array
    {
        return [
            SchemaDefinition::IS_SENSITIVE_DATA_ELEMENT => in_array($inputFieldName, $this->getConsolidatedSensitiveInputFieldNames()),
        ];
    }

    /**
     * Consolidation of the schema inputs. Call this function to read the data
     * instead of the individual functions, since it applies hooks to override/extend.
     *
     * @return array<string,mixed>
     */
    final public function getConsolidatedInputFieldExtensionsSchemaDefinition(string $inputFieldName): array
    {
        if (array_key_exists($inputFieldName, $this->consolidatedInputFieldExtensionsCache)) {
            return $this->consolidatedInputFieldExtensionsCache[$inputFieldName];
        }
        $this->consolidatedInputFieldExtensionsCache[$inputFieldName] = App::applyFilters(
            HookNames::INPUT_FIELD_EXTENSIONS,
            $this->getInputFieldExtensionsSchemaDefinition($inputFieldName),
            $this,
            $inputFieldName,
        );
        return $this->consolidatedInputFieldExtensionsCache[$inputFieldName];
    }
    /**
     * Validate constraints on the input's value
     */
    final public function validateInputValue(
        stdClass $inputValue,
        AstInterface $astNode,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        $inputFieldNameTypeResolvers = $this->getConsolidatedInputFieldNameTypeResolvers();
        foreach ((array)$inputValue as $inputFieldName => $inputFieldValue) {
            $this->validateInputFieldValue(
                $inputFieldName,
                $inputFieldValue,
                $astNode,
                $objectTypeFieldResolutionFeedbackStore,
            );

            /**
             * If the arg is an InputObject, let it perform validations on its input fields.
             * Please notice the value can also be `null`, as the input
             * could be nullable (it could receive even an explicit `null`)
             */
            $inputFieldTypeResolver = $inputFieldNameTypeResolvers[$inputFieldName];
            if (!($inputFieldTypeResolver instanceof InputObjectTypeResolverInterface)) {
                continue;
            }
            /** @var InputObjectTypeResolverInterface $inputFieldTypeResolver */
            $inputFieldTypeModifiers = $this->getConsolidatedInputFieldTypeModifiers($inputFieldName);
            $inputFieldIsArrayOfArraysType = ($inputFieldTypeModifiers & SchemaTypeModifiers::IS_ARRAY_OF_ARRAYS) === SchemaTypeModifiers::IS_ARRAY_OF_ARRAYS;
            $inputFieldIsArrayType = $inputFieldIsArrayOfArraysType || ($inputFieldTypeModifiers & SchemaTypeModifiers::IS_ARRAY) === SchemaTypeModifiers::IS_ARRAY;
            if ($inputFieldIsArrayOfArraysType) {
                /** @var array<array<stdClass|null>> $inputFieldValue */
                foreach ($inputFieldValue as $arrayInputFieldValue) {
                    foreach ($arrayInputFieldValue as $arrayOfArraysInputFieldValue) {
                        if ($arrayOfArraysInputFieldValue === null) {
                            continue;
                        }
                        $inputFieldTypeResolver->validateInputValue(
                            $arrayOfArraysInputFieldValue,
                            $astNode,
                            $objectTypeFieldResolutionFeedbackStore,
                        );
                    }
                }
            } elseif ($inputFieldIsArrayType) {
                /** @var array<stdClass|null> $inputFieldValue */
                foreach ($inputFieldValue as $arrayInputFieldValue) {
                    if ($arrayInputFieldValue === null) {
                        continue;
                    }
                    $inputFieldTypeResolver->validateInputValue(
                        $arrayInputFieldValue,
                        $astNode,
                        $objectTypeFieldResolutionFeedbackStore,
                    );
                }
            } else {
                /** @var stdClass|null $inputFieldValue */
                if ($inputFieldValue === null) {
                    continue;
                }
                $inputFieldTypeResolver->validateInputValue(
                    $inputFieldValue,
                    $astNode,
                    $objectTypeFieldResolutionFeedbackStore,
                );
            }
        }
    }
    /**
     * Validate constraints on the input field's value
     */
    protected function validateInputFieldValue(
        string $inputFieldName,
        mixed $inputFieldValue,
        AstInterface $astNode,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
    }
}
