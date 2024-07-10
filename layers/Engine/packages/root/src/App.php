<?php

declare(strict_types=1);

namespace PoP\Root;

use PoP\Root\Container\ContainerBuilderFactory;
use PoP\Root\Container\ContainerInterface;
use PoP\Root\Container\SystemContainerBuilderFactory;
use PoP\Root\Exception\ComponentNotExistsException;
use PoP\Root\HttpFoundation\Request;
use PoP\Root\HttpFoundation\Response;
use PoP\Root\Module\ModuleInterface;
use PoP\Root\StateManagers\AppStateManagerInterface;
use PoP\Root\StateManagers\HookManagerInterface;
use PoP\Root\StateManagers\ModuleManagerInterface;

/**
 * Facade to the current AppThread object that hosts
 * all the top-level instances to run the application.
 *
 * This interface contains all the methods from the
 * AppThreadInterface (to provide access to them)
 * but as static.
 */
class App implements AppInterface
{
    protected static bool $initialized = false;
    protected static AppThreadInterface $appThread;

    /**
     * This function must be invoked at the very beginning,
     * to initialize the instance to run the application.
     *
     * Also it allows to set a new AppThread instance at
     * any time, to initiate a new context.
     */
    public static function setAppThread(AppThreadInterface $appThread): void
    {
        self::$appThread = $appThread;
    }

    /**
     * Allow to get the current AppThread, to store
     * (and put back later) when initiating a new context.
     */
    public static function getAppThread(): AppThreadInterface
    {
        return self::$appThread;
    }

    public static function isInitialized(): bool
    {
        return self::$initialized;
    }

    /**
     * This function must be invoked right after calling
     * `setAppThread` with the new AppThread instance,
     * to initialize it to run the application.
     *
     * Either inject the desired instance, or have the Root
     * provide the default one.
     *
     * It creates a new AppThread and sets it as the current
     * object hosting all state in the application.
     */
    public static function initialize(
        ?AppLoaderInterface $appLoader = null,
        ?HookManagerInterface $hookManager = null,
        ?Request $request = null,
        ?ContainerBuilderFactory $containerBuilderFactory = null,
        ?SystemContainerBuilderFactory $systemContainerBuilderFactory = null,
        ?ModuleManagerInterface $moduleManager = null,
        ?AppStateManagerInterface $appStateManager = null,
    ): void {
        self::$initialized = true;
        self::$appThread->initialize(
            $appLoader,
            $hookManager,
            $request,
            $containerBuilderFactory,
            $systemContainerBuilderFactory,
            $moduleManager,
            $appStateManager,
        );
    }

    public static function setResponse(Response $response): void
    {
        self::$appThread->setResponse($response);
    }

    public static function getAppLoader(): AppLoaderInterface
    {
        return self::$appThread->getAppLoader();
    }

    public static function getHookManager(): HookManagerInterface
    {
        return self::$appThread->getHookManager();
    }

    public static function getRequest(): Request
    {
        return self::$appThread->getRequest();
    }

    public static function getResponse(): Response
    {
        return self::$appThread->getResponse();
    }

    public static function getContainerBuilderFactory(): ContainerBuilderFactory
    {
        return self::$appThread->getContainerBuilderFactory();
    }

    public static function getSystemContainerBuilderFactory(): SystemContainerBuilderFactory
    {
        return self::$appThread->getSystemContainerBuilderFactory();
    }

    public static function getModuleManager(): ModuleManagerInterface
    {
        return self::$appThread->getModuleManager();
    }

    public static function getAppStateManager(): AppStateManagerInterface
    {
        return self::$appThread->getAppStateManager();
    }

    public static function isHTTPRequest(): bool
    {
        return self::$appThread->isHTTPRequest();
    }

    /**
     * Store Module classes to be initialized, and
     * inject them into the AppLoader when this is initialized.
     *
     * @param array<class-string<ModuleInterface>> $moduleClasses List of `Module` class to initialize
     */
    public static function stockAndInitializeModuleClasses(
        array $moduleClasses
    ): void {
        self::$appThread->stockAndInitializeModuleClasses($moduleClasses);
    }

    /**
     * Shortcut function.
     */
    final public static function getContainer(): ContainerInterface
    {
        return self::$appThread->getContainer();
    }

    /**
     * Shortcut function.
     */
    final public static function getSystemContainer(): ContainerInterface
    {
        return self::$appThread->getSystemContainer();
    }

    /**
     * Shortcut function.
     *
     * @phpstan-param class-string<ModuleInterface> $moduleClass
     * @throws ComponentNotExistsException
     */
    final public static function getModule(string $moduleClass): ModuleInterface
    {
        return self::$appThread->getModule($moduleClass);
    }

    /**
     * Shortcut function.
     * @param string|string[] $keyOrPath The property key, or a property path for array values
     */
    final public static function getState(string|array $keyOrPath): mixed
    {
        return self::$appThread->getState($keyOrPath);
    }

    /**
     * Shortcut function.
     * @param string|string[] $keyOrPath The property key, or a property path for array values
     */
    final public static function hasState(string|array $keyOrPath): mixed
    {
        return self::$appThread->hasState($keyOrPath);
    }

    /**
     * Shortcut function.
     */
    final public static function addFilter(string $tag, callable $function_to_add, int $priority = 10, int $accepted_args = 1): void
    {
        self::$appThread->addFilter($tag, $function_to_add, $priority, $accepted_args);
    }
    /**
     * Shortcut function.
     */
    final public static function removeFilter(string $tag, callable $function_to_remove, int $priority = 10): bool
    {
        return self::$appThread->removeFilter($tag, $function_to_remove, $priority);
    }
    /**
     * Shortcut function.
     */
    final public static function applyFilters(string $tag, mixed $value, mixed ...$args): mixed
    {
        return self::$appThread->applyFilters($tag, $value, ...$args);
    }
    /**
     * Shortcut function.
     */
    final public static function addAction(string $tag, callable $function_to_add, int $priority = 10, int $accepted_args = 1): void
    {
        self::$appThread->addAction($tag, $function_to_add, $priority, $accepted_args);
    }
    /**
     * Shortcut function.
     */
    final public static function removeAction(string $tag, callable $function_to_remove, int $priority = 10): bool
    {
        return self::$appThread->removeAction($tag, $function_to_remove, $priority);
    }
    /**
     * Shortcut function.
     */
    final public static function doAction(string $tag, mixed ...$args): void
    {
        self::$appThread->doAction($tag, ...$args);
    }

    /**
     * Shortcut function.
     *
     * Equivalent of $_POST[$key] ?? $default
     */
    final public static function request(string $key, mixed $default = null): mixed
    {
        return self::$appThread->request($key, $default);
    }

    /**
     * Shortcut function.
     *
     * Equivalent of $_GET[$key] ?? $default
     */
    final public static function query(string $key, mixed $default = null): mixed
    {
        return self::$appThread->query($key, $default);
    }

    /**
     * Shortcut function.
     *
     * Equivalent of $_COOKIES[$key] ?? $default
     */
    final public static function cookies(string $key, mixed $default = null): mixed
    {
        return self::$appThread->cookies($key, $default);
    }

    /**
     * Shortcut function.
     *
     * Equivalent of $_FILES[$key] ?? $default
     */
    final public static function files(string $key, mixed $default = null): mixed
    {
        return self::$appThread->files($key, $default);
    }

    /**
     * Shortcut function.
     *
     * Equivalent of $_SERVER[$key] ?? $default
     */
    final public static function server(string $key, mixed $default = null): mixed
    {
        return self::$appThread->server($key, $default);
    }

    /**
     * Shortcut function.
     *
     * Mostly equivalent to a subset of $_SERVER
     */
    final public static function headers(string $key, mixed $default = null): mixed
    {
        return self::$appThread->headers($key, $default);
    }
}
