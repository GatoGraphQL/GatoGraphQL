<?php

declare(strict_types=1);

namespace PoP\Engine\ConditionalOnEnvironment\Guzzle\SchemaServices\DirectiveResolvers;

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
