<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Cache;

use DateInterval;

interface CacheInterface
{
    public function hasCache(string $id, string $type): bool;
    /**
     * @return boolean True if the item was successfully removed. False if there was an error.
     */
    public function deleteCache(string $id, string $type): bool;
    /**
     * Remove all entries in the cache
     */
    public function clear(): void;
    /**
     * Commit entries in the pool
     */
    public function commit(): void;
    public function getCache(string $id, string $type): mixed;
    public function getComponentModelCache(string $id, string $type): mixed;

    /**
     * Store the cache
     *
     * @param string $id key under which to store the cache
     * @param string $type the type of the cache, used to distinguish groups of caches
     * @param mixed $content the value to cache
     * @param int|DateInterval|null $time time after which the cache expires, in seconds
     */
    public function storeCache(string $id, string $type, mixed $content, int|DateInterval|null $time = null): void;
    public function storeComponentModelCache(string $id, string $type, mixed $content, int|DateInterval|null $time = null): void;
    public function getCacheByModelInstance(string $type): mixed;
    public function storeCacheByModelInstance(string $type, mixed $content): void;
}
