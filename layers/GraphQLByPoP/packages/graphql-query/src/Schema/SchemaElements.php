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
    /**
     * Name for the directive arg to indicate which directives
     * are being nested, by indicating their relative position
     * to the meta-directive.
     *
     * Eg: @foreach(affect: [1]) @translate
     */
    const DIRECTIVE_PARAM_AFFECT_DIRECTIVES_UNDER_POS = 'affect';
}
