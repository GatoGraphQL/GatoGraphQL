<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Facades;

use GraphQLAPI\GraphQLAPI\Registries\ModuleRegistry;
use GraphQLAPI\GraphQLAPI\Registries\ModuleRegistryInterface;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\SchemaTypeModuleResolver;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\CacheFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\ClientFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\EndpointFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\VersioningFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\OperationalFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\PerformanceFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\AccessControlFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\UserInterfaceFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\PluginManagementFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\SchemaConfigurationFunctionalityModuleResolver;

/**
 * Obtain an instance of the ModuleRegistry.
 * Manage the instance internally instead of using the ContainerBuilder,
 * because it is required for setting configuration values before components
 * are initialized, so the ContainerBuilder is still unavailable
 */
class ModuleRegistryFacade
{
    private static ?ModuleRegistryInterface $instance = null;

    public static function getInstance(): ModuleRegistryInterface
    {
        if (is_null(self::$instance)) {
            // Instantiate
            self::$instance = new ModuleRegistry();
            // Add the ModuleResolvers
            self::$instance->addModuleResolver(new PluginManagementFunctionalityModuleResolver());
            self::$instance->addModuleResolver(new EndpointFunctionalityModuleResolver());
            self::$instance->addModuleResolver(new SchemaConfigurationFunctionalityModuleResolver());
            self::$instance->addModuleResolver(new AccessControlFunctionalityModuleResolver());
            self::$instance->addModuleResolver(new VersioningFunctionalityModuleResolver());
            self::$instance->addModuleResolver(new UserInterfaceFunctionalityModuleResolver());
            self::$instance->addModuleResolver(new PerformanceFunctionalityModuleResolver());
            self::$instance->addModuleResolver(new CacheFunctionalityModuleResolver());
            self::$instance->addModuleResolver(new OperationalFunctionalityModuleResolver());
            self::$instance->addModuleResolver(new ClientFunctionalityModuleResolver());
            self::$instance->addModuleResolver(new SchemaTypeModuleResolver());
        }
        return self::$instance;
    }
}
