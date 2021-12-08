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
    public const EQ = '=';
    public const NOT_EQ = '!=';
    public const GT = '>';
    public const GET = '>=';
    public const LT = '<';
    public const LET = '<=';
    public const LIKE = 'LIKE';
    public const NOT_LIKE = 'NOT LIKE';
    public const IN = 'IN';
    public const NOT_IN = 'NOT IN';
    public const BETWEEN = 'BETWEEN';
    public const NOT_BETWEEN = 'NOT BETWEEN';
    public const EXISTS = 'EXISTS';
    public const NOT_EXISTS = 'NOT EXISTS';
    public const REGEXP = 'REGEXP';
    public const NOT_REGEXP = 'NOT REGEXP';
    public const RLIKE = 'RLIKE';
}
