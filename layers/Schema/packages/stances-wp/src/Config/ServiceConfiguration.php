<?php

declare(strict_types=1);

namespace PoPSchema\StancesWP\Config;

use PoPSchema\Stances\TypeResolverPickers\Optional\StanceCustomPostTypeResolverPicker;
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
            StanceCustomPostTypeResolverPicker::class,
            \PoPSchema\StancesWP\TypeResolverPickers\Overrides\StanceCustomPostTypeResolverPicker::class
        );
    }
}
