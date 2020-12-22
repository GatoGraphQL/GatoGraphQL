<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLQuery\Schema;

class SchemaElements
{
    /**
     * Names for the directive arg under which to nest directives
     * Eg: @foreach @translate(from:"en", to:"es", nestedUnder:-1)
     */
    const DIRECTIVE_PARAM_NESTED_UNDER = 'nestedUnder';
}
