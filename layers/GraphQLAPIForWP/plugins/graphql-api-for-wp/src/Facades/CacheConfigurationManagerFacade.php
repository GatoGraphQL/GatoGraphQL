<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Facades;

use GraphQLAPI\GraphQLAPI\Cache\CacheConfigurationManager;
use PoP\ComponentModel\Cache\CacheConfigurationManagerInterface;

/**
 * Obtain an instance of the CacheConfigurationManager.
 * Manage the instance internally instead of using the ContainerBuilder,
 * because it is required for setting configuration values before components
 * are initialized, so the ContainerBuilder is still unavailable
 */
class CacheConfigurationManagerFacade
{
    private static ?CacheConfigurationManagerInterface $instance = null;

    public static function getInstance(): CacheConfigurationManagerInterface
    {
        if (is_null(self::$instance)) {
            self::$instance = new CacheConfigurationManager();
        }
        return self::$instance;
    }
}
