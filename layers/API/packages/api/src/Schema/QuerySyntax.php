<?php

declare(strict_types=1);

namespace PoPAPI\API\Schema;

class QuerySyntax
{
    /**
     * Support resolving other fields from the same type in field/directive arguments:
     * Replace posts(searchfor: "{{title}}") with posts(searchfor: "sprintf(%s, [title()])")
     */
    const SYMBOL_EMBEDDABLE_FIELD_PREFIX = '{{';
    const SYMBOL_EMBEDDABLE_FIELD_SUFFIX = '}}';
}
