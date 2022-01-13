<?php

declare(strict_types=1);

namespace PoP\Engine\Hooks;

use PoP\ComponentModel\Cache\PersistentCacheInterface;
use PoP\ComponentModel\Cache\TransientCacheInterface;
use PoP\Root\Hooks\AbstractHookSet;

class CacheHookSet extends AbstractHookSet
{
    private ?PersistentCacheInterface $persistentCache = null;
    private ?TransientCacheInterface $transientCache = null;

    final public function setPersistentCache(PersistentCacheInterface $persistentCache): void
    {
        $this->persistentCache = $persistentCache;
    }
    final protected function getPersistentCache(): PersistentCacheInterface
    {
        return $this->persistentCache ??= $this->instanceManager->getInstance(PersistentCacheInterface::class);
    }
    final public function setTransientCache(TransientCacheInterface $transientCache): void
    {
        $this->transientCache = $transientCache;
    }
    final protected function getTransientCache(): TransientCacheInterface
    {
        return $this->transientCache ??= $this->instanceManager->getInstance(TransientCacheInterface::class);
    }

    protected function init(): void
    {
        // When a plugin is activated/deactivated, ANY plugin, delete the corresponding cached files
        // This is particularly important for the MEMORY, since we can't set by constants to not use it
        $this->getHooksAPI()->addAction(
            'popcms:componentInstalledOrUninstalled',
            [$this, 'clear']
        );

        // Save all deferred cacheItems
        $this->getHooksAPI()->addAction(
            'popcms:shutdown',
            [$this, 'commit']
        );
    }

    public function clear(): void
    {
        $this->getPersistentCache()->clear();
        $this->getTransientCache()->clear();
    }

    public function commit(): void
    {
        $this->getPersistentCache()->commit();
        $this->getTransientCache()->commit();
    }
}
