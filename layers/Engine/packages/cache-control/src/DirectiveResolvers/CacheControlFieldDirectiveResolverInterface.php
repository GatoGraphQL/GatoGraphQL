<?php

declare(strict_types=1);

namespace PoP\CacheControl\DirectiveResolvers;

interface CacheControlFieldDirectiveResolverInterface
{
    /**
     * Resolves the directive. It differs from `resolveDirective` in that it can be executed without params
     */
    public function resolveCacheControlDirective(): void;
    /**
     * Cache control max age for this directive, possibly applied to certain fields
     */
    public function getMaxAge(): ?int;
}
