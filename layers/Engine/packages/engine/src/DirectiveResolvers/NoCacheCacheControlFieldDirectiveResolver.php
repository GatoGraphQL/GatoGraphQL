<?php

declare(strict_types=1);

namespace PoP\Engine\DirectiveResolvers;

use PoP\CacheControl\DirectiveResolvers\AbstractCacheControlFieldDirectiveResolver;

class NoCacheCacheControlFieldDirectiveResolver extends AbstractCacheControlFieldDirectiveResolver
{
    /**
     * @return string[]
     */
    public function getFieldNamesToApplyTo(): array
    {
        return [
            'var',
            'context',
            'time',
        ];
    }

    public function getMaxAge(): ?int
    {
        // Do not cache
        return 0;
    }
}
