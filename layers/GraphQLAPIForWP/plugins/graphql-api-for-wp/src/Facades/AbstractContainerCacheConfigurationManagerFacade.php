<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Facades;

use GraphQLAPI\GraphQLAPI\ConfigurationCache\ContainerCacheConfigurationManager;
use GraphQLAPI\GraphQLAPI\Registries\UserAuthorizationSchemeRegistry;
use GraphQLAPI\GraphQLAPI\Security\UserAuthorization;
use GraphQLAPI\GraphQLAPI\Security\UserAuthorizationSchemes\ManageOptionsUserAuthorizationScheme;
use GraphQLAPI\GraphQLAPI\Services\Helpers\EndpointHelpers;
use GraphQLAPI\GraphQLAPI\Services\Menus\PluginMenu;
use PoP\ComponentModel\Cache\CacheConfigurationManagerInterface;
use PoP\Root\Instances\InstanceManager;

/**
 * Obtain an instance of the ContainerCacheConfigurationManager.
 * Manage the instance internally instead of using the ContainerBuilder,
 * because it is required for setting configuration values before components
 * are initialized, so the ContainerBuilder (for both Sytem/Application)
 * is still unavailable.
 */
abstract class AbstractContainerCacheConfigurationManagerFacade
{
    /**
     * We can't use the InstanceManager, since at this stage
     * it hasn't been initialized yet.
     * We can create a new instance of these classes
     * because their instantiation produces no side-effects
     * (maybe that happens under `initialize`)
     */
    protected static function doGetInstance(): CacheConfigurationManagerInterface
    {
        $instanceManager = new InstanceManager();
        $userAuthorizationSchemeRegistry = new UserAuthorizationSchemeRegistry();
        $manageOptionsUserAuthorizationScheme = new ManageOptionsUserAuthorizationScheme();
        $userAuthorizationSchemeRegistry->addUserAuthorizationScheme($manageOptionsUserAuthorizationScheme);
        $userAuthorization = new UserAuthorization();
        $userAuthorization->setUserAuthorizationSchemeRegistry($userAuthorizationSchemeRegistry);
        $pluginMenu = new PluginMenu();
        $pluginMenu->setInstanceManager($instanceManager);
        $pluginMenu->setUserAuthorization($userAuthorization);
        $endpointHelpers = new EndpointHelpers();
        $endpointHelpers->setPluginMenu($pluginMenu);
        $containerCacheConfigurationManager = static::createContainerCacheConfigurationManager();
        $containerCacheConfigurationManager->setEndpointHelpers($endpointHelpers);
        return $containerCacheConfigurationManager;
    }

    abstract protected static function createContainerCacheConfigurationManager(): ContainerCacheConfigurationManager;
}
