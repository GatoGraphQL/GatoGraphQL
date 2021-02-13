<?php

declare(strict_types=1);

namespace PoP\API\Config;

use PoP\Root\Component\PHPServiceConfigurationTrait;
use PoP\Root\Container\ContainerBuilderUtils;
use PoP\ModuleRouting\RouteModuleProcessorManagerInterface;

class ServiceConfiguration
{
    use PHPServiceConfigurationTrait;

    protected static function configure(): void
    {
        // Add RouteModuleProcessors to the Manager
        ContainerBuilderUtils::injectServicesIntoService(
            RouteModuleProcessorManagerInterface::class,
            'PoP\\API\\RouteModuleProcessors',
            'add'
        );
    }
}
