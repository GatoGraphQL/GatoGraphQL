<?php

declare(strict_types=1);

namespace PoP\Root;

use PoP\Root\Constants\HookNamePlaceholders;
use PoP\Root\Constants\HookNames;
use PoP\Root\Container\ContainerCacheConfiguration;
use PoP\Root\Dotenv\DotenvBuilderFactory;
use PoP\Root\Facades\SystemCompilerPassRegistryFacade;
use PoP\Root\Module\ModuleInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

/**
 * Application Loader
 */
class AppLoader implements AppLoaderInterface
{
    /**
     * Has the module been initialized?
     *
     * @var string[]
     * @phpstan-var array<class-string<ModuleInterface>>
     */
    protected array $initializedModuleClasses = [];
    /**
     * Module in their initialization order
     *
     * @var string[]
     * @phpstan-var array<class-string<ModuleInterface>>
     */
    protected array $orderedModuleClasses = [];
    /**
     * Module classes to be initialized
     *
     * @var string[]
     * @phpstan-var array<class-string<ModuleInterface>>
     */
    protected array $moduleClassesToInitialize = [];
    protected bool $readyState = false;
    /**
     * [key]: Module class, [value]: Configuration
     *
     * @var array<string,array<string,mixed>>
     * @phpstan-var array<class-string<ModuleInterface>,array<string,mixed>>
     */
    protected array $moduleClassConfiguration = [];
    protected ?ContainerCacheConfiguration $containerCacheConfiguration = null;
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
     * @phpstan-var array<class-string<ModuleInterface>>
     */
    protected array $skipSchemaModuleClasses = [];
    /**
     * Cache if a module must skipSchema or not, stored under its class
     *
     * @var array<string,bool>
     * @phpstan-var array<class-string<ModuleInterface>,bool>
     */
    protected array $skipSchemaForModuleCache = [];
    /**
     * Inject Compiler Passes to boot the System (eg: when testing)
     *
     * @var array<class-string<CompilerPassInterface>>
     */
    protected array $systemContainerCompilerPassClasses = [];
    /**
     * Inject Compiler Passes to boot the Application (eg: when testing)
     *
     * @var array<class-string<CompilerPassInterface>>
     */
    protected array $applicationContainerCompilerPassClasses = [];

    /**
     * Add Module classes to be initialized
     *
     * @param array<class-string<ModuleInterface>> $moduleClasses List of `Module` class to initialize
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
     * Get the Module classes to be initialized.
     *
     * Call this method after the GraphQL server is in ready state,
     * to signify "Module classes already initialized"
     *
     * @return array<class-string<ModuleInterface>> List of `Module` class to initialize
     */
    public function getModuleClassesToInitialize(): array
    {
        return $this->moduleClassesToInitialize;
    }

    /**
     * Define that the application is ready to be used
     */
    public function setReadyState(bool $readyState): void
    {
        $this->readyState = $readyState;
    }

    /**
     * Indicate if the application is ready to be used
     */
    public function isReadyState(): bool
    {
        return $this->readyState;
    }

    /**
     * Add configuration for the Module classes
     *
     * @param array<string,array<string,mixed>> $moduleClassConfiguration [key]: Module class, [value]: Configuration
     * @phpstan-param array<class-string<ModuleInterface>,array<string,mixed>> $moduleClassConfiguration
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
     * Get configuration for the Module classes
     *
     * @return array<class-string<ModuleInterface>,array<string,mixed>> [key]: Module class, [value]: Configuration
     */
    public function getModuleClassConfiguration(): array
    {
        return $this->moduleClassConfiguration;
    }

    /**
     * Inject Compiler Passes to boot the System (eg: when testing)
     *
     * @param array<class-string<CompilerPassInterface>> $systemContainerCompilerPassClasses List of `CompilerPass` class to initialize
     */
    public function addSystemContainerCompilerPassClasses(
        array $systemContainerCompilerPassClasses
    ): void {
        $this->systemContainerCompilerPassClasses = array_merge(
            $this->systemContainerCompilerPassClasses,
            $systemContainerCompilerPassClasses
        );
    }

    /**
     * Get the Compiler Passes to boot the System (eg: when testing)
     *
     * @return array<class-string<CompilerPassInterface>> List of `CompilerPass` class to initialize
     */
    public function getSystemContainerCompilerPassClasses(): array
    {
        return $this->systemContainerCompilerPassClasses;
    }

    /**
     * Inject Compiler Passes to boot the Application (eg: when testing)
     *
     * @param array<class-string<CompilerPassInterface>> $applicationContainerCompilerPassClasses List of `CompilerPass` class to initialize
     */
    public function addApplicationContainerCompilerPassClasses(
        array $applicationContainerCompilerPassClasses
    ): void {
        $this->applicationContainerCompilerPassClasses = array_merge(
            $this->applicationContainerCompilerPassClasses,
            $applicationContainerCompilerPassClasses
        );
    }

    /**
     * Get the Compiler Passes to boot the Application (eg: when testing)
     *
     * @return array<class-string<CompilerPassInterface>> List of `CompilerPass` class to initialize
     */
    public function getApplicationContainerCompilerPassClasses(): array
    {
        return $this->applicationContainerCompilerPassClasses;
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
     * @param array<class-string<ModuleInterface>> $skipSchemaModuleClasses List of `Module` class which must not initialize their Schema services
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
     * @phpstan-param array<class-string<ModuleInterface>> $moduleClasses
     */
    private function addComponentsOrderedForInitialization(
        array $moduleClasses,
        bool $isDev
    ): void {
        /**
         * If any module class has already been initialized,
         * then do nothing
         */
        $moduleClasses = array_diff(
            $moduleClasses,
            $this->initializedModuleClasses
        );
        $moduleManager = App::getModuleManager();
        foreach ($moduleClasses as $moduleClass) {
            $this->initializedModuleClasses[] = $moduleClass;

            // Initialize and register the Module
            $module = $moduleManager->register($moduleClass);

            // Initialize all depended-upon PoP modules
            if (
                $dependedModuleClasses = array_diff(
                    $module->getDependedModuleClasses(),
                    $this->initializedModuleClasses
                )
            ) {
                $this->addComponentsOrderedForInitialization(
                    $dependedModuleClasses,
                    $isDev
                );
            }

            if ($isDev) {
                if (
                    $devDependedModuleClasses = array_diff(
                        $module->getDevDependedModuleClasses(),
                        $this->initializedModuleClasses
                    )
                ) {
                    $this->addComponentsOrderedForInitialization(
                        $devDependedModuleClasses,
                        $isDev
                    );
                }
                if (Environment::isApplicationEnvironmentDevPHPUnit()) {
                    if (
                        $devPHPUnitDependedModuleClasses = array_diff(
                            $module->getDevPHPUnitDependedModuleClasses(),
                            $this->initializedModuleClasses
                        )
                    ) {
                        $this->addComponentsOrderedForInitialization(
                            $devPHPUnitDependedModuleClasses,
                            $isDev
                        );
                    }
                }
            }

            // Initialize all depended-upon PoP conditional modules, if they are installed
            $dependedConditionalModuleClasses = array_filter(
                $module->getDependedConditionalModuleClasses(),
                // Rector does not downgrade `class_exists(...)` properly, so keep as string
                'class_exists'
            );
            if (
                $dependedConditionalModuleClasses = array_diff(
                    $dependedConditionalModuleClasses,
                    $this->initializedModuleClasses
                )
            ) {
                $this->addComponentsOrderedForInitialization(
                    $dependedConditionalModuleClasses,
                    $isDev
                );
            }

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

    public function setContainerCacheConfiguration(
        ?ContainerCacheConfiguration $containerCacheConfiguration = null,
    ): void {
        $this->containerCacheConfiguration = $containerCacheConfiguration;
    }

    public function getContainerCacheConfiguration(): ?ContainerCacheConfiguration
    {
        return $this->containerCacheConfiguration;
    }

    /**
     * Boot the application. It does these steps:
     *
     * 1. Initialize Symfony's Dotenv module (to get config from ENV)
     * 2. Calculate in what order will the Components (including from main Plugin and Extensions) will be initialized (based on their Composer dependency order)
     * 3. Allow Components to customize the module configuration for themselves, and the modules they can see
     * 4. Register all Components with the ModuleManager
     * 5. Initialize the System Container, have all Components inject services, and compile it, making "system" services (eg: hooks, translation) available for initializing Application Container services
     */
    public function bootSystem(): void
    {
        /**
         * System container: initialize it and compile it already,
         * since it will be used to initialize the Application container
         */
        App::getSystemContainerBuilderFactory()->init(
            $this->containerCacheConfiguration?->cacheContainerConfiguration(),
            $this->containerCacheConfiguration?->getContainerConfigurationCacheNamespace(),
            $this->containerCacheConfiguration?->getContainerConfigurationCacheDirectory()
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
     * @return array<class-string<CompilerPassInterface>>
     */
    final protected function getSystemContainerCompilerPasses(): array
    {
        // Collect the compiler pass classes from all modules
        $compilerPassClasses = $this->systemContainerCompilerPassClasses;
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
        /** @var array<class-string<CompilerPassInterface>> */
        return array_values(array_unique($compilerPassClasses));
    }

    /**
     * Boot the application. It does these steps:
     *
     * 1. Initialize the Application Container, have all Components inject services, and compile it
     * 2. Trigger "moduleLoaded", "boot" and "afterBoot" events on all the Components, for them to execute any custom extra logic
     */
    public function bootApplication(): void
    {
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
            $this->containerCacheConfiguration?->cacheContainerConfiguration(),
            $this->containerCacheConfiguration?->getContainerConfigurationCacheNamespace(),
            $this->containerCacheConfiguration?->getContainerConfigurationCacheDirectory()
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
            $skipSchemaForModule = $this->skipSchemaForModule($module);
            /** @var array<class-string<ModuleInterface>> */
            $skipSchemaModuleClasses = $this->skipSchemaModuleClasses;
            $module->initialize(
                $moduleConfiguration,
                $skipSchemaForModule,
                $skipSchemaModuleClasses
            );
        }

        // Register CompilerPasses, Compile and Cache
        // Symfony's DependencyInjection Application Container
        $systemCompilerPassRegistry = SystemCompilerPassRegistryFacade::getInstance();
        $systemCompilerPasses = $systemCompilerPassRegistry->getCompilerPasses();
        $applicationCompilerPasses = [
            ...$systemCompilerPasses,
            ...array_map(
                fn (string $compilerPassClass) => new $compilerPassClass(),
                $this->applicationContainerCompilerPassClasses
            )
        ];
        App::getContainerBuilderFactory()->maybeCompileAndCacheContainer($applicationCompilerPasses);

        // Initialize the modules
        App::getModuleManager()->moduleLoaded();
    }

    public function skipSchemaForModule(ModuleInterface $module): bool
    {
        $moduleClass = \get_class($module);
        if (!isset($this->skipSchemaForModuleCache[$moduleClass])) {
            $this->skipSchemaForModuleCache[$moduleClass] = in_array($moduleClass, $this->skipSchemaModuleClasses) || $module->skipSchema();
        }
        return $this->skipSchemaForModuleCache[$moduleClass];
    }

    /**
     * Trigger "moduleLoaded", "preBoot", "boot" and "afterBoot"
     * events on all the Components, for them to execute
     * any custom extra logic.
     */
    public function bootApplicationModules(): void
    {
        $appStateManager = App::getAppStateManager();
        $appStateManager->initializeAppState($this->initialAppState);
        $moduleManager = App::getModuleManager();
        // Allow to execute the SchemaConfigurator in this event
        $moduleManager->preBoot();
        $moduleManager->boot();
        /**
         * After the services have been initialized, we can then parse the GraphQL query.
         * This way, the SchemaConfigutationExecuter can inject its hooks
         * (eg: Composable Directives enabled?) before the env var is read for
         * first time and, then, initialized.
         */
        $appStateManager->executeAppState();
        $moduleManager->afterBoot();

        // Allow to inject functionality
        App::doAction(HookNames::AFTER_BOOT_APPLICATION);

        // Signal that the GraphQL server is ready to be invoked
        $this->setReadyState(true);

        // Allow to inject functionality
        App::doAction(sprintf(
            HookNamePlaceholders::APPLICATION_READY_FOR_APP_THREAD,
            App::getAppThread()->getName() ?? '',
            App::getAppThread()->getContext()
        ));
        App::doAction(HookNames::APPLICATION_READY);
    }
}
