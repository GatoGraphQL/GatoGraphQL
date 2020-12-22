<?php

declare(strict_types=1);

namespace PoPSchema\PostTags\Config;

use PoP\Root\Component\PHPServiceConfigurationTrait;
use PoP\ComponentModel\Container\ContainerBuilderUtils;
use PoP\ModuleRouting\RouteModuleProcessorManagerInterface;

class ServiceConfiguration
{
    use PHPServiceConfigurationTrait;

    protected static function configure(): void
    {
        // Add RouteModuleProcessors to the Manager
        // Load API and RESTAPI conditional classes
        if (class_exists('\PoP\API\Component') && \PoP\API\Component::isEnabled()) {
            ContainerBuilderUtils::injectServicesIntoService(
                RouteModuleProcessorManagerInterface::class,
                'PoPSchema\\PostTags\\Conditional\\API\\RouteModuleProcessors',
                'add'
            );
        }
        if (class_exists('\PoP\RESTAPI\Component') && \PoP\RESTAPI\Component::isEnabled()) {
            ContainerBuilderUtils::injectServicesIntoService(
                RouteModuleProcessorManagerInterface::class,
                'PoPSchema\\PostTags\\Conditional\\RESTAPI\\RouteModuleProcessors',
                'add'
            );
        }
    }
}
