<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Facades\Cache;

use PoP\Root\App;
use PoP\ComponentModel\Cache\PersistentCacheInterface;

class PersistentCacheFacade
{
    public static function getInstance(): PersistentCacheInterface
    {
        /**
         * @var PersistentCacheInterface
         */
        $service = App::getContainer()->get(PersistentCacheInterface::class);
        return $service;
    }
}
