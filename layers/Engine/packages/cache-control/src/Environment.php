<?php

declare(strict_types=1);

namespace PoP\CacheControl;

class Environment
{
    public final const DEFAULT_CACHE_CONTROL_MAX_AGE = 'DEFAULT_CACHE_CONTROL_MAX_AGE';

    public static function disableCacheControl(): bool
    {
        return getenv('DISABLE_CACHE_CONTROL') !== false ? strtolower(getenv('DISABLE_CACHE_CONTROL')) === "true" : false;
    }
}
