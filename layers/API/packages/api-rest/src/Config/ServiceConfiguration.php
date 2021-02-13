<?php

declare(strict_types=1);

namespace PoP\RESTAPI\Config;

use PoP\Root\Component\PHPServiceConfigurationTrait;
use PoP\Root\Container\ContainerBuilderUtils;
use PoP\ModuleRouting\RouteModuleProcessorManagerInterface;
use PoP\ComponentModel\DataStructure\DataStructureManagerInterface;

class ServiceConfiguration
{
    use PHPServiceConfigurationTrait;

    protected static function configure(): void
    {
        // Add RouteModuleProcessors to the Manager
        ContainerBuilderUtils::injectServicesIntoService(
            RouteModuleProcessorManagerInterface::class,
            'PoP\\RESTAPI\\RouteModuleProcessors',
            'add'
        );

        ContainerBuilderUtils::injectServicesIntoService(
            DataStructureManagerInterface::class,
            'PoP\\RESTAPI\\DataStructureFormatters',
            'add'
        );
    }
}
