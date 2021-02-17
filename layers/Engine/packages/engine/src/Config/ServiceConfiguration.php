<?php

declare(strict_types=1);

namespace PoP\Engine\Config;

use PoP\Root\Component\PHPServiceConfigurationTrait;
use PoP\Root\Container\ContainerBuilderUtils;
use PoP\ComponentModel\ModuleFiltering\ModuleFilterManagerInterface;

class ServiceConfiguration
{
    use PHPServiceConfigurationTrait;

    protected static function configure(): void
    {
        // Add ModuleFilters to the ModuleFilterManager
        ContainerBuilderUtils::injectServicesIntoService(
            ModuleFilterManagerInterface::class,
            'PoP\\Engine\\ModuleFilters',
            'add'
        );
    }
}
