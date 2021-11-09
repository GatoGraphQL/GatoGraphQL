<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolvers\InputObjectType;

use PoP\ComponentModel\ErrorHandling\Error;
use PoP\ComponentModel\Misc\GeneralUtils;
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

    public function getInputObjectFieldDescription(string $inputObjectFieldName): ?string
    {
        return null;
    }
    public function getInputObjectFieldDeprecationMessage(string $inputObjectFieldName): ?string
    {
        return null;
    }
    public function getInputObjectFieldDefaultValue(string $inputObjectFieldName): mixed
    {
        return null;
    }
    public function getInputObjectFieldTypeModifiers(string $inputObjectFieldName): int
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
        $coercedInputObjectValue = new stdClass();
        $inputObjectFieldNameTypeResolvers = $this->getInputObjectFieldNameTypeResolvers();

        /**
         * Inject all properties with default value
         */
        foreach ($inputObjectFieldNameTypeResolvers as $fieldName => $inputTypeResolver) {
            if (isset($inputValue->$fieldName) || ($inputObjectFieldDefaultValue = $this->getInputObjectFieldDefaultValue($fieldName)) === null) {
                continue;
            }
            $inputValue->$fieldName = $inputObjectFieldDefaultValue;
        }

        /** @var Error[] */
        $errors = [];
        foreach ((array)$inputValue as $fieldName => $propertyValue) {
            // Check that the property exists
            $inputTypeResolver = $inputObjectFieldNameTypeResolvers[$fieldName] ?? null;
            if ($inputTypeResolver === null) {
                $errors[] = new Error(
                    $this->getErrorCode(),
                    sprintf(
                        $this->getTranslationAPI()->__('There is no property \'%s\' in input object \'%s\''),
                        $fieldName,
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
                $coercedInputObjectValue->$fieldName = $this->getDangerouslyDynamicScalarTypeResolver()->coerceValue($propertyValue);
                continue;
            }

            $inputObjectFieldTypeModifiers = $this->getInputObjectFieldTypeModifiers($fieldName);
            $propertyIsArrayType = ($inputObjectFieldTypeModifiers & SchemaTypeModifiers::IS_ARRAY) === SchemaTypeModifiers::IS_ARRAY;
            $propertyIsNonNullArrayItemsType = ($inputObjectFieldTypeModifiers & SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY) === SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY;
            $propertyIsArrayOfArraysType = ($inputObjectFieldTypeModifiers & SchemaTypeModifiers::IS_ARRAY_OF_ARRAYS) === SchemaTypeModifiers::IS_ARRAY_OF_ARRAYS;
            $propertyIsNonNullArrayOfArraysItemsType = ($inputObjectFieldTypeModifiers & SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY_OF_ARRAYS) === SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY_OF_ARRAYS;

            /**
             * Support passing a single value where a list is expected:
             * `{ posts(ids: 1) }` means `{ posts(ids: [1]) }`
             *
             * Defined in the GraphQL spec.
             *
             * @see https://spec.graphql.org/draft/#sec-List.Input-Coercion
             */
            $propertyValue = $this->getInputCoercingService()->maybeConvertInputValueFromSingleToList(
                $propertyValue,
                $propertyIsArrayType,
                $propertyIsArrayOfArraysType,
            );

            // Validate that the expected array/non-array input is provided
            $maybeErrorMessage = $this->getInputCoercingService()->validateInputArrayModifiers(
                $propertyValue,
                $fieldName,
                $propertyIsArrayType,
                $propertyIsNonNullArrayItemsType,
                $propertyIsArrayOfArraysType,
                $propertyIsNonNullArrayOfArraysItemsType,
            );
            if ($maybeErrorMessage !== null) {
                $errors[] = new Error(
                    $this->getErrorCode(),
                    $maybeErrorMessage
                );
                continue;
            }

            // Cast (or "coerce" in GraphQL terms) the value
            $coercedInputPropertyValue = $this->getInputCoercingService()->coerceInputValue(
                $inputTypeResolver,
                $propertyValue,
                $propertyIsArrayType,
                $propertyIsArrayOfArraysType,
            );

            // Check if the coercion produced errors
            $maybeCoercedInputPropertyValueErrors = $this->getInputCoercingService()->extractErrorsFromCoercedInputValue(
                $coercedInputPropertyValue,
                $propertyIsArrayType,
                $propertyIsArrayOfArraysType,
            );
            if ($maybeCoercedInputPropertyValueErrors !== []) {
                $castingError = new Error(
                    $this->getErrorCode(),
                    sprintf(
                        $this->getTranslationAPI()->__('Casting property \'%s\' of type \'%s\' produced errors', 'component-model'),
                        $fieldName,
                        $inputTypeResolver->getMaybeNamespacedTypeName()
                    ),
                    null,
                    $maybeCoercedInputPropertyValueErrors
                );
                $errors[] = $castingError;
                continue;
            }

            // The property is valid, add to the resulting InputObject
            $coercedInputObjectValue->$fieldName = $coercedInputPropertyValue;
        }

        /**
         * Check that all mandatory properties have been provided
         */
        foreach ($inputObjectFieldNameTypeResolvers as $fieldName => $inputTypeResolver) {
            if (isset($inputValue->$fieldName)) {
                continue;
            }
            $inputObjectFieldTypeModifiers = $this->getInputObjectFieldTypeModifiers($fieldName);
            $inputObjectFieldTypeModifiersIsMandatory = ($inputObjectFieldTypeModifiers & SchemaTypeModifiers::MANDATORY) === SchemaTypeModifiers::MANDATORY;
            if (!$inputObjectFieldTypeModifiersIsMandatory) {
                continue;
            }
            $errors[] = new Error(
                $this->getErrorCode(),
                sprintf(
                    $this->getTranslationAPI()->__('Mandatory property \'%s\' in input object \'%s\' has not been provided'),
                    $fieldName,
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
        return $coercedInputObjectValue;
    }
}
