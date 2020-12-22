<?php

declare(strict_types=1);

namespace PoPSchema\UserRolesWP\Config;

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
            \PoPSchema\UserRoles\FieldResolvers\RootRolesFieldResolver::class,
            \PoPSchema\UserRolesWP\FieldResolvers\Overrides\RootRolesFieldResolver::class
        );
        ContainerBuilderUtils::injectValuesIntoService(
            InstanceManagerInterface::class,
            'overrideClass',
            \PoPSchema\UserRoles\FieldResolvers\UserFieldResolver::class,
            \PoPSchema\UserRolesWP\FieldResolvers\Overrides\UserFieldResolver::class
        );
    }
}
