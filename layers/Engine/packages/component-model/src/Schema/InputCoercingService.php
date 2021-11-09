<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Schema;

use PoP\ComponentModel\ComponentConfiguration;

class InputCoercingService implements InputCoercingServiceInterface
{
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
        bool $inputIsArrayOfArraysType,
        bool $inputIsArrayType,
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
}
