<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserState\DirectiveResolvers;

use PoP\CacheControl\DirectiveResolvers\AbstractCacheControlFieldDirectiveResolver;

class NoCacheCacheControlFieldDirectiveResolver extends AbstractCacheControlFieldDirectiveResolver
{
    /**
     * @return string[]
     */
    public function getFieldNamesToApplyTo(): array
    {
        return [
            'me',
            'isUserLoggedIn',
        ];
    }

    public function getMaxAge(): ?int
    {
        // Do not cache
        return 0;
    }
}
