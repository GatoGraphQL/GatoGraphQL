<?php

declare(strict_types=1);

namespace PoPSchema\SchemaCommons\TypeResolvers\ScalarType;

/**
 * This type is similar to Enum in that only values from a fixed-set
 * can be provided, but it is validated as a String.
 *
 * This is because Enum values have several constraints:
 *
 * - Can't have spaces
 * - Can't have special chars, such as `-`
 *
 * For whenever the option values may not satisfy these constraints,
 * this type can be used instead
 */
interface EnumStringScalarTypeResolverInterface
{
    /**
     * @return string[]
     */
    public function getPossibleValues(): array;

    /**
     * Consolidation of the possible values. Call this function to read the data
     * instead of the individual functions, since it applies hooks to override/extend.
     *
     * @return string[]
     */
    public function getConsolidatedPossibleValues(): array;
}
