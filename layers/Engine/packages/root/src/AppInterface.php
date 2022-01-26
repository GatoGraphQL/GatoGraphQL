<?php

declare(strict_types=1);

namespace PoP\Root;

use LogicException;
use PoP\Root\Component\ComponentInterface;
use PoP\Root\Container\ContainerBuilderFactory;
use PoP\Root\Container\ContainerInterface;
use PoP\Root\Container\SystemContainerBuilderFactory;
use PoP\Root\HttpFoundation\Request;
use PoP\Root\HttpFoundation\Response;
use PoP\Root\StateManagers\AppStateManagerInterface;
use PoP\Root\StateManagers\ComponentManagerInterface;
use PoP\Root\StateManagers\HookManagerInterface;
use PoP\Root\Stores\MutationResolutionStore;

/**
 * Single class hosting all the top-level instances to run the application
 */
interface AppInterface
{
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
    ): void;

    public static function regenerateResponse(): void;

    public static function getAppLoader(): AppLoaderInterface;

    public static function getHookManager(): HookManagerInterface;

    public static function getRequest(): Request;

    public static function getResponse(): Response;

    public static function getContainerBuilderFactory(): ContainerBuilderFactory;

    public static function getSystemContainerBuilderFactory(): SystemContainerBuilderFactory;

    public static function getComponentManager(): ComponentManagerInterface;

    public static function getMutationResolutionStore(): MutationResolutionStore;

    /**
     * Store Component classes to be initialized, and
     * inject them into the AppLoader when this is initialized.
     *
     * @param string[] $componentClasses List of `Component` class to initialize
     */
    public static function stockAndInitializeComponentClasses(
        array $componentClasses
    ): void;

    /**
     * Shortcut function.
     */
    public static function getContainer(): ContainerInterface;

    /**
     * Shortcut function.
     */
    public static function getSystemContainer(): ContainerInterface;

    /**
     * Shortcut function.
     *
     * @throws LogicException
     */
    public static function getComponent(string $componentClass): ComponentInterface;

    /**
     * Shortcut function.
     * @param string|string[] $keyOrPath The property key, or a property path for array values
     */
    public static function getState(string|array $keyOrPath): mixed;

    /**
     * Shortcut function.
     * @param string|string[] $keyOrPath The property key, or a property path for array values
     */
    public static function hasState(string|array $keyOrPath): mixed;

    /**
     * Shortcut function.
     */
    public static function addFilter(string $tag, callable $function_to_add, int $priority = 10, int $accepted_args = 1): void;

    /**
     * Shortcut function.
     */
    public static function removeFilter(string $tag, callable $function_to_remove, int $priority = 10): bool;

    /**
     * Shortcut function.
     */
    public static function applyFilters(string $tag, mixed $value, mixed ...$args): mixed;

    /**
     * Shortcut function.
     */
    public static function addAction(string $tag, callable $function_to_add, int $priority = 10, int $accepted_args = 1): void;

    /**
     * Shortcut function.
     */
    public static function removeAction(string $tag, callable $function_to_remove, int $priority = 10): bool;

    /**
     * Shortcut function.
     */
    public static function doAction(string $tag, mixed ...$args): void;

    /**
     * Shortcut function.
     *
     * Equivalent of $_POST[$key] ?? $default
     */
    public static function request(string $key, mixed $default = null): mixed;

    /**
     * Shortcut function.
     *
     * Equivalent of $_GET[$key] ?? $default
     */
    public static function query(string $key, mixed $default = null): mixed;

    /**
     * Shortcut function.
     *
     * Equivalent of $_COOKIES[$key] ?? $default
     */
    public static function cookies(string $key, mixed $default = null): mixed;

    /**
     * Shortcut function.
     *
     * Equivalent of $_FILES[$key] ?? $default
     */
    public static function files(string $key, mixed $default = null): mixed;

    /**
     * Shortcut function.
     *
     * Equivalent of $_SERVER[$key] ?? $default
     */
    public static function server(string $key, mixed $default = null): mixed;

    /**
     * Shortcut function.
     *
     * Mostly equivalent to a subset of $_SERVER
     */
    public static function headers(string $key, mixed $default = null): mixed;
}
