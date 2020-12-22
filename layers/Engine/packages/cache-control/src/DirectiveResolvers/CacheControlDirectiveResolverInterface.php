<?php

declare(strict_types=1);

namespace PoP\CacheControl\DirectiveResolvers;

interface CacheControlDirectiveResolverInterface
{
    /**
     * Resolves the directive. It differs from `resolveDirective` in that it can be executed without params
     *
     * @return void
     */
    public function resolveCacheControlDirective(): void;
    /**
     * Cache control max age for this directive, possibly applied to certain fields
     *
     * @return integer|null
     */
    public function getMaxAge(): ?int;
}
