<?php

declare(strict_types=1);

namespace PoP\Root;

use PoP\Root\Module\ModuleInterface;
use PoP\Root\Constants\HookNames;
use PoP\Root\Dotenv\DotenvBuilderFactory;
use PoP\Root\Facades\SystemCompilerPassRegistryFacade;

/**
 * Application Loader
 */
class AppLoader implements AppLoaderInterface
{
    /**
     * Has the module been initialized?
     *
     * @var string[]
     */
    protected array $initializedModuleClasses = [];
    /**
     * Module in their initialization order
     *
     * @var string[]
     */
    protected array $orderedModuleClasses = [];
    /**
     * Module classes to be initialized
     *
     * @var string[]
     */
    protected array $moduleClassesToInitialize = [];
    /**
     * [key]: Module class, [value]: Configuration
     *
     * @var array<string, array<string, mixed>>
     */
    protected array $moduleClassConfiguration = [];
    /**
     * [key]: State key, [value]: Value
     *
     * @var array<string,mixed>
     */
    protected array $initialAppState = [];
    /**
     * List of `Module` class which must not initialize their Schema services
     *
     * @var string[]
     */
    protected array $skipSchemaModuleClasses = [];
    /**
     * Cache if a module must skipSchema or not, stored under its class
     *
     * @var array<string,bool>
     */
    protected array $skipSchemaForModuleCache = [];

    /**
     * Add Module classes to be initialized
     *
     * @param string[] $moduleClasses List of `Module` class to initialize
     */
    public function addModuleClassesToInitialize(
        array $moduleClasses
    ): void {
        $this->moduleClassesToInitialize = array_merge(
            $this->moduleClassesToInitialize,
            $moduleClasses
        );
    }

    /**
     * Add configuration for the Module classes
     *
     * @param array<string, array<string, mixed>> $moduleClassConfiguration [key]: Module class, [value]: Configuration
     */
    public function addModuleClassConfiguration(
        array $moduleClassConfiguration
    ): void {
        // Allow to override entries under each Module
        foreach ($moduleClassConfiguration as $moduleClass => $moduleConfiguration) {
            $this->moduleClassConfiguration[$moduleClass] ??= [];
            $this->moduleClassConfiguration[$moduleClass] = array_merge(
                $this->moduleClassConfiguration[$moduleClass],
                $moduleConfiguration
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
     * Add schema Module classes to skip initializing
     *
     * @param string[] $skipSchemaModuleClasses List of `Module` class which must not initialize their Schema services
     */
    public function addSchemaModuleClassesToSkip(
        array $skipSchemaModuleClasses
    ): void {
        $this->skipSchemaModuleClasses = array_merge(
            $this->skipSchemaModuleClasses,
            $skipSchemaModuleClasses
        );
    }

    /**
     * Get the array of modules ordered by how they must be initialized,
     * following the Composer dependencies tree
     *
     * @param string[] $moduleClasses List of `Module` class to initialize
     */
    private function addComponentsOrderedForInitialization(
        array $moduleClasses,
        bool $isDev
    ): void {
        /**
         * If any module class has already been initialized,
         * then do nothing
         */
        $moduleClasses = array_values(array_diff(
            $moduleClasses,
            $this->initializedModuleClasses
        ));
        $moduleManager = App::getModuleManager();
        foreach ($moduleClasses as $moduleClass) {
            $this->initializedModuleClasses[] = $moduleClass;

            // Initialize and register the Module
            $module = $moduleManager->register($moduleClass);

            // Initialize all depended-upon PoP modules
            $this->addComponentsOrderedForInitialization(
                $module->getDependedModuleClasses(),
                $isDev
            );

            if ($isDev) {
                $this->addComponentsOrderedForInitialization(
                    $module->getDevDependedModuleClasses(),
                    $isDev
                );
                if (Environment::isApplicationEnvironmentDevPHPUnit()) {
                    $this->addComponentsOrderedForInitialization(
                        $module->getDevPHPUnitDependedModuleClasses(),
                        $isDev
                    );
                }
            }

            // Initialize all depended-upon PoP conditional modules, if they are installed
            $this->addComponentsOrderedForInitialization(
                array_filter(
                    $module->getDependedConditionalModuleClasses(),
                    // Rector does not downgrade `class_exists(...)` properly, so keep as string
                    'class_exists'
                ),
                $isDev
            );

            // We reached the bottom of the rung, add the module to the list
            $this->orderedModuleClasses[] = $moduleClass;

            /**
             * If this compononent satisfies the contracts for other
             * modules, set them as "satisfied".
             */
            foreach ($module->getSatisfiedModuleClasses() as $satisfiedComponentClass) {
                $satisfiedComponent = App::getModule($satisfiedComponentClass);
                $satisfiedComponent->setSatisfyingModule($module);
            }
        }
    }

    /**
     * Get the array of modules ordered by how they must be initialized,
     * following the Composer dependencies tree
     *
     * @param boolean $isDev Indicate if testing with PHPUnit, as to load modules only for DEV
     */
    public function initializeModules(
        bool $isDev = false
    ): void {
        // Initialize Dotenv (before the ContainerBuilder, since this one uses environment constants)
        DotenvBuilderFactory::init();

        /**
         * Calculate the modules in their initialization order
         */
        $this->addComponentsOrderedForInitialization(
            $this->moduleClassesToInitialize,
            $isDev
        );

        /**
         * After initialized, and before booting,
         * allow the modules to inject their own configuration
         */
        $this->configureComponents();
    }

    /**
     * Boot the application. It does these steps:
     *
     * 1. Initialize Symfony's Dotenv module (to get config from ENV)
     * 2. Calculate in what order will the Components (including from main Plugin and Extensions) will be initialized (based on their Composer dependency order)
     * 3. Allow Components to customize the module configuration for themselves, and the modules they can see
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
        foreach ($this->orderedModuleClasses as $moduleClass) {
            $module = App::getModule($moduleClass);
            if (!$module->isEnabled()) {
                continue;
            }
            $module->initializeSystem();
        }
        $systemCompilerPasses = array_map(
            fn ($class) => new $class(),
            $this->getSystemContainerCompilerPasses()
        );
        App::getSystemContainerBuilderFactory()->maybeCompileAndCacheContainer($systemCompilerPasses);

        // Finally boot the modules
        $this->bootSystemComponents();
    }

    /**
     * Trigger after initializing all modules,
     * and before booting the system
     */
    protected function configureComponents(): void
    {
        App::getModuleManager()->configureComponents();
    }

    /**
     * Trigger "moduleLoaded", "boot" and "afterBoot" events on all the Components,
     * for them to execute any custom extra logic
     */
    protected function bootSystemComponents(): void
    {
        App::getModuleManager()->bootSystem();
    }

    /**
     * @return string[]
     */
    final protected function getSystemContainerCompilerPasses(): array
    {
        // Collect the compiler pass classes from all modules
        $compilerPassClasses = [];
        foreach ($this->orderedModuleClasses as $moduleClass) {
            $module = App::getModule($moduleClass);
            if (!$module->isEnabled()) {
                continue;
            }
            $compilerPassClasses = [
                ...$compilerPassClasses,
                ...$module->getSystemContainerCompilerPassClasses()
            ];
        }
        return array_values(array_unique($compilerPassClasses));
    }

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
    ): void {
        /**
         * Allow each module to customize the configuration for itself,
         * and for its depended-upon modules.
         * Hence this is executed from bottom to top
         */
        foreach (array_reverse($this->orderedModuleClasses) as $moduleClass) {
            $module = App::getModule($moduleClass);
            if (!$module->isEnabled()) {
                continue;
            }
            $module->customizeModuleClassConfiguration($this->moduleClassConfiguration);
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
        foreach ($this->orderedModuleClasses as $moduleClass) {
            // Initialize the module, passing its configuration, and checking if its schema must be skipped
            $module = App::getModule($moduleClass);
            if (!$module->isEnabled()) {
                continue;
            }
            $moduleConfiguration = $this->moduleClassConfiguration[$moduleClass] ?? [];
            $skipSchemaForComponent = $this->skipSchemaForComponent($module);
            $module->initialize(
                $moduleConfiguration,
                $skipSchemaForComponent,
                $this->skipSchemaModuleClasses
            );
        }

        // Register CompilerPasses, Compile and Cache
        // Symfony's DependencyInjection Application Container
        $systemCompilerPassRegistry = SystemCompilerPassRegistryFacade::getInstance();
        $systemCompilerPasses = $systemCompilerPassRegistry->getCompilerPasses();
        App::getContainerBuilderFactory()->maybeCompileAndCacheContainer($systemCompilerPasses);

        // Initialize the modules
        App::getModuleManager()->moduleLoaded();
    }

    public function skipSchemaForComponent(ModuleInterface $module): bool
    {
        $moduleClass = \get_class($module);
        if (!isset($this->skipSchemaForModuleCache[$moduleClass])) {
            $this->skipSchemaForModuleCache[$moduleClass] = in_array($moduleClass, $this->skipSchemaModuleClasses) || $module->skipSchema();
        }
        return $this->skipSchemaForModuleCache[$moduleClass];
    }

    /**
     * Trigger "moduleLoaded", "boot" and "afterBoot" events on all the Components,
     * for them to execute any custom extra logic.
     */
    public function bootApplicationModules(): void
    {
        App::getAppStateManager()->initializeAppState($this->initialAppState);
        $moduleManager = App::getModuleManager();
        $moduleManager->boot();
        $moduleManager->afterBoot();

        // Allow to inject functionality
        App::doAction(HookNames::AFTER_BOOT_APPLICATION);
    }
}
