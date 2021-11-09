<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Schema;

use PoP\ComponentModel\ComponentConfiguration;
use PoP\ComponentModel\Services\BasicServiceTrait;

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
    public function maybeCoerceInputFromSingleValueToList(
        mixed $inputValue,
        bool $inputIsArrayType,
        bool $inputIsArrayOfArraysType,
    ): mixed {
        if (
            is_array($inputValue)
            || !ComponentConfiguration::coerceInputFromSingleValueToList()
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
}
