<?php

declare(strict_types=1);

namespace PoP\SPA\Config;

use PoP\Root\Component\PHPServiceConfigurationTrait;
use PoP\ComponentModel\Container\ContainerBuilderUtils;
use PoP\ComponentModel\ModuleFiltering\ModuleFilterManagerInterface;

class ServiceConfiguration
{
    use PHPServiceConfigurationTrait;

    protected static function configure(): void
    {
        // Add ModuleFilter to the ModuleFilterManager
        ContainerBuilderUtils::injectServicesIntoService(
            ModuleFilterManagerInterface::class,
            'PoP\\SPA\\ModuleFilters',
            'add'
        );
    }
}
