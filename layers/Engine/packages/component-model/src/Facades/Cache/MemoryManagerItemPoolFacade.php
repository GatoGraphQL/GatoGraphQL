<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Facades\Cache;

use Psr\Cache\CacheItemPoolInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class MemoryManagerItemPoolFacade
{
    public static function getInstance(): CacheItemPoolInterface
    {
        /**
         * @var CacheItemPoolInterface
         */
        $service = ContainerBuilderFactory::getInstance()->get('memory_cache_item_pool');
        return $service;
    }
}
