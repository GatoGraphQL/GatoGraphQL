<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI;

use GraphQLAPI\GraphQLAPI\PluginManagement\ExtensionManager;
use GraphQLAPI\GraphQLAPI\PluginManagement\MainPluginManager;
use LogicException;
use PoP\Root\App as UpstreamApp;
use PoP\Root\AppLoader;
use PoP\Root\Component\ComponentInterface;
use PoP\Root\Container\ContainerBuilderFactory;
use PoP\Root\Container\SystemContainerBuilderFactory;
use PoP\Root\Managers\ComponentManager;
use Symfony\Component\DependencyInjection\Container;

/**
 * Decorator wrapping the single class App hosting all the top-level instances,
 * plus addition of properties needed for the plugin.
 *
 * Using composition instead of inheritance, so that the original PoP\Root\App
 * is the single source of truth
 */
class App implements AppInterface
{
    protected static UpstreamApp $app;
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
     * This function must be invoked at the very beginning,
     * to initialize the instance to run the application.
     *
     * Either inject the desired instance, or have the Root
     * provide the default one.
     */
    public static function initialize(
        ?AppLoader $appLoader = null,
        ?ContainerBuilderFactory $containerBuilderFactory = null,
        ?SystemContainerBuilderFactory $systemContainerBuilderFactory = null,
        ?ComponentManager $componentManager = null,
    ): void {
        self::$app::initialize(
            $appLoader,
            $containerBuilderFactory,
            $systemContainerBuilderFactory,
            $componentManager,
        );
    }

    public static function getAppLoader(): AppLoader
    {
        return self::$app::getAppLoader();
    }

    public static function getContainerBuilderFactory(): ContainerBuilderFactory
    {
        return self::$app::getContainerBuilderFactory();
    }

    public static function getSystemContainerBuilderFactory(): SystemContainerBuilderFactory
    {
        return self::$app::getSystemContainerBuilderFactory();
    }

    public static function getComponentManager(): ComponentManager
    {
        return self::$app::getComponentManager();
    }

    /**
     * Store Component classes to be initialized, and
     * inject them into the AppLoader when this is initialized.
     *
     * @param string[] $componentClasses List of `Component` class to initialize
     */
    public static function stockAndInitializeComponentClasses(
        array $componentClasses
    ): void {
        self::$app::stockAndInitializeComponentClasses($componentClasses);
    }

    /**
     * Shortcut function.
     */
    final public static function getContainer(): Container
    {
        return self::$app::getContainer();
    }

    /**
     * Shortcut function.
     */
    final public static function getSystemContainer(): Container
    {
        return self::$app::getSystemContainer();
    }

    /**
     * Shortcut function.
     *
     * @throws LogicException
     */
    final public static function getComponent(string $componentClass): ComponentInterface
    {
        return self::$app::getComponent($componentClass);
    }
}
