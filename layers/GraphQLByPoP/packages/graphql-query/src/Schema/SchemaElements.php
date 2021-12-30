<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLQuery\Schema;

class SchemaElements
{
    /**
     * Name for the directive arg to indicate which directives
     * are being nested, by indicating their relative position
     * to the meta-directive.
     *
     * Eg: @foreach(affectDirectivesUnderPos: [1]) @translate
     */
    const DIRECTIVE_PARAM_AFFECT_DIRECTIVES_UNDER_POS = 'affectDirectivesUnderPos';
}
