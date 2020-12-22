<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Facades;

use GraphQLAPI\GraphQLAPI\ModuleTypeResolvers\ModuleTypeResolver;
use GraphQLAPI\GraphQLAPI\Registries\ModuleTypeRegistry;
use GraphQLAPI\GraphQLAPI\Registries\ModuleTypeRegistryInterface;

/**
 * Obtain an instance of the ModuleTypeRegistry.
 * Manage the instance internally instead of using the ContainerBuilder,
 * because it is required for setting configuration values before components
 * are initialized, so the ContainerBuilder is still unavailable
 */
class ModuleTypeRegistryFacade
{
    private static ?ModuleTypeRegistryInterface $instance = null;

    public static function getInstance(): ModuleTypeRegistryInterface
    {
        if (is_null(self::$instance)) {
            // Instantiate
            self::$instance = new ModuleTypeRegistry();
            // Add the ModuleTypeResolvers
            self::$instance->addModuleTypeResolver(new ModuleTypeResolver());
        }
        return self::$instance;
    }
}
