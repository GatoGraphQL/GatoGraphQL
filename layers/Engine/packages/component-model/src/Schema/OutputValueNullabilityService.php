<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Schema;

use PoP\ComponentModel\Enums\OutputValueNullabilityViolations;
use PoP\Root\Services\AbstractBasicService;

use function in_array;
use function is_array;

class OutputValueNullabilityService extends AbstractBasicService implements OutputValueNullabilityServiceInterface
{
    public function isNonNullable(int $fieldTypeModifiers): bool
    {
        return ($fieldTypeModifiers & SchemaTypeModifiers::NON_NULLABLE) === SchemaTypeModifiers::NON_NULLABLE;
    }

    /**
     * Same logic as when producing the schema definition:
     * the items modifier applies only if the field is a list.
     *
     * @see layers/Engine/packages/component-model/src/Resolvers/TypeSchemaDefinitionResolverTrait.php
     */
    public function hasNonNullableArrayItems(int $fieldTypeModifiers): bool
    {
        if (!$this->isArray($fieldTypeModifiers)) {
            return false;
        }
        return ($fieldTypeModifiers & SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY) === SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY;
    }

    public function hasNonNullableArrayOfArraysItems(int $fieldTypeModifiers): bool
    {
        if (($fieldTypeModifiers & SchemaTypeModifiers::IS_ARRAY_OF_ARRAYS) !== SchemaTypeModifiers::IS_ARRAY_OF_ARRAYS) {
            return false;
        }
        return ($fieldTypeModifiers & SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY_OF_ARRAYS) === SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY_OF_ARRAYS;
    }

    protected function isArray(int $fieldTypeModifiers): bool
    {
        return ($fieldTypeModifiers & SchemaTypeModifiers::IS_ARRAY) === SchemaTypeModifiers::IS_ARRAY
            || ($fieldTypeModifiers & SchemaTypeModifiers::IS_ARRAY_OF_ARRAYS) === SchemaTypeModifiers::IS_ARRAY_OF_ARRAYS;
    }

    public function getNullabilityViolation(
        int $fieldTypeModifiers,
        mixed $value,
    ): ?OutputValueNullabilityViolations {
        if ($value === null) {
            return $this->isNonNullable($fieldTypeModifiers)
                ? OutputValueNullabilityViolations::NULL_VALUE
                : null;
        }
        if (!is_array($value)) {
            return null;
        }
        if (
            $this->hasNonNullableArrayItems($fieldTypeModifiers)
            && in_array(null, $value, true)
        ) {
            return OutputValueNullabilityViolations::NULL_ARRAY_ITEM;
        }
        if ($this->hasNonNullableArrayOfArraysItems($fieldTypeModifiers)) {
            foreach ($value as $arrayItem) {
                if (
                    is_array($arrayItem)
                    && in_array(null, $arrayItem, true)
                ) {
                    return OutputValueNullabilityViolations::NULL_ARRAY_OF_ARRAYS_ITEM;
                }
            }
        }
        return null;
    }
}
