<?php

declare(strict_types=1);

namespace PoP\Root;

use PoP\Root\Module\ModuleInterface;
use PoP\Root\Container\ContainerBuilderFactory;
use PoP\Root\Container\ContainerInterface;
use PoP\Root\Container\SystemContainerBuilderFactory;
use PoP\Root\Exception\ComponentNotExistsException;
use PoP\Root\HttpFoundation\Request;
use PoP\Root\HttpFoundation\Response;
use PoP\Root\StateManagers\AppStateManagerInterface;
use PoP\Root\StateManagers\ModuleManagerInterface;
use PoP\Root\StateManagers\HookManagerInterface;

/**
 * Single class hosting all the top-level instances
 * to run the application. Only a single AppThread
 * will be active at a single time, and its state
 * will be accessed/modified by the whole application.
 * Access the current AppThread via the corresponding
 * methods in the `App` facade class.
 *
 * It keeps all state in the application stored and
 * accessible in a single place, so that regenerating
 * this class provides a new state.
 *
 * Needed for PHPUnit.
 */
interface AppThreadInterface
{
    public function getName(): ?string;

    /**
     * Store properties for identifying across different
     * INTERNAL GraphQL servers, by storing the
     * persisted query for each in the context.
     *
     * @return array<string,mixed>
     */
    public function getContext(): array;

    /**
     * This function must be invoked at the very beginning,
     * to initialize the instance to run the application.
     *
     * Either inject the desired instance, or have the Root
     * provide the default one.
     */
    public function initialize(
        ?AppLoaderInterface $appLoader = null,
        ?HookManagerInterface $hookManager = null,
        ?Request $request = null,
        ?ContainerBuilderFactory $containerBuilderFactory = null,
        ?SystemContainerBuilderFactory $systemContainerBuilderFactory = null,
        ?ModuleManagerInterface $moduleManager = null,
        ?AppStateManagerInterface $appStateManager = null,
    ): void;

    public function setResponse(Response $response): void;

    public function getAppLoader(): AppLoaderInterface;

    public function getHookManager(): HookManagerInterface;

    public function getRequest(): Request;

    public function getResponse(): Response;

    public function getContainerBuilderFactory(): ContainerBuilderFactory;

    public function getSystemContainerBuilderFactory(): SystemContainerBuilderFactory;

    public function getModuleManager(): ModuleManagerInterface;

    public function getAppStateManager(): AppStateManagerInterface;

    public function isHTTPRequest(): bool;

    /**
     * Store Module classes to be initialized, and
     * inject them into the AppLoader when this is initialized.
     *
     * @param array<class-string<ModuleInterface>> $moduleClasses List of `Module` class to initialize
     */
    public function stockAndInitializeModuleClasses(
        array $moduleClasses
    ): void;

    /**
     * Shortcut function.
     */
    public function getContainer(): ContainerInterface;

    /**
     * Shortcut function.
     */
    public function getSystemContainer(): ContainerInterface;

    /**
     * Shortcut function.
     *
     * @phpstan-param class-string<ModuleInterface> $moduleClass
     * @throws ComponentNotExistsException
     */
    public function getModule(string $moduleClass): ModuleInterface;

    /**
     * Shortcut function.
     * @param string|string[] $keyOrPath The property key, or a property path for array values
     */
    public function getState(string|array $keyOrPath): mixed;

    /**
     * Shortcut function.
     * @param string|string[] $keyOrPath The property key, or a property path for array values
     */
    public function hasState(string|array $keyOrPath): mixed;

    /**
     * Shortcut function.
     */
    public function addFilter(string $tag, callable $function_to_add, int $priority = 10, int $accepted_args = 1): void;

    /**
     * Shortcut function.
     */
    public function removeFilter(string $tag, callable $function_to_remove, int $priority = 10): bool;

    /**
     * Shortcut function.
     */
    public function applyFilters(string $tag, mixed $value, mixed ...$args): mixed;

    /**
     * Shortcut function.
     */
    public function addAction(string $tag, callable $function_to_add, int $priority = 10, int $accepted_args = 1): void;

    /**
     * Shortcut function.
     */
    public function removeAction(string $tag, callable $function_to_remove, int $priority = 10): bool;

    /**
     * Shortcut function.
     */
    public function doAction(string $tag, mixed ...$args): void;

    /**
     * Shortcut function.
     *
     * Equivalent of $_POST[$key] ?? $default
     */
    public function request(string $key, mixed $default = null): mixed;

    /**
     * Shortcut function.
     *
     * Equivalent of $_GET[$key] ?? $default
     */
    public function query(string $key, mixed $default = null): mixed;

    /**
     * Shortcut function.
     *
     * Equivalent of $_COOKIES[$key] ?? $default
     */
    public function cookies(string $key, mixed $default = null): mixed;

    /**
     * Shortcut function.
     *
     * Equivalent of $_FILES[$key] ?? $default
     */
    public function files(string $key, mixed $default = null): mixed;

    /**
     * Shortcut function.
     *
     * Equivalent of $_SERVER[$key] ?? $default
     */
    public function server(string $key, mixed $default = null): mixed;

    /**
     * Shortcut function.
     *
     * Mostly equivalent to a subset of $_SERVER
     */
    public function headers(string $key, mixed $default = null): mixed;
}
