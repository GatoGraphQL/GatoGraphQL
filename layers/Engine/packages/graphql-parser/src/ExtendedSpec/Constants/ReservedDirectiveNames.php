<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\ExtendedSpec\Constants;

class ReservedDirectiveNames
{
    /**
     * Helper directives to indicate which directives are
     * affected by a meta directive, as an alternative to
     * argument `affectDirectivesUnderPos`.
     *
     * Eg: @underEachArrayItem @start @strUpperCase @strTrim @end
     */
    const META_DIRECTIVE_BLOCK_START = 'start';
    const META_DIRECTIVE_BLOCK_END = 'end';
}
