<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI;

use GraphQLAPI\GraphQLAPI\PluginManagement\ExtensionManager;
use GraphQLAPI\GraphQLAPI\PluginManagement\MainPluginManager;
use GraphQLAPI\GraphQLAPI\PluginSkeleton\ExtensionInterface;
use GraphQLAPI\GraphQLAPI\PluginSkeleton\MainPluginInterface;
use LogicException;
use PoP\Root\App as RootApp;
use PoP\Root\AppInterface as RootAppInterface;
use PoP\Root\AppLoaderInterface;
use PoP\Root\Component\ComponentInterface;
use PoP\Root\Container\ContainerBuilderFactory;
use PoP\Root\Container\ContainerInterface;
use PoP\Root\Container\SystemContainerBuilderFactory;
use PoP\Root\HttpFoundation\Request;
use PoP\Root\StateManagers\AppStateManagerInterface;
use PoP\Root\StateManagers\ComponentManagerInterface;
use PoP\Root\StateManagers\HookManagerInterface;
use PoP\Root\Stores\MutationResolutionStore;

/**
 * Decorator wrapping the single class App hosting all the top-level instances,
 * plus addition of properties needed for the plugin.
 *
 * Using composition instead of inheritance, so that the original PoP\Root\App
 * is the single source of truth
 */
class App implements AppInterface, RootAppInterface
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
        ?AppLoaderInterface $appLoader = null,
        ?HookManagerInterface $hookManager = null,
        ?Request $request = null,
        ?ContainerBuilderFactory $containerBuilderFactory = null,
        ?SystemContainerBuilderFactory $systemContainerBuilderFactory = null,
        ?ComponentManagerInterface $componentManager = null,
        ?AppStateManagerInterface $appStateManager = null,
        ?MutationResolutionStore $mutationResolutionStore = null,
    ): void {
        RootApp::initialize(
            $appLoader,
            $hookManager,
            $request,
            $containerBuilderFactory,
            $systemContainerBuilderFactory,
            $componentManager,
            $appStateManager,
            $mutationResolutionStore,
        );
    }

    public static function getAppLoader(): AppLoaderInterface
    {
        return RootApp::getAppLoader();
    }

    public static function getHookManager(): HookManagerInterface
    {
        return RootApp::getHookManager();
    }

    public static function getRequest(): Request
    {
        return RootApp::getRequest();
    }

    public static function getContainerBuilderFactory(): ContainerBuilderFactory
    {
        return RootApp::getContainerBuilderFactory();
    }

    public static function getSystemContainerBuilderFactory(): SystemContainerBuilderFactory
    {
        return RootApp::getSystemContainerBuilderFactory();
    }

    public static function getComponentManager(): ComponentManagerInterface
    {
        return RootApp::getComponentManager();
    }

    public static function getMutationResolutionStore(): MutationResolutionStore
    {
        return RootApp::getMutationResolutionStore();
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
        RootApp::stockAndInitializeComponentClasses($componentClasses);
    }

    /**
     * Shortcut function.
     */
    final public static function getContainer(): ContainerInterface
    {
        return RootApp::getContainer();
    }

    /**
     * Shortcut function.
     */
    final public static function getSystemContainer(): ContainerInterface
    {
        return RootApp::getSystemContainer();
    }

    /**
     * Shortcut function.
     *
     * @throws LogicException
     */
    final public static function getComponent(string $componentClass): ComponentInterface
    {
        return RootApp::getComponent($componentClass);
    }

    /**
     * Shortcut function.
     */
    public static function addFilter(string $tag, callable $function_to_add, int $priority = 10, int $accepted_args = 1): void
    {
        RootApp::addFilter($tag, $function_to_add, $priority, $accepted_args);
    }

    /**
     * Shortcut function.
     */
    public static function removeFilter(string $tag, callable $function_to_remove, int $priority = 10): bool
    {
        return RootApp::removeFilter($tag, $function_to_remove, $priority);
    }

    /**
     * Shortcut function.
     */
    public static function applyFilters(string $tag, mixed $value, mixed ...$args): mixed
    {
        return RootApp::applyFilters($tag, $value, ...$args);
    }

    /**
     * Shortcut function.
     */
    public static function addAction(string $tag, callable $function_to_add, int $priority = 10, int $accepted_args = 1): void
    {
        RootApp::addAction($tag, $function_to_add, $priority, $accepted_args);
    }

    /**
     * Shortcut function.
     */
    public static function removeAction(string $tag, callable $function_to_remove, int $priority = 10): bool
    {
        return RootApp::removeAction($tag, $function_to_remove, $priority);
    }

    /**
     * Shortcut function.
     */
    public static function doAction(string $tag, mixed ...$args): void
    {
        RootApp::doAction($tag, ...$args);
    }

    /**
     * Shortcut function.
     *
     * Equivalent of $_POST[$key] ?? $default
     */
    final public static function request(string $key, mixed $default = null): mixed
    {
        return RootApp::request($key, $default);
    }

    /**
     * Shortcut function.
     *
     * Equivalent of $_GET[$key] ?? $default
     */
    final public static function query(string $key, mixed $default = null): mixed
    {
        return RootApp::query($key, $default);
    }

    /**
     * Shortcut function.
     *
     * Equivalent of $_COOKIES[$key] ?? $default
     */
    final public static function cookies(string $key, mixed $default = null): mixed
    {
        return RootApp::cookies($key, $default);
    }

    /**
     * Shortcut function.
     *
     * Equivalent of $_FILES[$key] ?? $default
     */
    final public static function files(string $key, mixed $default = null): mixed
    {
        return RootApp::files($key, $default);
    }

    /**
     * Shortcut function.
     *
     * Equivalent of $_SERVER[$key] ?? $default
     */
    final public static function server(string $key, mixed $default = null): mixed
    {
        return RootApp::server($key, $default);
    }

    /**
     * Shortcut function.
     *
     * Mostly equivalent to a subset of $_SERVER
     */
    final public static function headers(string $key, mixed $default = null): mixed
    {
        return RootApp::headers($key, $default);
    }
}
