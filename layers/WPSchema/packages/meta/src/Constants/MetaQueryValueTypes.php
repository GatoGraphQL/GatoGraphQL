<?php

declare(strict_types=1);

namespace PoPWPSchema\Meta\Constants;

/**
 * Meta query "type", as explained here:
 *
 * @see https://developer.wordpress.org/reference/classes/wp_meta_query/
 */
class MetaQueryValueTypes
{
    public const NUMERIC = 'NUMERIC';
    public const BINARY = 'BINARY';
    public const CHAR = 'CHAR';
    public const DATE = 'DATE';
    public const DATETIME = 'DATETIME';
    public const DECIMAL = 'DECIMAL';
    public const SIGNED = 'SIGNED';
    public const TIME = 'TIME';
    public const UNSIGNED = 'UNSIGNED';
}
