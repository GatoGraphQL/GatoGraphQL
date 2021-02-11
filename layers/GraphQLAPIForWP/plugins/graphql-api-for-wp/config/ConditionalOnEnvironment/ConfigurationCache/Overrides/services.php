<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use PoP\ComponentModel\Cache\CacheConfigurationManagerInterface;
use GraphQLAPI\GraphQLAPI\ConditionalOnEnvironment\ConfigurationCache\Overrides\CacheConfigurationManager;

return function (ContainerConfigurator $configurator) {
    $services = $configurator->services()
        ->defaults()
            ->public()
            ->autowire();
    // Override to configure the cache with dynamic values
    $services->set(
        CacheConfigurationManagerInterface::class,
        CacheConfigurationManager::class
    );
};
