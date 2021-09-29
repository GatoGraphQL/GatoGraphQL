<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Facades\Cache;

use PoP\ComponentModel\Cache\PersistentCacheInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class PersistentCacheFacade
{
    public static function getInstance(): ?PersistentCacheInterface
    {
        $containerBuilderFactory = ContainerBuilderFactory::getInstance();
        if ($containerBuilderFactory->has(PersistentCacheInterface::class)) {
            return $containerBuilderFactory->get(PersistentCacheInterface::class);
        }
        return null;
    }
}
