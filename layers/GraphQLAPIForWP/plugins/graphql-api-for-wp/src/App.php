<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI;

use GraphQLAPI\GraphQLAPI\PluginManagement\ExtensionManager;
use GraphQLAPI\GraphQLAPI\PluginManagement\MainPluginManager;
use GraphQLAPI\GraphQLAPI\PluginSkeleton\ExtensionInterface;
use GraphQLAPI\GraphQLAPI\PluginSkeleton\MainPluginInterface;
use PoP\ComponentModel\App\AbstractComponentModelAppProxy;

/**
 * Keep all state in the application stored and accessible
 * through this class, so that regenerating this class
 * provides a new state.
 *
 * Needed for PHPUnit.
 */
class App extends AbstractComponentModelAppProxy implements AppInterface
{
    public static function initializePlugin(
        ?MainPluginManager $mainPluginManager = null,
        ?ExtensionManager $extensionManager = null,
    ): void {
        /** @var AppThreadInterface */
        $appThread = static::getAppThread();
        $appThread->initializePlugin(
            $mainPluginManager,
            $extensionManager,
        );
    }

    public static function getMainPluginManager(): MainPluginManager
    {
        /** @var AppThreadInterface */
        $appThread = static::getAppThread();
        return $appThread->getMainPluginManager();
    }

    public static function getExtensionManager(): ExtensionManager
    {
        /** @var AppThreadInterface */
        $appThread = static::getAppThread();
        return $appThread->getExtensionManager();
    }

    /**
     * Shortcut function.
     */
    public static function getMainPlugin(): MainPluginInterface
    {
        /** @var AppThreadInterface */
        $appThread = static::getAppThread();
        return $appThread->getMainPlugin();
    }

    /**
     * Shortcut function.
     *
     * @phpstan-param class-string<ExtensionInterface> $extensionClass
     */
    public static function getExtension(string $extensionClass): ExtensionInterface
    {
        /** @var AppThreadInterface */
        $appThread = static::getAppThread();
        return $appThread->getExtension($extensionClass);
    }
}
