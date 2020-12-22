<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLQuery\Schema;

class QuerySymbols
{
    /**
     * Names for variables supporting the @export directive must start with this token
     */
    const VARIABLE_AS_EXPRESSION_NAME_PREFIX = '_';
}
