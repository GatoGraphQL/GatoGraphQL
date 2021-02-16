<?php

declare(strict_types=1);

namespace PoP\Root;

use PoP\Root\Container\ContainerBuilderFactory;
use PoP\Root\Dotenv\DotenvBuilderFactory;
use PoP\Root\Managers\ComponentManager;

/**
 * Component Loader
 */
class AppLoader
{
    /**
     * Has the component been initialized?
     *
     * @var string[]
     */
    protected static $initializedClasses = [];

    /**
     * Initialize the dependency injection containers
     *
     * @param boolean|null $cacheContainerConfiguration Indicate if to cache the container. If null, it gets the value from ENV
     * @param boolean|null $namespace Provide the namespace, to regenerate the cache whenever the application is upgraded. If null, it gets the value from ENV
     * @param boolean|null $directory If null, it will use a system temp folder
     */
    public static function initializeContainers(
        ?bool $cacheContainerConfiguration = null,
        ?string $namespace = null,
        ?string $directory = null
    ): void {
        // Initialize Dotenv (before the ContainerBuilder, since this one uses environment constants)
        DotenvBuilderFactory::init();

        // Initialize the ContainerBuilder
        // Indicate if to cache the container configuration, from configuration if defined, or from the environment
        $cacheContainerConfiguration ??= Environment::cacheContainerConfiguration();

        // Provide a namespace, from configuration if defined, or from the environment
        $namespace ??= Environment::getCacheContainerConfigurationNamespace();

        ContainerBuilderFactory::init(
            $cacheContainerConfiguration,
            $namespace,
            $directory
        );
    }

    /**
     * Initialize the PoP components
     *
     * @param string[] $componentClasses List of `Component` class to initialize
     * @param array<string, mixed> $componentClassConfiguration [key]: Component class, [value]: Configuration
     * @param string[] $skipSchemaComponentClasses List of `Component` class to not initialize
     */
    public static function initializeComponents(
        array $componentClasses,
        array $componentClassConfiguration = [],
        array $skipSchemaComponentClasses = []
    ): void {
        /**
         * Get the list of components, in the order in which they must be initialized
         */
        $orderedComponentClasses = self::getComponentsOrderedForInitialization(
            $componentClasses
        );

        /**
         * Allow each component to customize the configuration for itself,
         * and for its depended-upon components.
         * Hence this is executed from bottom to top
         */
        foreach (array_reverse($orderedComponentClasses) as $componentClass) {
            $componentClass::customizeComponentClassConfiguration($componentClassConfiguration);
        }

        /**
         * Initialize the components
         */
        foreach ($orderedComponentClasses as $componentClass) {
            // Temporary solution until migrated:
            // Initialize all depended-upon migration plugins
            foreach ($componentClass::getDependedMigrationPlugins() as $migrationPluginPath) {
                require_once $migrationPluginPath;
            }

            // Initialize the component, passing its configuration, and checking if its schema must be skipped
            $componentConfiguration = $componentClassConfiguration[$componentClass] ?? [];
            $skipSchemaForComponent = in_array($componentClass, $skipSchemaComponentClasses);
            $componentClass::initialize(
                $componentConfiguration,
                $skipSchemaForComponent,
                $skipSchemaComponentClasses
            );
        }
    }

    /**
     * Get the array of components ordered by how they must be initialized,
     * following the Composer dependencies tree
     *
     * @param string[] $componentClasses List of `Component` class to initialize
     * @return string[]
     */
    protected static function getComponentsOrderedForInitialization(
        array $componentClasses
    ): array {
        $orderedComponentClasses = [];
        self::addComponentsOrderedForInitialization(
            $componentClasses,
            $orderedComponentClasses
        );
        return $orderedComponentClasses;
    }

    /**
     * Get the array of components ordered by how they must be initialized,
     * following the Composer dependencies tree
     *
     * @param string[] $componentClasses List of `Component` class to initialize
     * @param string[] $orderedComponentClasses List of `Component` class in order of initialization
     */
    protected static function addComponentsOrderedForInitialization(
        array $componentClasses,
        array &$orderedComponentClasses
    ): void {
        /**
         * If any component class has already been initialized,
         * then do nothing
         */
        $componentClasses = array_values(array_diff(
            $componentClasses,
            self::$initializedClasses
        ));
        foreach ($componentClasses as $componentClass) {
            self::$initializedClasses[] = $componentClass;

            // Initialize all depended-upon PoP components
            self::addComponentsOrderedForInitialization(
                $componentClass::getDependedComponentClasses(),
                $orderedComponentClasses
            );

            // Initialize all depended-upon PoP conditional components, if they are installed
            self::addComponentsOrderedForInitialization(
                array_filter(
                    $componentClass::getDependedConditionalComponentClasses(),
                    'class_exists'
                ),
                $orderedComponentClasses
            );

            // We reached the bottom of the rung, add the component to the list
            $orderedComponentClasses[] = $componentClass;
        }
    }

    public static function bootApplication(): void
    {
        self::bootContainers();

        // Boot all the components
        self::bootComponents();
    }

    protected static function bootContainers(): void
    {
        // Collect the compiler pass classes from all components
        $compilerPassClasses = [];
        foreach (ComponentManager::getComponentClasses() as $componentClass) {
            $compilerPassClasses = [
                ...$compilerPassClasses,
                ...$componentClass::getContainerCompilerPassClasses()
            ];
        }
        $compilerPassClasses = array_values(array_unique($compilerPassClasses));

        // Compile and Cache Symfony's DependencyInjection Container Builder
        ContainerBuilderFactory::maybeCompileAndCacheContainer($compilerPassClasses);
    }

    protected static function bootComponents(): void
    {
        ComponentManager::beforeBoot();
        ComponentManager::boot();
        ComponentManager::afterBoot();
    }
}
