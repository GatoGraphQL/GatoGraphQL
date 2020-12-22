<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Facades\Cache;

use Psr\Cache\CacheItemPoolInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class PersistentCacheItemPoolFacade
{
    public static function getInstance(): ?CacheItemPoolInterface
    {
        $containerBuilderFactory = ContainerBuilderFactory::getInstance();
        if ($containerBuilderFactory->has('persistent_cache_item_pool')) {
            return $containerBuilderFactory->get('persistent_cache_item_pool');
        }
        return null;
    }
}
