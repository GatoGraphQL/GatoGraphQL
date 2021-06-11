<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Schema;

/**
 * Values to extract using a bitwise operation
 * 
 * @see https://www.php.net/manual/en/language.operators.bitwise.php#91291
 */
class SchemaTypeModifiers
{
    public const NON_NULLABLE = 1;
    public const IS_ARRAY = 2;

    /**
     * This value will not be used with GraphQL, but can be used by PoP.
     * 
     * While GraphQL has a strong type system, PoP takes a more lenient approach,
     * enabling fields to maybe be an array, maybe not.
     * 
     * Eg: `echo(value: ...)` will print back whatever provided,
     * whether `String` or `[String]`
     */
    public const MAY_BE_ARRAY = 4;
}
