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
    public final const EQUALS = 'EQUALS'; // '='
    public final const NOT_EQUALS = 'NOT_EQUALS'; // '!='
    public final const GREATER_THAN = 'GREATER_THAN'; // '>'
    public final const GREATER_THAN_OR_EQUAL = 'GREATER_THAN_OR_EQUAL'; // '>='
    public final const LESS_THAN = 'LESS_THAN'; // '<'
    public final const LESS_THAN_OR_EQUAL = 'LESS_THAN_OR_EQUAL'; // '<='
    public final const LIKE = 'LIKE'; // 'LIKE'
    public final const NOT_LIKE = 'NOT_LIKE'; // 'NOT LIKE'
    public final const IN = 'IN'; // 'IN'
    public final const NOT_IN = 'NOT_IN'; // 'NOT IN'
    public final const BETWEEN = 'BETWEEN'; // 'BETWEEN'
    public final const NOT_BETWEEN = 'NOT_BETWEEN'; // 'NOT BETWEEN'
    public final const EXISTS = 'EXISTS'; // 'EXISTS'
    public final const NOT_EXISTS = 'NOT_EXISTS'; // 'NOT EXISTS'
    public final const REGEXP = 'REGEXP'; // 'REGEXP'
    public final const NOT_REGEXP = 'NOT_REGEXP'; // 'NOT REGEXP'
    public final const RLIKE = 'RLIKE'; // 'RLIKE'
}
