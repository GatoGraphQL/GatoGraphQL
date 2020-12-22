<?php

declare(strict_types=1);

namespace PoP\Engine\Cache;

use PoP\Hooks\HooksAPIInterface;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Cache\CacheItemInterface;
use PoP\ComponentModel\ModelInstance\ModelInstanceInterface;

class Cache extends \PoP\ComponentModel\Cache\Cache
{
    protected HooksAPIInterface $hooksAPI;

    public function __construct(
        CacheItemPoolInterface $cacheItemPool,
        HooksAPIInterface $hooksAPI,
        ModelInstanceInterface $modelInstance
    ) {
        parent::__construct($cacheItemPool, $modelInstance);
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
     *
     * @param CacheItemInterface $cacheItem
     * @return void
     */
    protected function saveCache(CacheItemInterface $cacheItem)
    {
        $this->cacheItemPool->saveDeferred($cacheItem);
    }

    protected function getCacheReplacements()
    {
        return [
            POP_CONSTANT_UNIQUE_ID => POP_CACHEPLACEHOLDER_UNIQUE_ID,
            POP_CONSTANT_CURRENTTIMESTAMP => POP_CACHEPLACEHOLDER_CURRENTTIMESTAMP,
            POP_CONSTANT_RAND => POP_CACHEPLACEHOLDER_RAND,
            POP_CONSTANT_TIME => POP_CACHEPLACEHOLDER_TIME,
        ];
    }
}
