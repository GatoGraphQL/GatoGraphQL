<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Facades\Cache;

use PoP\ComponentModel\Cache\CacheInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class PersistentCacheFacade
{
    public static function getInstance(): ?CacheInterface
    {
        $containerBuilderFactory = ContainerBuilderFactory::getInstance();
        if ($containerBuilderFactory->has('persistent_cache')) {
            return $containerBuilderFactory->get('persistent_cache');
        }
        return null;
    }
}
