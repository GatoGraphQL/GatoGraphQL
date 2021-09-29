<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Cache;

use PoP\Root\Environment;

/**
 * Inject configuration to the cache
 *
 * @author Leonardo Losoviz <leo@getpop.org>
 */
class CacheConfigurationManager implements CacheConfigurationManagerInterface
{
    /**
     * Make the cache folder obsolete when increasing the application version,
     * otherwise the cache uses a stale configuration
     *
     * @see https://symfony.com/doc/current/components/cache/adapters/filesystem_adapter.html
     */
    public function getNamespace(): string
    {
        // (Needed for development) Don't share cache among plugin versions
        if ($version = Environment::getApplicationVersion()) {
            return '_v' . $version;
        }
        return '';
    }

    /**
     * Inject to the FilesystemAdapter:
     * The directory where to store the cache. If null, it uses the default /tmp system folder
     *
     * @see https://symfony.com/doc/current/components/cache/adapters/filesystem_adapter.html
     */
    public function getDirectory(): ?string
    {
        return null;
    }
}
