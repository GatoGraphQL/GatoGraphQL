<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Facades\Cache;

use PoP\Root\App;
use Psr\Cache\CacheItemPoolInterface;

class TransientCacheManagerItemPoolFacade
{
    public static function getInstance(): CacheItemPoolInterface
    {
        /**
         * @var CacheItemPoolInterface
         */
        $service = App::getContainer()->get('transient_cache_item_pool');
        return $service;
    }
}
