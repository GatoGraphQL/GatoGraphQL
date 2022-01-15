<?php

declare(strict_types=1);

namespace PoPSchema\ExternalEndpoints\DirectiveResolvers;

use PoP\CacheControl\DirectiveResolvers\AbstractCacheControlDirectiveResolver;

class NoCacheCacheControlDirectiveResolver extends AbstractCacheControlDirectiveResolver
{
    public function getFieldNamesToApplyTo(): array
    {
        return [
            'getJSON',
            'getAsyncJSON',
        ];
    }

    public function getMaxAge(): ?int
    {
        // Do not cache
        return 0;
    }
}
