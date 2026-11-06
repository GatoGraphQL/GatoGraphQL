<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Schema;

use PoP\ComponentModel\Enums\OutputValueNullabilityViolations;

/**
 * Single place defining what "Non-Null" means for a field's
 * output value, as indicated by its type modifiers
 * (`String!`, `[String!]`, `[[String!]]`).
 *
 * @see https://spec.graphql.org/draft/#sec-Handling-Execution-Errors
 */
interface OutputValueNullabilityServiceInterface
{
    public function isNonNullable(int $fieldTypeModifiers): bool;

    public function hasNonNullableArrayItems(int $fieldTypeModifiers): bool;

    public function hasNonNullableArrayOfArraysItems(int $fieldTypeModifiers): bool;

    /**
     * Indicate if the value violates the nullability defined
     * by the type modifiers, or `null` if it does not.
     */
    public function getNullabilityViolation(
        int $fieldTypeModifiers,
        mixed $value,
    ): ?OutputValueNullabilityViolations;
}
