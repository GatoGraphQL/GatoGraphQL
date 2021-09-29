<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Facades\Cache;

use PoP\ComponentModel\Cache\PersistentCacheInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class PersistentCacheFacade
{
    public static function getInstance(): PersistentCacheInterface
    {
        /**
         * @var PersistentCacheInterface
         */
        $service = ContainerBuilderFactory::getInstance()->get(PersistentCacheInterface::class);
        return $service;
    }
}
