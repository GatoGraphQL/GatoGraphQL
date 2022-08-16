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
    public final const NONE = 0;
    public final const NON_NULLABLE = 1;
    public final const IS_ARRAY = 2;
    public final const IS_NON_NULLABLE_ITEMS_IN_ARRAY = 4;
    public final const IS_ARRAY_OF_ARRAYS = 8;
    public final const IS_NON_NULLABLE_ITEMS_IN_ARRAY_OF_ARRAYS = 16;
    /**
     * The input is mandatory and also non-null
     */
    public final const MANDATORY = 32;
    /**
     * The input is mandatory but can be null.
     *
     * Eg: field arg `value` in field `echo` is mandatory,
     * but it can also echo back a `null` value:
     *
     *   ```
     *   {
     *     echo(value: null)
     *   }
     *   ```
     */
    public final const MANDATORY_BUT_NULLABLE = 64;
}
