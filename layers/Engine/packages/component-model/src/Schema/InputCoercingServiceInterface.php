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
        bool $inputIsArrayOfArraysType,
        bool $inputIsArrayType,
    ): mixed;
}
