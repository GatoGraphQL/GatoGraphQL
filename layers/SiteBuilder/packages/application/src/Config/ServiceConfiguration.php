<?php

declare(strict_types=1);

namespace PoP\Application\Config;

use PoP\Definitions\DefinitionManagerInterface;
use PoP\ComponentModel\Modules\DefinitionGroups;
use PoP\Root\Component\PHPServiceConfigurationTrait;
use PoP\Root\Container\ContainerBuilderUtils;
use PoP\ComponentModel\ModuleFiltering\ModuleFilterManagerInterface;

class ServiceConfiguration
{
    use PHPServiceConfigurationTrait;

    protected static function configure(): void
    {
        // Add ModuleFilter to the ModuleFilterManager
        ContainerBuilderUtils::injectServicesIntoService(
            ModuleFilterManagerInterface::class,
            'PoP\\Application\\ModuleFilters',
            'add'
        );

        // Set the definition resolver
        ContainerBuilderUtils::injectValuesIntoService(
            DefinitionManagerInterface::class,
            'setDefinitionResolver',
            '@emoji_definition_resolver',
            DefinitionGroups::MODULES
        );
    }
}
