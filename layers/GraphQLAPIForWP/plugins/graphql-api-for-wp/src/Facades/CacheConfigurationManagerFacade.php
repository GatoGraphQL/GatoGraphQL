<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Facades;

use GraphQLAPI\GraphQLAPI\Overrides\Services\ConfigurationCache\CacheConfigurationManager;
use GraphQLAPI\GraphQLAPI\Registries\ModuleRegistry;
use GraphQLAPI\GraphQLAPI\Registries\UserAuthorizationSchemeRegistry;
use GraphQLAPI\GraphQLAPI\Security\UserAuthorization;
use GraphQLAPI\GraphQLAPI\Security\UserAuthorizationSchemes\ManageOptionsUserAuthorizationScheme;
use GraphQLAPI\GraphQLAPI\Services\Helpers\EndpointHelpers;
use GraphQLAPI\GraphQLAPI\Services\Menus\PluginMenu;
use PoP\ComponentModel\Cache\CacheConfigurationManagerInterface;
use PoP\ComponentModel\Instances\InstanceManager;

/**
 * Obtain an instance of the CacheConfigurationManager.
 * Manage the instance internally instead of using the ContainerBuilder,
 * because it is required for setting configuration values before components
 * are initialized, so the ContainerBuilder (for both Sytem/Application)
 * is still unavailable.
 */
class CacheConfigurationManagerFacade
{
    private static ?CacheConfigurationManagerInterface $instance = null;

    public static function getInstance(): CacheConfigurationManagerInterface
    {
        if (is_null(self::$instance)) {
            // We can't use the InstanceManager, since at this stage
            // it hasn't been initialized yet.
            // We can create a new instance of these classes
            // because their instantiation produces no side-effects
            // (maybe that happens under `initialize`)
            $instanceManager = new InstanceManager();
            $moduleRegistry = new ModuleRegistry();
            $userAuthorizationSchemeRegistry = new UserAuthorizationSchemeRegistry();
            $userAuthorizationSchemeRegistry->addUserAuthorizationScheme(new ManageOptionsUserAuthorizationScheme());
            $userAuthorization = new UserAuthorization($userAuthorizationSchemeRegistry);
            $menu = new PluginMenu(
                $instanceManager,
                $userAuthorization,
            );
            $endpointHelpers = new EndpointHelpers($menu, $moduleRegistry);
            self::$instance = new CacheConfigurationManager($endpointHelpers);
        }
        return self::$instance;
    }
}
