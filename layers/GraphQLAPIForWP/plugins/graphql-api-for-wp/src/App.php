<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI;

use GraphQLAPI\GraphQLAPI\App\AbstractComponentModelAppProxy;
use GraphQLAPI\GraphQLAPI\PluginManagement\ExtensionManager;
use GraphQLAPI\GraphQLAPI\PluginManagement\MainPluginManager;
use GraphQLAPI\GraphQLAPI\PluginSkeleton\ExtensionInterface;
use GraphQLAPI\GraphQLAPI\PluginSkeleton\MainPluginInterface;

/**
 * Decorator wrapping the single class App hosting all the top-level instances,
 * plus addition of properties needed for the plugin.
 *
 * Using composition instead of inheritance, so that the original PoP\Root\App
 * is the single source of truth
 */
class App extends AbstractComponentModelAppProxy implements AppInterface
{
    protected static MainPluginManager $mainPluginManager;
    protected static ExtensionManager $extensionManager;

    public static function initializePlugin(
        ?MainPluginManager $mainPluginManager = null,
        ?ExtensionManager $extensionManager = null,
    ): void {
        self::$mainPluginManager = $mainPluginManager ?? static::createMainPluginManager();
        self::$extensionManager = $extensionManager ?? static::createExtensionManager();
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
        return self::$mainPluginManager;
    }

    public static function getExtensionManager(): ExtensionManager
    {
        return self::$extensionManager;
    }

    /**
     * Shortcut function.
     */
    public static function getMainPlugin(): MainPluginInterface
    {
        return self::getMainPluginManager()->getPlugin();
    }

    /**
     * Shortcut function.
     */
    public static function getExtension(string $extensionClass): ExtensionInterface
    {
        return self::getExtensionManager()->getExtension($extensionClass);
    }
}
