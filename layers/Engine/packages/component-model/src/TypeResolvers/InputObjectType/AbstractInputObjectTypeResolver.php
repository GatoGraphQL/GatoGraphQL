<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolvers\InputObjectType;

use PoP\ComponentModel\ErrorHandling\Error;
use PoP\ComponentModel\Schema\InputCoercingServiceInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\AbstractTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\DangerouslyDynamicScalarTypeResolver;
use stdClass;

abstract class AbstractInputObjectTypeResolver extends AbstractTypeResolver implements InputObjectTypeResolverInterface
{
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

    public function getInputFieldDescription(string $inputFieldName): ?string
    {
        return null;
    }
    public function getInputFieldDeprecationMessage(string $inputFieldName): ?string
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

    final public function coerceValue(string|int|float|bool|stdClass $inputValue): string|int|float|bool|stdClass|Error
    {
        if (!($inputValue instanceof stdClass)) {
            return $this->getError(
                sprintf(
                    $this->getTranslationAPI()->__('Input object of type \'%s\' cannot be casted from input value \'%s\'', 'component-model'),
                    $this->getMaybeNamespacedTypeName(),
                    $inputValue
                )
            );
        }
        return $this->coerceInputObjectValue($inputValue);
    }

    final protected function coerceInputObjectValue(stdClass $inputValue): stdClass|Error
    {
        $coercedInputValue = new stdClass();
        $inputFieldNameTypeResolvers = $this->getInputFieldNameTypeResolvers();

        /**
         * Inject all properties with default value
         */
        foreach ($inputFieldNameTypeResolvers as $inputFieldName => $inputTypeResolver) {
            if (isset($inputValue->$inputFieldName) || ($inputFieldDefaultValue = $this->getInputFieldDefaultValue($inputFieldName)) === null) {
                continue;
            }
            $inputValue->$inputFieldName = $inputFieldDefaultValue;
        }

        /** @var Error[] */
        $errors = [];
        foreach ((array)$inputValue as $inputFieldName => $inputFieldValue) {
            // Check that the input field exists
            $inputTypeResolver = $inputFieldNameTypeResolvers[$inputFieldName] ?? null;
            if ($inputTypeResolver === null) {
                $errors[] = new Error(
                    $this->getErrorCode(),
                    sprintf(
                        $this->getTranslationAPI()->__('There is no input field \'%s\' in input object \'%s\''),
                        $inputFieldName,
                        $this->getMaybeNamespacedTypeName()
                    )
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
            if ($inputTypeResolver === $this->getDangerouslyDynamicScalarTypeResolver()) {
                $coercedInputValue->$inputFieldName = $this->getDangerouslyDynamicScalarTypeResolver()->coerceValue($inputFieldValue);
                continue;
            }

            $inputFieldTypeModifiers = $this->getInputFieldTypeModifiers($inputFieldName);
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
                    $maybeErrorMessage
                );
                continue;
            }

            // Cast (or "coerce" in GraphQL terms) the value
            $coercedInputFieldValue = $this->getInputCoercingService()->coerceInputValue(
                $inputTypeResolver,
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
                $castingError = new Error(
                    $this->getErrorCode(),
                    sprintf(
                        $this->getTranslationAPI()->__('Casting input field \'%s\' of type \'%s\' produced errors', 'component-model'),
                        $inputFieldName,
                        $inputTypeResolver->getMaybeNamespacedTypeName()
                    ),
                    null,
                    $maybeCoercedInputFieldValueErrors
                );
                $errors[] = $castingError;
                continue;
            }

            // The input field is valid, add to the resulting InputObject
            $coercedInputValue->$inputFieldName = $coercedInputFieldValue;
        }

        /**
         * Check that all mandatory properties have been provided
         */
        foreach ($inputFieldNameTypeResolvers as $inputFieldName => $inputTypeResolver) {
            if (isset($inputValue->$inputFieldName)) {
                continue;
            }
            $inputFieldTypeModifiers = $this->getInputFieldTypeModifiers($inputFieldName);
            $inputFieldTypeModifiersIsMandatory = ($inputFieldTypeModifiers & SchemaTypeModifiers::MANDATORY) === SchemaTypeModifiers::MANDATORY;
            if (!$inputFieldTypeModifiersIsMandatory) {
                continue;
            }
            $errors[] = new Error(
                $this->getErrorCode(),
                sprintf(
                    $this->getTranslationAPI()->__('Mandatory input field \'%s\' in input object \'%s\' has not been provided'),
                    $inputFieldName,
                    $this->getMaybeNamespacedTypeName()
                )
            );
            continue;
        }

        // If there was any error, return it
        if ($errors) {
            return $this->getError(
                sprintf(
                    $this->getTranslationAPI()->__('Casting input object of type \'%s\' produced errors', 'component-model'),
                    $this->getMaybeNamespacedTypeName()
                ),
                $errors
            );
        }

        // Add all missing properties which have a default value
        return $coercedInputValue;
    }
}
