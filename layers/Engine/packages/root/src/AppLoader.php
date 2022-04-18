<?php

declare(strict_types=1);

namespace PoP\Root;

use PoP\Root\Component\ComponentInterface;
use PoP\Root\Constants\HookNames;
use PoP\Root\Dotenv\DotenvBuilderFactory;
use PoP\Root\Facades\SystemCompilerPassRegistryFacade;

/**
 * Application Loader
 */
class AppLoader implements AppLoaderInterface
{
    /**
     * Has the component been initialized?
     *
     * @var string[]
     */
    protected array $initializedComponentClasses = [];
    /**
     * Component in their initialization order
     *
     * @var string[]
     */
    protected array $orderedComponentClasses = [];
    /**
     * Component classes to be initialized
     *
     * @var string[]
     */
    protected array $componentClassesToInitialize = [];
    /**
     * [key]: Component class, [value]: Configuration
     *
     * @var array<string, array<string, mixed>>
     */
    protected array $componentClassConfiguration = [];
    /**
     * [key]: State key, [value]: Value
     *
     * @var array<string,mixed>
     */
    protected array $initialAppState = [];
    /**
     * List of `Component` class which must not initialize their Schema services
     *
     * @var string[]
     */
    protected array $skipSchemaComponentClasses = [];
    /**
     * Cache if a component must skipSchema or not, stored under its class
     *
     * @var array<string,bool>
     */
    protected array $skipSchemaForComponentCache = [];

    /**
     * Add Component classes to be initialized
     *
     * @param string[] $componentClasses List of `Component` class to initialize
     */
    public function addComponentClassesToInitialize(
        array $componentClasses
    ): void {
        $this->componentClassesToInitialize = array_merge(
            $this->componentClassesToInitialize,
            $componentClasses
        );
    }

    /**
     * Add configuration for the Component classes
     *
     * @param array<string, array<string, mixed>> $componentClassConfiguration [key]: Component class, [value]: Configuration
     */
    public function addComponentClassConfiguration(
        array $componentClassConfiguration
    ): void {
        // Allow to override entries under each Component
        foreach ($componentClassConfiguration as $componentClass => $componentConfiguration) {
            $this->componentClassConfiguration[$componentClass] ??= [];
            $this->componentClassConfiguration[$componentClass] = array_merge(
                $this->componentClassConfiguration[$componentClass],
                $componentConfiguration
            );
        }
    }

    /**
     * Set the initial state, eg: when passing state via the request is disabled
     *
     * @param array<string,mixed> $initialAppState
     */
    public function setInitialAppState(array $initialAppState): void
    {
        $this->initialAppState = $initialAppState;
    }

    /**
     * Merge some initial state
     *
     * @param array<string,mixed> $initialAppState
     */
    public function mergeInitialAppState(array $initialAppState): void
    {
        $this->initialAppState = array_merge(
            $this->initialAppState,
            $initialAppState
        );
    }

    /**
     * Add schema Component classes to skip initializing
     *
     * @param string[] $skipSchemaComponentClasses List of `Component` class which must not initialize their Schema services
     */
    public function addSchemaComponentClassesToSkip(
        array $skipSchemaComponentClasses
    ): void {
        $this->skipSchemaComponentClasses = array_merge(
            $this->skipSchemaComponentClasses,
            $skipSchemaComponentClasses
        );
    }

    /**
     * Get the array of components ordered by how they must be initialized,
     * following the Composer dependencies tree
     *
     * @param string[] $componentClasses List of `Component` class to initialize
     */
    private function addComponentsOrderedForInitialization(
        array $componentClasses,
        bool $isDev
    ): void {
        /**
         * If any component class has already been initialized,
         * then do nothing
         */
        $componentClasses = array_values(array_diff(
            $componentClasses,
            $this->initializedComponentClasses
        ));
        $componentManager = App::getComponentManager();
        foreach ($componentClasses as $componentClass) {
            $this->initializedComponentClasses[] = $componentClass;

            // Initialize and register the Component
            $component = $componentManager->register($componentClass);

            /**
             * If this compononent satisfies the contracts for other
             * components, set them as "satisfied". Since they will also
             * be dependencies, they must've been already registered.
             */
            foreach ($component->getSatisfiedComponentClasses() as $satisfiedComponentClass) {
                $satisfiedComponent = App::getComponent($satisfiedComponentClass);
                $satisfiedComponent->setHasSatisfyingComponent();
            }

            // Initialize all depended-upon PoP components
            $this->addComponentsOrderedForInitialization(
                $component->getDependedComponentClasses(),
                $isDev
            );

            if ($isDev) {
                $this->addComponentsOrderedForInitialization(
                    $component->getDevDependedComponentClasses(),
                    $isDev
                );
                if (Environment::isApplicationEnvironmentDevPHPUnit()) {
                    $this->addComponentsOrderedForInitialization(
                        $component->getDevPHPUnitDependedComponentClasses(),
                        $isDev
                    );
                }
            }

            // Initialize all depended-upon PoP conditional components, if they are installed
            $this->addComponentsOrderedForInitialization(
                array_filter(
                    $component->getDependedConditionalComponentClasses(),
                    class_exists(...)
                ),
                $isDev
            );

            // We reached the bottom of the rung, add the component to the list
            $this->orderedComponentClasses[] = $componentClass;
        }
    }

    /**
     * Get the array of components ordered by how they must be initialized,
     * following the Composer dependencies tree
     *
     * @param boolean $isDev Indicate if testing with PHPUnit, as to load components only for DEV
     */
    public function initializeComponents(
        bool $isDev = false
    ): void {
        // Initialize Dotenv (before the ContainerBuilder, since this one uses environment constants)
        DotenvBuilderFactory::init();

        /**
         * Calculate the components in their initialization order
         */
        $this->addComponentsOrderedForInitialization(
            $this->componentClassesToInitialize,
            $isDev
        );

        /**
         * After initialized, and before booting,
         * allow the components to inject their own configuration
         */
        $this->configureComponents();
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
     */
    public function bootSystem(
        ?bool $cacheContainerConfiguration = null,
        ?string $containerNamespace = null,
        ?string $containerDirectory = null,
    ): void {
        /**
         * System container: initialize it and compile it already,
         * since it will be used to initialize the Application container
         */
        App::getSystemContainerBuilderFactory()->init(
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
        foreach ($this->orderedComponentClasses as $componentClass) {
            $component = App::getComponent($componentClass);
            if (!$component->isEnabled()) {
                continue;
            }
            $component->initializeSystem();
        }
        $systemCompilerPasses = array_map(
            fn ($class) => new $class(),
            $this->getSystemContainerCompilerPasses()
        );
        App::getSystemContainerBuilderFactory()->maybeCompileAndCacheContainer($systemCompilerPasses);

        // Finally boot the components
        $this->bootSystemComponents();
    }

    /**
     * Trigger after initializing all components,
     * and before booting the system
     */
    protected function configureComponents(): void
    {
        App::getComponentManager()->configureComponents();
    }

    /**
     * Trigger "componentLoaded", "boot" and "afterBoot" events on all the Components,
     * for them to execute any custom extra logic
     */
    protected function bootSystemComponents(): void
    {
        App::getComponentManager()->bootSystem();
    }

    /**
     * @return string[]
     */
    final protected function getSystemContainerCompilerPasses(): array
    {
        // Collect the compiler pass classes from all components
        $compilerPassClasses = [];
        foreach ($this->orderedComponentClasses as $componentClass) {
            $component = App::getComponent($componentClass);
            if (!$component->isEnabled()) {
                continue;
            }
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
     * 2. Trigger "componentLoaded", "boot" and "afterBoot" events on all the Components, for them to execute any custom extra logic
     *
     * @param boolean|null $cacheContainerConfiguration Indicate if to cache the container. If null, it gets the value from ENV
     * @param string|null $containerNamespace Provide the namespace, to regenerate the cache whenever the application is upgraded. If null, it gets the value from ENV
     * @param string|null $containerDirectory Provide the directory, to regenerate the cache whenever the application is upgraded. If null, it uses the default /tmp folder by the OS
     */
    public function bootApplication(
        ?bool $cacheContainerConfiguration = null,
        ?string $containerNamespace = null,
        ?string $containerDirectory = null
    ): void {
        /**
         * Allow each component to customize the configuration for itself,
         * and for its depended-upon components.
         * Hence this is executed from bottom to top
         */
        foreach (array_reverse($this->orderedComponentClasses) as $componentClass) {
            $component = App::getComponent($componentClass);
            if (!$component->isEnabled()) {
                continue;
            }
            $component->customizeComponentClassConfiguration($this->componentClassConfiguration);
        }

        /**
         * Initialize the Application container only
         */
        App::getContainerBuilderFactory()->init(
            $cacheContainerConfiguration,
            $containerNamespace,
            $containerDirectory
        );

        /**
         * Initialize the container services by the Components
         */
        foreach ($this->orderedComponentClasses as $componentClass) {
            // Initialize the component, passing its configuration, and checking if its schema must be skipped
            $component = App::getComponent($componentClass);
            if (!$component->isEnabled()) {
                continue;
            }
            $componentConfiguration = $this->componentClassConfiguration[$componentClass] ?? [];
            $skipSchemaForComponent = $this->skipSchemaForComponent($component);
            $component->initialize(
                $componentConfiguration,
                $skipSchemaForComponent,
                $this->skipSchemaComponentClasses
            );
        }

        // Register CompilerPasses, Compile and Cache
        // Symfony's DependencyInjection Application Container
        $systemCompilerPassRegistry = SystemCompilerPassRegistryFacade::getInstance();
        $systemCompilerPasses = $systemCompilerPassRegistry->getCompilerPasses();
        App::getContainerBuilderFactory()->maybeCompileAndCacheContainer($systemCompilerPasses);

        // Initialize the components
        App::getComponentManager()->componentLoaded();
    }

    public function skipSchemaForComponent(ComponentInterface $component): bool
    {
        $componentClass = \get_class($component);
        if (!isset($this->skipSchemaForComponentCache[$componentClass])) {
            $this->skipSchemaForComponentCache[$componentClass] = in_array($componentClass, $this->skipSchemaComponentClasses) || $component->skipSchema();
        }
        return $this->skipSchemaForComponentCache[$componentClass];
    }

    /**
     * Trigger "componentLoaded", "boot" and "afterBoot" events on all the Components,
     * for them to execute any custom extra logic.
     */
    public function bootApplicationComponents(): void
    {
        App::getAppStateManager()->initializeAppState($this->initialAppState);
        $componentManager = App::getComponentManager();
        $componentManager->boot();
        $componentManager->afterBoot();

        // Allow to inject functionality
        App::doAction(HookNames::AFTER_BOOT_APPLICATION);
    }
}
