<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolvers\InputObjectType;

use PoP\Root\App;
use PoP\ComponentModel\Component;
use PoP\ComponentModel\ComponentConfiguration;
use PoP\ComponentModel\Error\Error;
use PoP\ComponentModel\Feedback\Tokens;
use PoP\ComponentModel\Resolvers\TypeSchemaDefinitionResolverTrait;
use PoP\ComponentModel\Schema\InputCoercingServiceInterface;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\AbstractTypeResolver;
use PoP\ComponentModel\TypeResolvers\DeprecatableInputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\DangerouslyDynamicScalarTypeResolver;
use stdClass;

abstract class AbstractInputObjectTypeResolver extends AbstractTypeResolver implements InputObjectTypeResolverInterface
{
    use TypeSchemaDefinitionResolverTrait;

    /** @var array<string, array> */
    protected array $schemaDefinitionForInputFieldCache = [];
    /** @var array<string, InputTypeResolverInterface>|null */
    private ?array $consolidatedInputFieldNameTypeResolversCache = null;
    /** @var array<string, ?string> */
    private array $consolidatedInputFieldDescriptionCache = [];
    /** @var array<string, mixed> */
    private array $consolidatedInputFieldDefaultValueCache = [];
    /** @var array<string, int> */
    private array $consolidatedInputFieldTypeModifiersCache = [];
    /** @var array<string, array<string,mixed>> */
    private array $consolidatedInputFieldExtensionsCache = [];
    /** @var string[]|null */
    private ?array $consolidatedAdminInputFieldNames = null;

    private ?DangerouslyDynamicScalarTypeResolver $dangerouslyDynamicScalarTypeResolver = null;
    private ?InputCoercingServiceInterface $inputCoercingService = null;

    final public function setDangerouslyDynamicScalarTypeResolver(DangerouslyDynamicScalarTypeResolver $dangerouslyDynamicScalarTypeResolver): void
    {
        $this->dangerouslyDynamicScalarTypeResolver = $dangerouslyDynamicScalarTypeResolver;
    }
    final protected function getDangerouslyDynamicScalarTypeResolver(): DangerouslyDynamicScalarTypeResolver
    {
        return $this->dangerouslyDynamicScalarTypeResolver ??= $this->instanceManager->getInstance(DangerouslyDynamicScalarTypeResolver::class);
    }
    final public function setInputCoercingService(InputCoercingServiceInterface $inputCoercingService): void
    {
        $this->inputCoercingService = $inputCoercingService;
    }
    final protected function getInputCoercingService(): InputCoercingServiceInterface
    {
        return $this->inputCoercingService ??= $this->instanceManager->getInstance(InputCoercingServiceInterface::class);
    }

    public function getAdminInputFieldNames(): array
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

        // Exclude the admin input fields, if "Admin" Schema is not enabled
        /** @var ComponentConfiguration */
        $componentConfiguration = App::getComponent(Component::class)->getConfiguration();
        if (!$componentConfiguration->enableAdminSchema()) {
            $adminInputFieldNames = $this->getConsolidatedAdminInputFieldNames();
            $consolidatedInputFieldNameTypeResolvers = array_filter(
                $consolidatedInputFieldNameTypeResolvers,
                fn (string $inputFieldName) => !in_array($inputFieldName, $adminInputFieldNames),
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
    final public function getConsolidatedAdminInputFieldNames(): array
    {
        if ($this->consolidatedAdminInputFieldNames !== null) {
            return $this->consolidatedAdminInputFieldNames;
        }

        $this->consolidatedAdminInputFieldNames = App::applyFilters(
            HookNames::ADMIN_INPUT_FIELD_NAMES,
            $this->getAdminInputFieldNames(),
            $this,
        );
        return $this->consolidatedAdminInputFieldNames;
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

    final public function coerceValue(string|int|float|bool|stdClass $inputValue): string|int|float|bool|object
    {
        if (!($inputValue instanceof stdClass)) {
            return $this->getError(
                sprintf(
                    $this->__('Input object of type \'%s\' cannot be casted from input value \'%s\'', 'component-model'),
                    $this->getMaybeNamespacedTypeName(),
                    $inputValue
                )
            );
        }
        return $this->coerceInputObjectValue($inputValue);
    }

    protected function coerceInputObjectValue(stdClass $inputValue): stdClass|Error
    {
        $coercedInputValue = new stdClass();
        $inputFieldNameTypeResolvers = $this->getConsolidatedInputFieldNameTypeResolvers();

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
            if (($inputFieldDefaultValue = $this->getConsolidatedInputFieldDefaultValue($inputFieldName)) !== null) {
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
            if ($inputFieldTypeResolver instanceof InputObjectTypeResolverInterface
                && $this->initializeInputFieldInputObjectValue()
            ) {
                $inputFieldTypeModifiers = $this->getConsolidatedInputFieldTypeModifiers($inputFieldName);
                $inputFieldTypeModifiersIsMandatory = ($inputFieldTypeModifiers & SchemaTypeModifiers::MANDATORY) === SchemaTypeModifiers::MANDATORY;
                if (!$inputFieldTypeModifiersIsMandatory && !$this->hasMandatoryInputFields($inputFieldTypeResolver)) {
                    $inputValue->$inputFieldName = new stdClass();
                }
            }
        }

        /** @var Error[] */
        $errors = [];
        foreach ((array)$inputValue as $inputFieldName => $inputFieldValue) {
            // Check that the input field exists
            $inputFieldTypeResolver = $inputFieldNameTypeResolvers[$inputFieldName] ?? null;
            if ($inputFieldTypeResolver === null) {
                $errors[] = new Error(
                    $this->getErrorCode(),
                    sprintf(
                        $this->__('There is no input field \'%s\' in input object \'%s\''),
                        $inputFieldName,
                        $this->getMaybeNamespacedTypeName()
                    ),
                    [
                        Tokens::ARGUMENT_PATH => [$inputFieldName],
                    ]
                );
                continue;
            }

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
            if ($inputFieldTypeResolver === $this->getDangerouslyDynamicScalarTypeResolver()) {
                $coercedInputValue->$inputFieldName = $this->getDangerouslyDynamicScalarTypeResolver()->coerceValue($inputFieldValue);
                continue;
            }

            $inputFieldTypeModifiers = $this->getConsolidatedInputFieldTypeModifiers($inputFieldName);
            $inputFieldIsArrayType = ($inputFieldTypeModifiers & SchemaTypeModifiers::IS_ARRAY) === SchemaTypeModifiers::IS_ARRAY;
            $inputFieldIsNonNullArrayItemsType = ($inputFieldTypeModifiers & SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY) === SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY;
            $inputFieldIsArrayOfArraysType = ($inputFieldTypeModifiers & SchemaTypeModifiers::IS_ARRAY_OF_ARRAYS) === SchemaTypeModifiers::IS_ARRAY_OF_ARRAYS;
            $inputFieldIsNonNullArrayOfArraysItemsType = ($inputFieldTypeModifiers & SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY_OF_ARRAYS) === SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY_OF_ARRAYS;

            /**
             * Support passing a single value where a list is expected:
             * `{ posts(ids: 1) }` means `{ posts(ids: [1]) }`
             *
             * Defined in the GraphQL spec.
             *
             * @see https://spec.graphql.org/draft/#sec-List.Input-Coercion
             */
            $inputFieldValue = $this->getInputCoercingService()->maybeConvertInputValueFromSingleToList(
                $inputFieldValue,
                $inputFieldIsArrayType,
                $inputFieldIsArrayOfArraysType,
            );

            // Validate that the expected array/non-array input is provided
            $maybeErrorMessage = $this->getInputCoercingService()->validateInputArrayModifiers(
                $inputFieldValue,
                $inputFieldName,
                $inputFieldIsArrayType,
                $inputFieldIsNonNullArrayItemsType,
                $inputFieldIsArrayOfArraysType,
                $inputFieldIsNonNullArrayOfArraysItemsType,
            );
            if ($maybeErrorMessage !== null) {
                $errors[] = new Error(
                    $this->getErrorCode(),
                    $maybeErrorMessage,
                    [
                        Tokens::ARGUMENT_PATH => [$inputFieldName],
                    ]
                );
                continue;
            }

            // Cast (or "coerce" in GraphQL terms) the value
            $coercedInputFieldValue = $this->getInputCoercingService()->coerceInputValue(
                $inputFieldTypeResolver,
                $inputFieldValue,
                $inputFieldIsArrayType,
                $inputFieldIsArrayOfArraysType,
            );

            // Check if the coercion produced errors
            $maybeCoercedInputFieldValueErrors = $this->getInputCoercingService()->extractErrorsFromCoercedInputValue(
                $coercedInputFieldValue,
                $inputFieldIsArrayType,
                $inputFieldIsArrayOfArraysType,
            );
            if ($maybeCoercedInputFieldValueErrors !== []) {
                $errors[] = $this->convergeCoercedInputFieldValueError(
                    $inputFieldTypeResolver,
                    $inputFieldName,
                    $maybeCoercedInputFieldValueErrors,
                );
                continue;
            }

            // Custom validations for the field
            $maybeCoercedInputFieldValueErrorMessages = $this->resolveCoercedInputFieldValueErrorMessages(
                $inputFieldTypeResolver,
                $inputFieldName,
                $coercedInputFieldValue,
            );
            if ($maybeCoercedInputFieldValueErrorMessages !== []) {
                foreach ($maybeCoercedInputFieldValueErrorMessages as $coercedInputFieldValueErrorMessage) {
                    $errors[] = new Error(
                        $this->getErrorCode(),
                        $coercedInputFieldValueErrorMessage,
                        [
                            Tokens::ARGUMENT_PATH => [$inputFieldName],
                        ]
                    );
                }
                continue;
            }

            // The input field is valid, add to the resulting InputObject
            $coercedInputValue->$inputFieldName = $coercedInputFieldValue;
        }

        /**
         * Check that all mandatory properties have been provided
         */
        foreach ($inputFieldNameTypeResolvers as $inputFieldName => $inputFieldTypeResolver) {
            // Providing a `null` value to a mandatory input is still valid
            if (property_exists($inputValue, $inputFieldName)) {
                continue;
            }
            $inputFieldTypeModifiers = $this->getConsolidatedInputFieldTypeModifiers($inputFieldName);
            $inputFieldTypeModifiersIsMandatory = ($inputFieldTypeModifiers & SchemaTypeModifiers::MANDATORY) === SchemaTypeModifiers::MANDATORY;
            if (!$inputFieldTypeModifiersIsMandatory) {
                continue;
            }
            $errors[] = new Error(
                $this->getErrorCode(),
                sprintf(
                    $this->__('Mandatory input field \'%s\' in input object \'%s\' has not been provided'),
                    $inputFieldName,
                    $this->getMaybeNamespacedTypeName()
                ),
                [
                    Tokens::ARGUMENT_PATH => [$inputFieldName],
                ]
            );
            continue;
        }

        // If there was any error, return it
        if ($errors) {
            return count($errors) === 1 ?
                $errors[0]
                : $this->getError(
                    sprintf(
                        $this->__('Casting input object of type \'%s\' produced errors', 'component-model'),
                        $this->getMaybeNamespacedTypeName()
                    ),
                    $errors
                );
        }

        // Add all missing properties which have a default value
        return $coercedInputValue;
    }

    protected function hasMandatoryInputFields(InputObjectTypeResolverInterface $inputObjectTypeResolver): bool
    {
        $inputFieldNameTypeResolvers = $inputObjectTypeResolver->getConsolidatedInputFieldNameTypeResolvers();
        foreach (array_keys($inputFieldNameTypeResolvers) as $inputFieldName) { 
            $inputFieldTypeModifiers = $this->getConsolidatedInputFieldTypeModifiers($inputFieldName);
            $inputFieldTypeModifiersIsMandatory = ($inputFieldTypeModifiers & SchemaTypeModifiers::MANDATORY) === SchemaTypeModifiers::MANDATORY;
            if ($inputFieldTypeModifiersIsMandatory) {
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
     * Converge multiple Errors as nested under a single Error
     *
     * @param Error[] $coercedInputFieldValueErrors
     */
    protected function convergeCoercedInputFieldValueError(
        InputTypeResolverInterface $inputFieldTypeResolver,
        string $inputFieldName,
        array $coercedInputFieldValueErrors,
    ): Error {
        // Prepend the arg path to the error(s)
        foreach ($coercedInputFieldValueErrors as $error) {
            $this->prependArgPathToError($error, [$inputFieldName]);
        }

        // Only 1 Error: bubble it up directly
        if (count($coercedInputFieldValueErrors) === 1) {
            return $coercedInputFieldValueErrors[0];
        }

        // Many nested errors: Create a new Error with all of them
        return new Error(
            $this->getErrorCode(),
            sprintf(
                $this->__('Casting input field \'%s\' of type \'%s\' produced errors', 'component-model'),
                $inputFieldName,
                $inputFieldTypeResolver->getMaybeNamespacedTypeName()
            ),
            [
                Tokens::ARGUMENT_PATH => [$inputFieldName],
            ],
            $coercedInputFieldValueErrors
        );
    }

    /**
     * Custom validations to execute on the input field.
     *
     * @return string[] The produced error messages, if any
     */
    protected function resolveCoercedInputFieldValueErrorMessages(
        InputTypeResolverInterface $inputFieldTypeResolver,
        string $inputFieldName,
        mixed $coercedInputFieldValue,
    ): array {
        return [];
    }

    /**
     * @param string[] $argPath
     */
    protected function prependArgPathToError(Error &$error, array $argPath): void
    {
        $errorData = $error->getData();
        $error->addData(Tokens::ARGUMENT_PATH, array_merge(
            $argPath,
            $errorData[Tokens::ARGUMENT_PATH] ?? []
        ));
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

        foreach ((array)$inputValue as $inputFieldName => $inputFieldValue) {
            // Check that the input field exists
            $inputFieldTypeResolver = $inputFieldNameTypeResolvers[$inputFieldName];
            if ($inputFieldTypeResolver instanceof DeprecatableInputTypeResolverInterface) {
                $inputFieldTypeModifiers = $this->getConsolidatedInputFieldTypeModifiers($inputFieldName);
                $inputFieldIsArrayType = ($inputFieldTypeModifiers & SchemaTypeModifiers::IS_ARRAY) === SchemaTypeModifiers::IS_ARRAY;
                $inputFieldIsArrayOfArraysType = ($inputFieldTypeModifiers & SchemaTypeModifiers::IS_ARRAY_OF_ARRAYS) === SchemaTypeModifiers::IS_ARRAY_OF_ARRAYS;

                $deprecationMessages = $this->getInputCoercingService()->getInputValueDeprecationMessages(
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
        /** @var ComponentConfiguration */
        $componentConfiguration = App::getComponent(Component::class)->getConfiguration();
        if ($componentConfiguration->skipExposingDangerouslyDynamicScalarTypeInSchema()) {
            /**
             * If `DangerouslyDynamic` is disabled, do not expose the input field if:
             *
             *   - its type is `DangerouslyDynamic`
             */
            $inputFieldNameTypeResolvers = $this->getConsolidatedInputFieldNameTypeResolvers();
            $inputFieldTypeResolver = $inputFieldNameTypeResolvers[$inputFieldName];
            if ($inputFieldTypeResolver === $this->getDangerouslyDynamicScalarTypeResolver()) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get the "schema" properties as for the inputFieldName
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

    protected function getInputFieldExtensionsSchemaDefinition(string $inputFieldName): array
    {
        return [
            SchemaDefinition::IS_ADMIN_ELEMENT => in_array($inputFieldName, $this->getConsolidatedAdminInputFieldNames()),
        ];
    }

    /**
     * Consolidation of the schema inputs. Call this function to read the data
     * instead of the individual functions, since it applies hooks to override/extend.
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
     * Validate constraints on the input field's value
     *
     * @return string[] Error messages
     */
    final public function validateInputValue(stdClass $inputValue): array
    {
        $errors = [];
        foreach ((array)$inputValue as $inputFieldName => $inputFieldValue) {
            $errors = array_merge(
                $errors,
                $this->validateInputFieldValue($inputFieldName, $inputFieldValue)
            );
        }
        return $errors;
    }
    /**
     * Validate constraints on the input field's value
     *
     * @return string[] Error messages
     */
    protected function validateInputFieldValue(string $inputFieldName, mixed $inputFieldValue): array
    {
        return [];
    }
}
