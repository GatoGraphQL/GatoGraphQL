<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Schema;

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
}
