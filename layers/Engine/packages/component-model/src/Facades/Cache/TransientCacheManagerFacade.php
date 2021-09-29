<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Facades\Cache;

use PoP\ComponentModel\Cache\CacheInterface;
use PoP\ComponentModel\Cache\TransientCacheInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class TransientCacheManagerFacade
{
    public static function getInstance(): CacheInterface
    {
        /**
         * @var CacheInterface
         */
        $service = ContainerBuilderFactory::getInstance()->get(TransientCacheInterface::class);
        return $service;
    }
}
