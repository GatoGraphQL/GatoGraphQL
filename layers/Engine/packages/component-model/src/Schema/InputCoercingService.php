<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Schema;

use PoP\ComponentModel\ComponentConfiguration;
use PoP\ComponentModel\Error\Error;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\BasicService\BasicServiceTrait;
use PoP\ComponentModel\TypeResolvers\DeprecatableInputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;

class InputCoercingService implements InputCoercingServiceInterface
{
    use BasicServiceTrait;

    /**
     * Support passing a single value where a list is expected:
     * `{ posts(ids: 1) }` means `{ posts(ids: [1]) }`
     *
     * Defined in the GraphQL spec.
     *
     * @see https://spec.graphql.org/draft/#sec-List.Input-Coercion
     */
    public function maybeConvertInputValueFromSingleToList(
        mixed $inputValue,
        bool $inputIsArrayType,
        bool $inputIsArrayOfArraysType,
    ): mixed {
        if (
            is_array($inputValue)
            || !ComponentConfiguration::convertInputValueFromSingleToList()
        ) {
            return $inputValue;
        }
        if ($inputIsArrayOfArraysType) {
            return [[$inputValue]];
        }
        if ($inputIsArrayType) {
            return [$inputValue];
        }
        return $inputValue;
    }

    /**
     * Validate that the expected array/non-array input is provided,
     * checking that the WrappingType is respected.
     *
     * Eg: `["hello"]` must be `[String]`, can't be `[[String]]` or `String`.
     *
     * @return string|null The error message if the validation fails, or null otherwise
     */
    public function validateInputArrayModifiers(
        mixed $inputValue,
        string $inputName,
        bool $inputIsArrayType,
        bool $inputIsNonNullArrayItemsType,
        bool $inputIsArrayOfArraysType,
        bool $inputIsNonNullArrayOfArraysItemsType,
    ): ?string {
        if (
            !$inputIsArrayType
            && is_array($inputValue)
        ) {
            return sprintf(
                $this->getTranslationAPI()->__('Argument \'%s\' does not expect an array, but array \'%s\' was provided', 'pop-component-model'),
                $inputName,
                json_encode($inputValue)
            );
        }
        if (
            $inputIsArrayType
            && !is_array($inputValue)
        ) {
            return sprintf(
                $this->getTranslationAPI()->__('Argument \'%s\' expects an array, but value \'%s\' was provided', 'pop-component-model'),
                $inputName,
                $inputValue
            );
        }
        if (
            $inputIsNonNullArrayItemsType
            && is_array($inputValue)
            && array_filter(
                $inputValue,
                fn ($arrayItem) => $arrayItem === null
            )
        ) {
            return sprintf(
                $this->getTranslationAPI()->__('Argument \'%s\' cannot receive an array with `null` values', 'pop-component-model'),
                $inputName
            );
        }
        if (
            $inputIsArrayType
            && !$inputIsArrayOfArraysType
            && array_filter(
                $inputValue,
                fn ($arrayItem) => is_array($arrayItem)
            )
        ) {
            return sprintf(
                $this->getTranslationAPI()->__('Argument \'%s\' cannot receive an array containing arrays as elements', 'pop-component-model'),
                $inputName,
                json_encode($inputValue)
            );
        }
        if (
            $inputIsArrayOfArraysType
            && is_array($inputValue)
            && array_filter(
                $inputValue,
                // `null` could be accepted as an array! (Validation against null comes next)
                fn ($arrayItem) => !is_array($arrayItem) && $arrayItem !== null
            )
        ) {
            return sprintf(
                $this->getTranslationAPI()->__('Argument \'%s\' expects an array of arrays, but value \'%s\' was provided', 'pop-component-model'),
                $inputName,
                json_encode($inputValue)
            );
        }
        if (
            $inputIsNonNullArrayOfArraysItemsType
            && is_array($inputValue)
            && array_filter(
                $inputValue,
                fn (?array $arrayItem) => $arrayItem === null ? false : array_filter(
                    $arrayItem,
                    fn ($arrayItemItem) => $arrayItemItem === null
                ) !== [],
            )
        ) {
            return sprintf(
                $this->getTranslationAPI()->__('Argument \'%s\' cannot receive an array of arrays with `null` values', 'pop-component-model'),
                $inputName
            );
        }
        return null;
    }

    /**
     * Coerce the input value, corresponding to the array type
     * defined by the modifiers.
     */
    public function coerceInputValue(
        InputTypeResolverInterface $inputTypeResolver,
        mixed $inputValue,
        bool $inputIsArrayType,
        bool $inputIsArrayOfArraysType
    ): mixed {
        if ($inputValue === null) {
            return null;
        }
        if ($inputIsArrayOfArraysType) {
            // If the value is an array of arrays, then cast each subelement to the item type
            return array_map(
                // If it contains a null value, return it as is
                fn (?array $arrayArgValueElem) => $arrayArgValueElem === null ? null : array_map(
                    fn (mixed $arrayOfArraysArgValueElem) => $arrayOfArraysArgValueElem === null ? null : $inputTypeResolver->coerceValue($arrayOfArraysArgValueElem),
                    $arrayArgValueElem
                ),
                $inputValue
            );
        }
        if ($inputIsArrayType) {
            // If the value is an array, then cast each element to the item type
            return array_map(
                fn (mixed $arrayArgValueElem) => $arrayArgValueElem === null ? null : $inputTypeResolver->coerceValue($arrayArgValueElem),
                $inputValue
            );
        }
        // Otherwise, simply cast the given value directly
        return $inputTypeResolver->coerceValue($inputValue);
    }

    /**
     * Extract the Errors produced when coercing the input values
     *
     * @return Error[] Errors from coercing the input value
     */
    public function extractErrorsFromCoercedInputValue(
        mixed $inputValue,
        bool $inputIsArrayType,
        bool $inputIsArrayOfArraysType
    ): array {
        if ($inputIsArrayOfArraysType) {
            return GeneralUtils::arrayFlatten(array_filter(
                $inputValue ?? [],
                fn (?array $arrayArgValueElem) => $arrayArgValueElem === null ? false : array_filter(
                    $arrayArgValueElem,
                    fn (mixed $arrayOfArraysArgValueElem) => GeneralUtils::isError($arrayOfArraysArgValueElem)
                )
            ));
        }
        if ($inputIsArrayType) {
            return array_values(array_filter(
                $inputValue ?? [],
                fn (mixed $arrayArgValueElem) => GeneralUtils::isError($arrayArgValueElem)
            ));
        }
        if (GeneralUtils::isError($inputValue)) {
            return [
                $inputValue,
            ];
        }
        return [];
    }

    /**
     * If applicable, get the deprecation messages for the input value
     *
     * @return string[]
     */
    public function getInputValueDeprecationMessages(
        DeprecatableInputTypeResolverInterface $deprecatableInputTypeResolver,
        mixed $inputValue,
        bool $inputIsArrayType,
        bool $inputIsArrayOfArraysType
    ): array {
        if ($inputValue === null) {
            return [];
        }
        $inputValueDeprecations = [];
        if ($inputIsArrayOfArraysType) {
            // Execute against an array of arrays of values
            foreach ($inputValue as $arrayArgValueElem) {
                if ($arrayArgValueElem === null) {
                    continue;
                }
                foreach ($arrayArgValueElem as $arrayOfArraysArgValueElem) {
                    if ($arrayOfArraysArgValueElem === null) {
                        continue;
                    }
                    $inputValueDeprecations = array_merge(
                        $inputValueDeprecations,
                        $deprecatableInputTypeResolver->getInputValueDeprecationMessages($arrayOfArraysArgValueElem)
                    );
                }
            }
        } elseif ($inputIsArrayType) {
            // Execute against an array of values
            foreach ($inputValue as $arrayArgValueElem) {
                if ($arrayArgValueElem === null) {
                    continue;
                }
                $inputValueDeprecations = array_merge(
                    $inputValueDeprecations,
                    $deprecatableInputTypeResolver->getInputValueDeprecationMessages($arrayArgValueElem)
                );
            }
        } else {
            // Execute against the single value
            $inputValueDeprecations = $deprecatableInputTypeResolver->getInputValueDeprecationMessages($inputValue);
        }
        return array_unique($inputValueDeprecations);
    }
}
