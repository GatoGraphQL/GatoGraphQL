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
    public final const NUMERIC = 'NUMERIC';
    public final const BINARY = 'BINARY';
    public final const CHAR = 'CHAR';
    public final const DATE = 'DATE';
    public final const DATETIME = 'DATETIME';
    public final const DECIMAL = 'DECIMAL';
    public final const SIGNED = 'SIGNED';
    public final const TIME = 'TIME';
    public final const UNSIGNED = 'UNSIGNED';
}
