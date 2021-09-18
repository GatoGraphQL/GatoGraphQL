<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ConditionalOnContext\CacheControl\Overrides\Managers;

use PoP\CacheControl\Managers\CacheControlEngine as UpstreamCacheControlEngine;

class CacheControlEngine extends UpstreamCacheControlEngine
{
    /**
     * Disable caching when previewing CPTs
     */
    protected function isCachingEnabled(): bool
    {
        return !\is_preview() && parent::isCachingEnabled();
    }
}
