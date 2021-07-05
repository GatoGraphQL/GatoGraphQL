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
    public const IS_NON_NULLABLE_ITEMS_IN_ARRAY = 4;
    public const IS_ARRAY_OF_ARRAYS = 8;
    public const IS_NON_NULLABLE_ITEMS_IN_ARRAY_OF_ARRAYS = 16;
}
