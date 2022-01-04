<?php

declare(strict_types=1);

namespace PoP\Root;

use PoP\Root\Container\ContainerBuilderFactory;
use PoP\Root\Container\SystemContainerBuilderFactory;
use PoP\Root\Dotenv\DotenvBuilderFactory;
use PoP\Root\Facades\SystemCompilerPassRegistryFacade;
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
    protected static array $initializedComponentClasses = [];
    /**
     * Component in their initialization order
     *
     * @var string[]
     */
    protected static array $orderedComponentClasses = [];
    /**
     * Component classes to be initialized
     *
     * @var string[]
     */
    protected static array $componentClassesToInitialize = [];
    /**
     * [key]: Component class, [value]: Configuration
     *
     * @var array<string, array<string, mixed>>
     */
    protected static array $componentClassConfiguration = [];
    /**
     * List of `Component` class which must not initialize their Schema services
     *
     * @var string[]
     */
    protected static array $skipSchemaComponentClasses = [];

    /**
     * This functions is to be called by PHPUnit,
     * to reset the state in between tests.
     *
     * Reset the state of the Application.
     */
    public static function reset(): void
    {
        self::$initializedComponentClasses = [];
        self::$orderedComponentClasses = [];
        self::$componentClassesToInitialize = [];
        self::$componentClassConfiguration = [];
        self::$skipSchemaComponentClasses = [];
        
        ContainerBuilderFactory::reset();
        SystemContainerBuilderFactory::reset();
        ComponentManager::reset();
    }

    /**
     * Add Component classes to be initialized
     *
     * @param string[] $componentClasses List of `Component` class to initialize
     */
    public static function addComponentClassesToInitialize(
        array $componentClasses
    ): void {
        self::$componentClassesToInitialize = array_merge(
            self::$componentClassesToInitialize,
            $componentClasses
        );
    }

    /**
     * Add configuration for the Component classes
     *
     * @param array<string, array<string, mixed>> $componentClassConfiguration [key]: Component class, [value]: Configuration
     */
    public static function addComponentClassConfiguration(
        array $componentClassConfiguration = []
    ): void {
        // Allow to override entries under each Component
        foreach ($componentClassConfiguration as $componentClass => $componentConfiguration) {
            self::$componentClassConfiguration[$componentClass] ??= [];
            self::$componentClassConfiguration[$componentClass] = array_merge(
                self::$componentClassConfiguration[$componentClass],
                $componentConfiguration
            );
        }
    }

    /**
     * Add schema Component classes to skip initializing
     *
     * @param string[] $skipSchemaComponentClasses List of `Component` class which must not initialize their Schema services
     */
    public static function addSchemaComponentClassesToSkip(
        array $skipSchemaComponentClasses = []
    ): void {
        self::$skipSchemaComponentClasses = array_merge(
            self::$skipSchemaComponentClasses,
            $skipSchemaComponentClasses
        );
    }

    /**
     * Get the array of components ordered by how they must be initialized,
     * following the Composer dependencies tree
     */
    private static function initializeComponents(bool $isDev): void
    {
        self::addComponentsOrderedForInitialization(
            self::$componentClassesToInitialize,
            $isDev
        );
    }

    /**
     * Get the array of components ordered by how they must be initialized,
     * following the Composer dependencies tree
     *
     * @param string[] $componentClasses List of `Component` class to initialize
     */
    private static function addComponentsOrderedForInitialization(
        array $componentClasses,
        bool $isDev
    ): void {
        /**
         * If any component class has already been initialized,
         * then do nothing
         */
        $componentClasses = array_values(array_diff(
            $componentClasses,
            self::$initializedComponentClasses
        ));
        foreach ($componentClasses as $componentClass) {
            self::$initializedComponentClasses[] = $componentClass;

            // Initialize and register the Component
            $component = ComponentManager::register($componentClass);

            // Initialize all depended-upon PoP components
            self::addComponentsOrderedForInitialization(
                $component->getDependedComponentClasses(),
                $isDev
            );

            if ($isDev) {
                self::addComponentsOrderedForInitialization(
                    $component->getDevDependedComponentClasses(),
                    $isDev
                );
            }

            // Initialize all depended-upon PoP conditional components, if they are installed
            self::addComponentsOrderedForInitialization(
                array_filter(
                    $component->getDependedConditionalComponentClasses(),
                    'class_exists'
                ),
                $isDev
            );

            // We reached the bottom of the rung, add the component to the list
            self::$orderedComponentClasses[] = $componentClass;
        }
    }

    /**
     * Boot the application. It does these steps:
     *
     * 1. Initialize Symfony's Dotenv component (to get config from ENV)
     * 2. Calculate in what order will the Components (including from main Plugin and Extensions) will be initialized (based on their Composer dependency order)
     * 3. Allow Components to customize the component configuration for themselves, and the components they can see
     * 4. Register all Components with the ComponentManager
     * 5. Initialize the System Container, have all Components inject services, and compile it, making "system" services (eg: hooks, translation) available for initializing Application Container services
     *
     * @param boolean|null $cacheContainerConfiguration Indicate if to cache the container. If null, it gets the value from ENV
     * @param string|null $containerNamespace Provide the namespace, to regenerate the cache whenever the application is upgraded. If null, it gets the value from ENV
     * @param string|null $containerDirectory Provide the directory, to regenerate the cache whenever the application is upgraded. If null, it uses the default /tmp folder by the OS
     * @param boolean $isDev Indicate if testing with PHPUnit, as to load components only for DEV
     */
    public static function bootSystem(
        ?bool $cacheContainerConfiguration = null,
        ?string $containerNamespace = null,
        ?string $containerDirectory = null,
        bool $isDev = false
    ): void {
        // Initialize Dotenv (before the ContainerBuilder, since this one uses environment constants)
        DotenvBuilderFactory::init();

        /**
         * Calculate the components in their initialization order
         */
        self::initializeComponents($isDev);

        /**
         * System container: initialize it and compile it already,
         * since it will be used to initialize the Application container
         */
        SystemContainerBuilderFactory::init(
            $cacheContainerConfiguration,
            $containerNamespace,
            $containerDirectory
        );

        /**
         * Have all Components register their Container services,
         * and already compile the container.
         * This way, these services become available for initializing
         * Application Container services.
         */
        foreach (self::$orderedComponentClasses as $componentClass) {
            $component = ComponentManager::getComponent($componentClass);
            $component->initializeSystem();
        }
        $systemCompilerPasses = array_map(
            fn ($class) => new $class(),
            self::getSystemContainerCompilerPasses()
        );
        SystemContainerBuilderFactory::maybeCompileAndCacheContainer($systemCompilerPasses);

        // Finally boot the components
        static::bootSystemForComponents();
    }

    /**
     * Trigger "beforeBoot", "boot" and "afterBoot" events on all the Components,
     * for them to execute any custom extra logic
     */
    protected static function bootSystemForComponents(): void
    {
        ComponentManager::bootSystem();
    }

    /**
     * @return string[]
     */
    final protected static function getSystemContainerCompilerPasses(): array
    {
        // Collect the compiler pass classes from all components
        $compilerPassClasses = [];
        foreach (self::$orderedComponentClasses as $componentClass) {
            $component = ComponentManager::getComponent($componentClass);
            $compilerPassClasses = [
                ...$compilerPassClasses,
                ...$component->getSystemContainerCompilerPassClasses()
            ];
        }
        return array_values(array_unique($compilerPassClasses));
    }

    /**
     * Boot the application. It does these steps:
     *
     * 1. Initialize the Application Container, have all Components inject services, and compile it
     * 2. Trigger "beforeBoot", "boot" and "afterBoot" events on all the Components, for them to execute any custom extra logic
     *
     * @param boolean|null $cacheContainerConfiguration Indicate if to cache the container. If null, it gets the value from ENV
     * @param string|null $containerNamespace Provide the namespace, to regenerate the cache whenever the application is upgraded. If null, it gets the value from ENV
     * @param string|null $containerDirectory Provide the directory, to regenerate the cache whenever the application is upgraded. If null, it uses the default /tmp folder by the OS
     */
    public static function bootApplication(
        ?bool $cacheContainerConfiguration = null,
        ?string $containerNamespace = null,
        ?string $containerDirectory = null
    ): void {
        /**
         * Allow each component to customize the configuration for itself,
         * and for its depended-upon components.
         * Hence this is executed from bottom to top
         */
        foreach (array_reverse(self::$orderedComponentClasses) as $componentClass) {
            $component = ComponentManager::getComponent($componentClass);
            $component->customizeComponentClassConfiguration(self::$componentClassConfiguration);
        }

        /**
         * Initialize the Application container only
         */
        ContainerBuilderFactory::init(
            $cacheContainerConfiguration,
            $containerNamespace,
            $containerDirectory
        );

        /**
         * Initialize the container services by the Components
         */
        foreach (self::$orderedComponentClasses as $componentClass) {
            // Initialize the component, passing its configuration, and checking if its schema must be skipped
            $component = ComponentManager::getComponent($componentClass);
            $componentConfiguration = self::$componentClassConfiguration[$componentClass] ?? [];
            $skipSchemaForComponent = in_array($componentClass, self::$skipSchemaComponentClasses);
            $component->initialize(
                $componentConfiguration,
                $skipSchemaForComponent,
                self::$skipSchemaComponentClasses
            );
        }

        // Register CompilerPasses, Compile and Cache
        // Symfony's DependencyInjection Application Container
        $systemCompilerPassRegistry = SystemCompilerPassRegistryFacade::getInstance();
        $systemCompilerPasses = $systemCompilerPassRegistry->getCompilerPasses();
        ContainerBuilderFactory::maybeCompileAndCacheContainer($systemCompilerPasses);

        // Finally boot the components
        static::bootApplicationForComponents();
    }

    /**
     * Trigger "beforeBoot", "boot" and "afterBoot" events on all the Components,
     * for them to execute any custom extra logic
     */
    protected static function bootApplicationForComponents(): void
    {
        ComponentManager::beforeBoot();
        ComponentManager::boot();
        ComponentManager::afterBoot();
    }
}
