<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Cache;

use Symfony\Contracts\Service\Attribute\Required;
use DateInterval;
use PoP\ComponentModel\ModelInstance\ModelInstanceInterface;
use Psr\Cache\CacheItemInterface;
use Psr\Cache\CacheItemPoolInterface;

class Cache implements CacheInterface
{
    use ReplaceCurrentExecutionDataWithPlaceholdersTrait;

    protected ModelInstanceInterface $modelInstance;

    public function __construct(
        protected CacheItemPoolInterface $cacheItemPool,
    ) {
    }

    #[Required]
    public function autowireCache(ModelInstanceInterface $modelInstance): void
    {
        $this->modelInstance = $modelInstance;
    }

    protected function getKey(string $id, string $type)
    {
        return $type . '.' . $id;
    }

    protected function getCacheItem(string $id, string $type): CacheItemInterface
    {
        return $this->cacheItemPool->getItem($this->getKey($id, $type));
    }

    public function hasCache(string $id, string $type): bool
    {
        $cacheItem = $this->getCacheItem($id, $type);
        return $cacheItem->isHit();
    }

    /**
     * @return boolean True if the item was successfully removed. False if there was an error.
     */
    public function deleteCache(string $id, string $type): bool
    {
        return $this->cacheItemPool->deleteItem($this->getKey($id, $type));
    }

    public function clear(): void
    {
        $this->cacheItemPool->clear();
    }


    /**
     * If the item is not cached, it will return `null`
     * @see https://www.php-fig.org/psr/psr-6/
     */
    public function getCache(string $id, string $type): mixed
    {
        $cacheItem = $this->getCacheItem($id, $type);
        return $cacheItem->get();
    }

    public function getComponentModelCache(string $id, string $type): mixed
    {
        $content = $this->getCache($id, $type);

        // Inject the current request data in place of the placeholders (pun not intended!)
        return $this->replacePlaceholdersWithCurrentExecutionData($content);
    }

    /**
     * Store the cache
     *
     * @param string $id key under which to store the cache
     * @param string $type the type of the cache, used to distinguish groups of caches
     * @param mixed $content the value to cache
     * @param int|DateInterval|null $time time after which the cache expires, in seconds
     */
    public function storeCache(string $id, string $type, mixed $content, int|DateInterval|null $time = null): void
    {
        $cacheItem = $this->getCacheItem($id, $type);
        $cacheItem->set($content);
        $cacheItem->expiresAfter($time);
        $this->saveCache($cacheItem);
    }

    /**
     * Store the cache by component model
     */
    public function storeComponentModelCache(string $id, string $type, mixed $content, int|DateInterval|null $time = null): void
    {
        // Before saving the cache, replace the data specific to this execution with generic placeholders
        $content = $this->replaceCurrentExecutionDataWithPlaceholders($content);
        $this->storeCache($id, $type, $content, $time);
    }

    /**
     * Save immediately. Can override to save as deferred
     */
    protected function saveCache(CacheItemInterface $cacheItem): void
    {
        $this->cacheItemPool->save($cacheItem);
    }

    public function getCacheByModelInstance(string $type): mixed
    {
        return $this->getComponentModelCache($this->modelInstance->getModelInstanceId(), $type);
    }

    public function storeCacheByModelInstance(string $type, mixed $content): void
    {
        $this->storeCache($this->modelInstance->getModelInstanceId(), $type, $content);
    }
}
