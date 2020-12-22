<?php

declare(strict_types=1);

namespace PoP\DefinitionPersistence\Config;

use PoP\Definitions\DefinitionManagerInterface;
use PoP\Root\Component\PHPServiceConfigurationTrait;
use PoP\ComponentModel\Container\ContainerBuilderUtils;

class ServiceConfiguration
{
    use PHPServiceConfigurationTrait;

    protected static function configure(): void
    {
        // Add ModuleFilter to the ModuleFilterManager
        ContainerBuilderUtils::injectServiceIntoService(
            DefinitionManagerInterface::class,
            'file_definition_persistence',
            'setDefinitionPersistence'
        );
    }
}
