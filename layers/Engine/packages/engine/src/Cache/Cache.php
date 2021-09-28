<?php

declare(strict_types=1);

namespace PoP\Engine\Cache;

use Symfony\Contracts\Service\Attribute\Required;
use PoP\Hooks\HooksAPIInterface;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Cache\CacheItemInterface;
use PoP\ComponentModel\ModelInstance\ModelInstanceInterface;
use PoP\ComponentModel\Cache\Cache as UpstreamCache;

class Cache extends UpstreamCache
{
    protected HooksAPIInterface $hooksAPI;

    #[Required]
    public function autowireEngineCache(
        HooksAPIInterface $hooksAPI,
    ) {
        $this->hooksAPI = $hooksAPI;

        // When a plugin is activated/deactivated, ANY plugin, delete the corresponding cached files
        // This is particularly important for the MEMORY, since we can't set by constants to not use it
        $this->hooksAPI->addAction(
            'popcms:componentInstalledOrUninstalled',
            function () {
                $this->cacheItemPool->clear();
            }
        );

        // Save all deferred cacheItems
        $this->hooksAPI->addAction(
            'popcms:shutdown',
            function () {
                $this->cacheItemPool->commit();
            }
        );
    }

    /**
     * Override to save as deferred, on hook "popcms:shutdown"
     */
    protected function saveCache(CacheItemInterface $cacheItem): void
    {
        $this->cacheItemPool->saveDeferred($cacheItem);
    }
}
