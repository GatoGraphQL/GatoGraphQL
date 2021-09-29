<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Facades\Cache;

use PoP\Root\Container\ContainerBuilderFactory;
use Psr\Cache\CacheItemPoolInterface;

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
