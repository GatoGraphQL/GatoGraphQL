<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Schema;

use PoP\ComponentModel\ErrorHandling\Error;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;

interface InputCoercingServiceInterface
{
    /**
     * Support passing a single value where a list is expected:
     * `{ posts(ids: 1) }` means `{ posts(ids: [1]) }`
     *
     * Defined in the GraphQL spec.
     *
     * @see https://spec.graphql.org/draft/#sec-List.Input-Coercion
     * 
     * @return mixed The provided value as is, converted to array, or converted to array of arrays
     */
    public function maybeCoerceInputFromSingleValueToList(
        mixed $inputValue,
        bool $inputIsArrayType,
        bool $inputIsArrayOfArraysType,
    ): mixed;

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
    ): ?string;

    /**
     * Coerce the input value, corresponding to the array type
     * defined by the modifiers.
     * 
     * In case of errors, these are added to entry $inputValueErrors
     * 
     * @param Error[] $inputValueErrors Errors from coercing the input value
     */
    public function coerceInputValue(
        InputTypeResolverInterface $inputTypeResolver,
        mixed $inputValue,
        bool $inputIsArrayType,
        bool $inputIsArrayOfArraysType,
        array &$inputValueErrors
    ): mixed;
}
