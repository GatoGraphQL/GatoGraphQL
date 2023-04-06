<?php

declare(strict_types=1);

namespace PoP\Root;

use PoP\Root\Container\ContainerCacheConfiguration;
use PoP\Root\Module\ModuleInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

interface AppLoaderInterface
{
    /**
     * Add Module classes to be initialized
     *
     * @param array<class-string<ModuleInterface>> $moduleClasses List of `Module` class to initialize
     */
    public function addModuleClassesToInitialize(
        array $moduleClasses
    ): void;

    /**
     * Get the Module classes to be initialized.
     *
     * Call this method after the GraphQL server is in ready state,
     * to signify "Module classes already initialized"
     *
     * @return array<class-string<ModuleInterface>> $moduleClasses List of `Module` class to initialize
     */
    public function getModuleClassesToInitialize(): array;

    /**
     * Define that the application is ready to be used
     */
    public function setReadyState(bool $readyState): void;

    /**
     * Indicate if the application is ready to be used
     */
    public function isReadyState(): bool;

    /**
     * Add configuration for the Module classes
     *
     * @param array<string,array<string,mixed>> $moduleClassConfiguration [key]: Module class, [value]: Configuration
     * @phpstan-param array<class-string<ModuleInterface>,array<string,mixed>> $moduleClassConfiguration
     */
    public function addModuleClassConfiguration(
        array $moduleClassConfiguration
    ): void;

    /**
     * Get configuration for the Module classes
     *
     * @return array<class-string<ModuleInterface>,array<string,mixed>> $moduleClassConfiguration [key]: Module class, [value]: Configuration
     */
    public function getModuleClassConfiguration(): array;

    /**
     * Inject Compiler Pass classes (eg: for testing)
     *
     * @param array<class-string<CompilerPassInterface>> $systemContainerCompilerPassClasses List of `CompilerPass` class to initialize
     */
    public function addSystemContainerCompilerPassClasses(
        array $systemContainerCompilerPassClasses
    ): void;

    /**
     * Get the Compiler Passes to boot the System (eg: when testing)
     *
     * @return array<class-string<CompilerPassInterface>> List of `CompilerPass` class to initialize
     */
    public function getSystemContainerCompilerPassClasses(): array;

    /**
     * Inject Compiler Pass classes (eg: for testing)
     *
     * @param array<class-string<CompilerPassInterface>> $applicationContainerCompilerPassClasses List of `CompilerPass` class to initialize
     */
    public function addApplicationContainerCompilerPassClasses(
        array $applicationContainerCompilerPassClasses
    ): void;

    /**
     * Get the Compiler Passes to boot the Application (eg: when testing)
     *
     * @return array<class-string<CompilerPassInterface>> List of `CompilerPass` class to initialize
     */
    public function getApplicationContainerCompilerPassClasses(): array;

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

    public function setContainerCacheConfiguration(
        ?ContainerCacheConfiguration $containerCacheConfiguration = null,
    ): void;

    public function getContainerCacheConfiguration(): ?ContainerCacheConfiguration;

    /**
     * Boot the application. It does these steps:
     *
     * 1. Initialize Symfony's Dotenv component (to get config from ENV)
     * 2. Calculate in what order will the Components (including from main Plugin and Extensions) will be initialized (based on their Composer dependency order)
     * 3. Allow Components to customize the component configuration for themselves, and the components they can see
     * 4. Register all Components with the ModuleManager
     * 5. Initialize the System Container, have all Components inject services, and compile it, making "system" services (eg: hooks, translation) available for initializing Application Container services
     */
    public function bootSystem(): void;

    /**
     * Boot the application. It does these steps:
     *
     * 1. Initialize the Application Container, have all Components inject services, and compile it
     * 2. Trigger "moduleLoaded", "boot" and "afterBoot" events on all the Components, for them to execute any custom extra logic
     */
    public function bootApplication(): void;

    /**
     * Trigger "moduleLoaded", "boot" and "afterBoot" events on all the Components,
     * for them to execute any custom extra logic.
     */
    public function bootApplicationModules(): void;

    public function skipSchemaForModule(ModuleInterface $module): bool;
}
