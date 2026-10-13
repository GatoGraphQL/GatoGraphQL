<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Enums;

enum OutputValueNullabilityViolations: string
{
    /**
     * A Non-Null field (eg: `String!`) has value `null`
     */
    case NULL_VALUE = 'null-value';
    /**
     * A list with Non-Null items (eg: `[String!]`) has a `null` item
     */
    case NULL_ARRAY_ITEM = 'null-array-item';
    /**
     * A list of lists with Non-Null items (eg: `[[String!]]`)
     * has a `null` item in some inner list
     */
    case NULL_ARRAY_OF_ARRAYS_ITEM = 'null-array-of-arrays-item';
}
