<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Cache;

interface CacheInterface
{
    public function hasCache($id, $type);
    /**
     * If the item is not cached, it will return `null`
     * @see https://www.php-fig.org/psr/psr-6/
     *
     * @param [type] $id
     * @param [type] $type
     * @return mixed
     */
    public function deleteCache($id, $type): void;
    /**
     * Remove all entries in the cache
     */
    public function clear(): void;
    public function getCache($id, $type);
    public function getComponentModelCache($id, $type);

    /**
     * Store the cache
     *
     * @param [type] $id key under which to store the cache
     * @param [type] $type the type of the cache, used to distinguish groups of caches
     * @param [type] $content the value to cache
     * @param [type] $time time after which the cache expires, in seconds
     * @return void
     */
    public function storeCache($id, $type, $content, $time = null);
    public function storeComponentModelCache($id, $type, $content, $time = null);
    public function getCacheByModelInstance($type);
    public function storeCacheByModelInstance($type, $content);
}
