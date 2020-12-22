<?php

declare(strict_types=1);

namespace PoP\ConfigurationComponentModel\Config;

use PoP\Root\Component\PHPServiceConfigurationTrait;
use PoP\ComponentModel\Container\ContainerBuilderUtils;
use PoP\ModuleRouting\RouteModuleProcessorManagerInterface;

class ServiceConfiguration
{
    use PHPServiceConfigurationTrait;

    protected static function configure(): void
    {
        // Add RouteModuleProcessors to the Manager
        ContainerBuilderUtils::injectServicesIntoService(
            RouteModuleProcessorManagerInterface::class,
            'PoP\\ConfigurationComponentModel\\RouteModuleProcessors',
            'add'
        );
    }
}
