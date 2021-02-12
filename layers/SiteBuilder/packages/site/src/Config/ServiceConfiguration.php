<?php

declare(strict_types=1);

namespace PoP\Site\Config;

use PoP\Resources\DefinitionGroups;
use PoP\Definitions\DefinitionManagerInterface;
use PoP\Root\Component\PHPServiceConfigurationTrait;
use PoP\Root\Container\ContainerBuilderUtils;

class ServiceConfiguration
{
    use PHPServiceConfigurationTrait;

    protected static function configure(): void
    {
        // Set the definition resolver
        ContainerBuilderUtils::injectValuesIntoService(
            DefinitionManagerInterface::class,
            'setDefinitionResolver',
            '@base36_definition_resolver',
            DefinitionGroups::RESOURCES
        );
    }
}
