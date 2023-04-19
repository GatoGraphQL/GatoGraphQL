<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI;

use GraphQLAPI\GraphQLAPI\PluginManagement\ExtensionManager;
use GraphQLAPI\GraphQLAPI\PluginManagement\MainPluginManager;
use GraphQLAPI\GraphQLAPI\PluginSkeleton\ExtensionInterface;
use GraphQLAPI\GraphQLAPI\PluginSkeleton\MainPluginInterface;

class PluginApp implements PluginAppInterface
{
    protected static MainPluginManager $mainPluginManager;
    protected static ExtensionManager $extensionManager;

    public static function initializePlugin(
        ?MainPluginManager $mainPluginManager = null,
        ?ExtensionManager $extensionManager = null,
    ): void {
        self::$mainPluginManager = $mainPluginManager ?? static::createMainPluginManager();
        self::$extensionManager = $extensionManager ?? static::createExtensionManager();

        /**
         * Trigger the plugin's AppInitialization hook
         * on WordPress' "plugins_loaded" hook
         */
        \add_action(
            'plugins_loaded',
            fn () => do_action(
                PluginAppHooks::INITIALIZE_APP,
                PluginAppGraphQLServerNames::EXTERNAL
            ),
            /**
             * Priority 1000: Give room for the extensions (as well as for
             * other plugins) to be initialized first.
             */
            1000
        );
    }

    protected static function createExtensionManager(): ExtensionManager
    {
        return new ExtensionManager();
    }

    protected static function createMainPluginManager(): MainPluginManager
    {
        return new MainPluginManager();
    }

    public static function getMainPluginManager(): MainPluginManager
    {
        return static::$mainPluginManager;
    }

    public static function getExtensionManager(): ExtensionManager
    {
        return static::$extensionManager;
    }

    /**
     * Shortcut function.
     */
    public static function getMainPlugin(): MainPluginInterface
    {
        return static::getMainPluginManager()->getPlugin();
    }

    /**
     * Shortcut function.
     *
     * @phpstan-param class-string<ExtensionInterface> $extensionClass
     */
    public static function getExtension(string $extensionClass): ExtensionInterface
    {
        return static::getExtensionManager()->getExtension($extensionClass);
    }
}
