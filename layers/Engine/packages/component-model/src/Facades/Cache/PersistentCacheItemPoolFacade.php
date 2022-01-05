<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Facades\Cache;

use PoP\Root\App;
use Psr\Cache\CacheItemPoolInterface;

class PersistentCacheItemPoolFacade
{
    public static function getInstance(): CacheItemPoolInterface
    {
        /**
         * @var CacheItemPoolInterface
         */
        $service = App::getContainer()->get('persistent_cache_item_pool');
        return $service;
    }
}
