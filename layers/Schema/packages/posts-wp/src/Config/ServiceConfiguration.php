<?php

declare(strict_types=1);

namespace PoPSchema\PostsWP\Config;

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
            \PoPSchema\Posts\TypeResolverPickers\Optional\PostCustomPostTypeResolverPicker::class,
            \PoPSchema\PostsWP\TypeResolverPickers\Overrides\PostCustomPostTypeResolverPicker::class
        );
    }
}
