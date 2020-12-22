<?php

declare(strict_types=1);

namespace PoPSchema\UserState\DirectiveResolvers;

use PoP\CacheControl\DirectiveResolvers\AbstractCacheControlDirectiveResolver;

class NoCacheCacheControlDirectiveResolver extends AbstractCacheControlDirectiveResolver
{
    public static function getFieldNamesToApplyTo(): array
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
