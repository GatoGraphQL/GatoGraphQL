<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI;

use GraphQLAPI\GraphQLAPI\PluginManagement\ExtensionManager;
use GraphQLAPI\GraphQLAPI\PluginManagement\MainPluginManager;
use GraphQLAPI\GraphQLAPI\PluginSkeleton\ExtensionInterface;
use GraphQLAPI\GraphQLAPI\PluginSkeleton\MainPluginInterface;
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
        UpstreamApp::initialize(
            $appLoader,
            $containerBuilderFactory,
            $systemContainerBuilderFactory,
            $componentManager,
        );
    }

    public static function getAppLoader(): AppLoader
    {
        return UpstreamApp::getAppLoader();
    }

    public static function getContainerBuilderFactory(): ContainerBuilderFactory
    {
        return UpstreamApp::getContainerBuilderFactory();
    }

    public static function getSystemContainerBuilderFactory(): SystemContainerBuilderFactory
    {
        return UpstreamApp::getSystemContainerBuilderFactory();
    }

    public static function getComponentManager(): ComponentManager
    {
        return UpstreamApp::getComponentManager();
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
        UpstreamApp::stockAndInitializeComponentClasses($componentClasses);
    }

    /**
     * Shortcut function.
     */
    final public static function getContainer(): Container
    {
        return UpstreamApp::getContainer();
    }

    /**
     * Shortcut function.
     */
    final public static function getSystemContainer(): Container
    {
        return UpstreamApp::getSystemContainer();
    }

    /**
     * Shortcut function.
     *
     * @throws LogicException
     */
    final public static function getComponent(string $componentClass): ComponentInterface
    {
        return UpstreamApp::getComponent($componentClass);
    }
}
