<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Facades;

use PoP\ComponentModel\Cache\CacheConfigurationManagerInterface;

trait ContainerCacheConfigurationManagerFacadeTrait
{
    protected static ?CacheConfigurationManagerInterface $instance = null;

    public static function getInstance(): CacheConfigurationManagerInterface
    {
        if (self::$instance === null) {
            self::$instance = self::doGetInstance();
        }
        return self::$instance;
    }

    /**
     * We can't use the InstanceManager, since at this stage
     * it hasn't been initialized yet.
     * We can create a new instance of these classes
     * because their instantiation produces no side-effects
     * (maybe that happens under `initialize`)
     */
    abstract protected static function doGetInstance(): CacheConfigurationManagerInterface;
}
