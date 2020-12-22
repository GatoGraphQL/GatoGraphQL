<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Config;

use PoP\ComponentModel\Configuration\Request;
use PoP\Definitions\DefinitionManagerInterface;
use PoP\Root\Component\PHPServiceConfigurationTrait;
use PoP\ComponentModel\Container\ContainerBuilderUtils;
use PoP\ComponentModel\Engine\DataloadingEngineInterface;
use PoP\ComponentModel\DirectiveResolvers\ValidateDirectiveResolver;
use PoP\ComponentModel\ModuleFiltering\ModuleFilterManagerInterface;
use PoP\ComponentModel\DirectiveResolvers\ResolveValueAndMergeDirectiveResolver;

class ServiceConfiguration
{
    use PHPServiceConfigurationTrait;

    protected static function configure(): void
    {
        // If `isMangled`, disable the definitions
        if (!Request::isMangled()) {
            ContainerBuilderUtils::injectValuesIntoService(
                DefinitionManagerInterface::class,
                'setEnabled',
                false
            );
        }

        // Add ModuleFilters to the ModuleFilterManager
        ContainerBuilderUtils::injectServicesIntoService(
            ModuleFilterManagerInterface::class,
            'PoP\\ComponentModel\\ModuleFilters',
            'add'
        );

        // Inject the mandatory root directives
        ContainerBuilderUtils::injectValuesIntoService(
            DataloadingEngineInterface::class,
            'addMandatoryDirectiveClasses',
            [
                ValidateDirectiveResolver::class,
                ResolveValueAndMergeDirectiveResolver::class,
            ]
        );
    }
}
