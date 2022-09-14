<?php

declare(strict_types=1);

namespace PoP\Root;

use PoP\Root\Module\ModuleInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

interface AppLoaderInterface
{
    /**
     * Add Module classes to be initialized
     *
     * @param string[] $moduleClasses List of `Module` class to initialize
     */
    public function addModuleClassesToInitialize(
        array $moduleClasses
    ): void;

    /**
     * Add configuration for the Module classes
     *
     * @param array<string,array<string,mixed>> $moduleClassConfiguration [key]: Module class, [value]: Configuration
     */
    public function addModuleClassConfiguration(
        array $moduleClassConfiguration
    ): void;

    /**
     * Inject Compiler Pass classes (eg: for testing)
     *
     * @param array<class-string<CompilerPassInterface>> $systemContainerCompilerPassClasses List of `CompilerPass` class to initialize
     */
    public function addSystemContainerCompilerPassClasses(
        array $systemContainerCompilerPassClasses
    ): void;

    /**
     * Inject Compiler Pass classes (eg: for testing)
     *
     * @param array<class-string<CompilerPassInterface>> $applicationContainerCompilerPassClasses List of `CompilerPass` class to initialize
     */
    public function addApplicationContainerCompilerPassClasses(
        array $applicationContainerCompilerPassClasses
    ): void;

    /**
     * Set the initial state, eg: when passing state via the request is disabled
     *
     * @param array<string,mixed> $initialAppState
     */
    public function setInitialAppState(array $initialAppState): void;

    /**
     * Merge some initial state
     *
     * @param array<string,mixed> $initialAppState
     */
    public function mergeInitialAppState(array $initialAppState): void;

    /**
     * Add schema Module classes to skip initializing
     *
     * @param array<class-string<ModuleInterface>> $skipSchemaModuleClasses List of `Module` class which must not initialize their Schema services
     */
    public function addSchemaModuleClassesToSkip(
        array $skipSchemaModuleClasses
    ): void;

    /**
     * Get the array of components ordered by how they must be initialized,
     * following the Composer dependencies tree
     *
     * @param boolean $isDev Indicate if testing with PHPUnit, as to load components only for DEV
     */
    public function initializeModules(
        bool $isDev = false
    ): void;

    /**
     * Boot the application. It does these steps:
     *
     * 1. Initialize Symfony's Dotenv component (to get config from ENV)
     * 2. Calculate in what order will the Components (including from main Plugin and Extensions) will be initialized (based on their Composer dependency order)
     * 3. Allow Components to customize the component configuration for themselves, and the components they can see
     * 4. Register all Components with the ModuleManager
     * 5. Initialize the System Container, have all Components inject services, and compile it, making "system" services (eg: hooks, translation) available for initializing Application Container services
     *
     * @param boolean|null $cacheContainerConfiguration Indicate if to cache the container. If null, it gets the value from ENV
     * @param string|null $containerNamespace Provide the namespace, to regenerate the cache whenever the application is upgraded. If null, it gets the value from ENV
     * @param string|null $containerDirectory Provide the directory, to regenerate the cache whenever the application is upgraded. If null, it uses the default /tmp folder by the OS
     */
    public function bootSystem(
        ?bool $cacheContainerConfiguration = null,
        ?string $containerNamespace = null,
        ?string $containerDirectory = null,
    ): void;

    /**
     * Boot the application. It does these steps:
     *
     * 1. Initialize the Application Container, have all Components inject services, and compile it
     * 2. Trigger "moduleLoaded", "boot" and "afterBoot" events on all the Components, for them to execute any custom extra logic
     *
     * @param boolean|null $cacheContainerConfiguration Indicate if to cache the container. If null, it gets the value from ENV
     * @param string|null $containerNamespace Provide the namespace, to regenerate the cache whenever the application is upgraded. If null, it gets the value from ENV
     * @param string|null $containerDirectory Provide the directory, to regenerate the cache whenever the application is upgraded. If null, it uses the default /tmp folder by the OS
     */
    public function bootApplication(
        ?bool $cacheContainerConfiguration = null,
        ?string $containerNamespace = null,
        ?string $containerDirectory = null
    ): void;

    /**
     * Trigger "moduleLoaded", "boot" and "afterBoot" events on all the Components,
     * for them to execute any custom extra logic.
     */
    public function bootApplicationModules(): void;

    public function skipSchemaForModule(ModuleInterface $module): bool;
}
