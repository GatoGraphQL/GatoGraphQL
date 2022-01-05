<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Facades\Cache;

use PoP\Root\Container\ContainerBuilderFactory;
use Psr\Cache\CacheItemPoolInterface;

class TransientCacheManagerItemPoolFacade
{
    public static function getInstance(): CacheItemPoolInterface
    {
        /**
         * @var CacheItemPoolInterface
         */
        $service = \PoP\Root\App::getContainerBuilderFactory()->getInstance()->get('transient_cache_item_pool');
        return $service;
    }
}
