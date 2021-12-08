<?php

declare(strict_types=1);

namespace PoPWPSchema\Meta\Constants;

/**
 * Meta query operators to compare the value against.
 *
 * @see https://developer.wordpress.org/reference/classes/wp_meta_query/
 */
class MetaQueryCompareByOperators
{
    public const EQUALS = 'EQUALS'; // '='
    public const NOT_EQUALS = 'NOT_EQUALS'; // '!='
    public const GREATER_THAN = 'GREATER_THAN'; // '>'
    public const GREATER_THAN_OR_EQUAL = 'GREATER_THAN_OR_EQUAL'; // '>='
    public const LESS_THAN = 'LESS_THAN'; // '<'
    public const LESS_THAN_OR_EQUAL = 'LESS_THAN_OR_EQUAL'; // '<='
    public const LIKE = 'LIKE'; // 'LIKE'
    public const NOT_LIKE = 'NOT_LIKE'; // 'NOT LIKE'
    public const IN = 'IN'; // 'IN'
    public const NOT_IN = 'NOT_IN'; // 'NOT IN'
    public const BETWEEN = 'BETWEEN'; // 'BETWEEN'
    public const NOT_BETWEEN = 'NOT_BETWEEN'; // 'NOT BETWEEN'
    public const EXISTS = 'EXISTS'; // 'EXISTS'
    public const NOT_EXISTS = 'NOT_EXISTS'; // 'NOT EXISTS'
    public const REGEXP = 'REGEXP'; // 'REGEXP'
    public const NOT_REGEXP = 'NOT_REGEXP'; // 'NOT REGEXP'
    public const RLIKE = 'RLIKE'; // 'RLIKE'
}
