<?php

declare(strict_types=1);

namespace PoP\TraceTools\DirectiveResolvers;

/**
 * Common functionality among all Trace directives
 */
trait TraceDirectiveResolverTrait
{
    /**
     * Operations can be executed only once
     *
     * @return boolean
     */
    public function isRepeatable(): bool
    {
        return false;
    }
}
