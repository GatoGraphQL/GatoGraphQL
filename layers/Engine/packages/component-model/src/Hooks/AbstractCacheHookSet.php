<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Hooks;

use PoP\ComponentModel\Cache\PersistentCacheInterface;
use PoP\ComponentModel\Cache\TransientCacheInterface;
use PoP\Root\App;
use PoP\Root\Hooks\AbstractHookSet;

abstract class AbstractCacheHookSet extends AbstractHookSet
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
        /**
         * When a plugin/module/component/etc is activated/deactivated,
         * delete the cached files from this application.
         *
         * For instance, for WordPress, these hooks must be provided:
         *
         * - 'activate_plugin'
         * - 'deactivate_plugin'
         */
        foreach ($this->getClearHookNames() as $hookName) {
            App::addAction(
                $hookName,
                $this->clear(...)
            );
        }

        /**
         * Save all deferred cacheItems.
         *
         * For instance, for WordPress, this hook must be provided:
         *
         * - 'shutdown'
         */
        App::addAction(
            $this->getCommitHookName(),
            $this->commit(...)
        );
    }

    /**
     * @return string[]
     */
    abstract protected function getClearHookNames(): array;
    abstract protected function getCommitHookName(): string;

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
