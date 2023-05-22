<?php

declare(strict_types=1);

namespace PoP\Engine\ConditionalOnModule\CacheControl\DirectiveResolvers;

use PoP\CacheControl\DirectiveResolvers\AbstractCacheControlFieldDirectiveResolver;

class NoCacheCacheControlFieldDirectiveResolver extends AbstractCacheControlFieldDirectiveResolver
{
    /**
     * @return string[]
     */
    public function getFieldNamesToApplyTo(): array
    {
        return [
            '_appState',
            '_appStateKeys',
            '_appStateValue',
        ];
    }

    public function getMaxAge(): ?int
    {
        // Do not cache
        return 0;
    }
}
