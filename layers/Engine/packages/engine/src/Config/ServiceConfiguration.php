<?php

declare(strict_types=1);

namespace PoP\Engine\Config;

use PoP\Engine\ComponentConfiguration;
use PoP\Root\Component\PHPServiceConfigurationTrait;
use PoP\Root\Container\ContainerBuilderUtils;
use PoP\CacheControl\Component as CacheControlComponent;
use PoP\ComponentModel\Engine\DataloadingEngineInterface;
use PoP\ComponentModel\ModuleFiltering\ModuleFilterManagerInterface;
use PoP\CacheControl\DirectiveResolvers\CacheControlDirectiveResolver;
use PoP\Engine\DirectiveResolvers\SetSelfAsExpressionDirectiveResolver;

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

        // Inject the mandatory root directives
        ContainerBuilderUtils::injectValuesIntoService(
            DataloadingEngineInterface::class,
            'addMandatoryDirectiveClass',
            SetSelfAsExpressionDirectiveResolver::class
        );
        if (ComponentConfiguration::addMandatoryCacheControlDirective()) {
            static::configureCacheControl();
        }
    }

    public static function configureCacheControl()
    {
        if (CacheControlComponent::isEnabled()) {
            ContainerBuilderUtils::injectValuesIntoService(
                DataloadingEngineInterface::class,
                'addMandatoryDirectives',
                [
                    CacheControlDirectiveResolver::getDirectiveName(),
                ]
            );
        }
    }
}
