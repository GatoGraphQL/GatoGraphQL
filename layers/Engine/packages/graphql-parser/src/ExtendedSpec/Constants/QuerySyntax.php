<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\ExtendedSpec\Constants;

class QuerySyntax
{
    const OBJECT_RESOLVED_FIELD_VALUE_REFERENCE_PREFIX = '__';

    /**
     * Helper directives to indicate which directives are
     * affected by a meta directive, as an alternative to
     * argument `affectDirectivesUnderPos`.
     *
     * Eg: @underEachArrayItem @start @strUpperCase @strTrim @end
     */
    const META_DIRECTIVE_BLOCK_START_DIRECTIVE_NAME = 'start';
    const META_DIRECTIVE_BLOCK_END_DIRECTIVE_NAME = 'end';
}
