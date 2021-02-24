<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Facades;

use GraphQLAPI\GraphQLAPI\Registries\ModuleRegistryInterface;
use PoP\Root\Container\SystemContainerBuilderFactory;

/**
 * Obtain an instance of the ModuleRegistry.
 * Use the System Container because it is required for
 * setting configuration values before components
 * are initialized, so the ContainerBuilder is still unavailable
 */
class ModuleRegistryFacade
{
    public static function getInstance(): ModuleRegistryInterface
    {
        /**
         * @var ModuleRegistryInterface
         */
        return SystemContainerBuilderFactory::getInstance()->get(ModuleRegistryInterface::class);
        // if (is_null(self::$instance)) {
        //     // Instantiate
        //     self::$instance = new ModuleRegistry();
        //     // Add the ModuleResolvers
        //     self::$instance->addModuleResolver(new PluginManagementFunctionalityModuleResolver());
        //     self::$instance->addModuleResolver(new EndpointFunctionalityModuleResolver());
        //     self::$instance->addModuleResolver(new SchemaConfigurationFunctionalityModuleResolver());
        //     self::$instance->addModuleResolver(new AccessControlFunctionalityModuleResolver());
        //     self::$instance->addModuleResolver(new VersioningFunctionalityModuleResolver());
        //     self::$instance->addModuleResolver(new UserInterfaceFunctionalityModuleResolver());
        //     self::$instance->addModuleResolver(new PerformanceFunctionalityModuleResolver());
        //     self::$instance->addModuleResolver(new CacheFunctionalityModuleResolver());
        //     self::$instance->addModuleResolver(new OperationalFunctionalityModuleResolver());
        //     self::$instance->addModuleResolver(new ClientFunctionalityModuleResolver());
        //     self::$instance->addModuleResolver(new SchemaTypeModuleResolver());
        // }
        // return self::$instance;
    }
}
