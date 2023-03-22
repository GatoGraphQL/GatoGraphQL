<?php

declare(strict_types=1);

namespace PoP\CacheControl\DirectiveResolvers;

interface CacheControlFieldDirectiveResolverInterface
{
    /**
     * Cache control max age for this directive, possibly applied to certain fields
     */
    public function getMaxAge(): ?int;
}
