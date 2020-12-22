<?php

declare(strict_types=1);

namespace PoPSchema\HighlightsWP\Config;

use PoP\Root\Component\PHPServiceConfigurationTrait;
use PoP\ComponentModel\Container\ContainerBuilderUtils;
use PoP\ComponentModel\Instances\InstanceManagerInterface;

class ServiceConfiguration
{
    use PHPServiceConfigurationTrait;

    protected static function configure(): void
    {
        ContainerBuilderUtils::injectValuesIntoService(
            InstanceManagerInterface::class,
            'overrideClass',
            \PoPSchema\Highlights\TypeResolverPickers\Optional\HighlightCustomPostTypeResolverPicker::class,
            \PoPSchema\HighlightsWP\TypeResolverPickers\Overrides\HighlightCustomPostTypeResolverPicker::class
        );
    }
}
