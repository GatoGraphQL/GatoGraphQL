<?php

declare(strict_types=1);

namespace PoP\Root\Module;

use PoP\Root\App;
use PoP\Root\Helpers\ClassHelpers;
use PoP\Root\Module\ModuleInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

abstract class AbstractModule implements ModuleInterface
{
    use InitializeContainerServicesInModuleTrait;

    /**
     * Indicate what other module satisfies the contracts
     * by this module.
     *
     * For instance, the packages under CMSSchema have generic contracts
     * for any CMS, that require to be satisfied for some specific CMS
     * (such as WordPress).
     */
    private ?ModuleInterface $satisfyingModule = null;
    private ?bool $enabled = null;
    protected ?ModuleConfigurationInterface $moduleConfiguration = null;
    protected ?ModuleInfoInterface $moduleInfo = null;

    /**
     * Enable each module to set default configuration for
     * itself and its depended components
     *
     * @param array<string,mixed> $moduleClassConfiguration
     */
    public function customizeModuleClassConfiguration(
        array &$moduleClassConfiguration
    ): void {
    }

    /**
     * Initialize the module
     *
     * @param array<string,mixed> $configuration
     * @param boolean $skipSchema Indicate if to skip initializing the schema
     * @param array<class-string<ModuleInterface>> $skipSchemaModuleClasses
     */
    final public function initialize(
        array $configuration,
        bool $skipSchema,
        array $skipSchemaModuleClasses,
    ): void {
        // Set the configuration on the corresponding ModuleConfiguration
        $this->initializeConfiguration($configuration);

        // Have the Module set its own info on the corresponding ModuleInfo
        $this->initializeInfo();

        // Initialize the self module
        $this->initializeContainerServices($skipSchema, $skipSchemaModuleClasses);

        // Allow the module to define runtime constants
        $this->defineRuntimeConstants($skipSchema, $skipSchemaModuleClasses);
    }

    /**
     * Initialize services for the system container
     */
    final public function initializeSystem(): void
    {
        $this->initializeSystemContainerServices();
    }

    /**
     * Initialize services for the system container
     */
    protected function initializeSystemContainerServices(): void
    {
        // Override
    }

    /**
     * Indicate if this module requires some other module
     * to satisfy its contracts.
     *
     * For instance, the packages under CMSSchema have generic contracts
     * for any CMS, that require to be satisfied for some specific CMS
     * (such as WordPress).
     */
    protected function requiresSatisfyingModule(): bool
    {
        return false;
    }

    /**
     * Indicate what other module satisfies the contracts by this module.
     */
    public function setSatisfyingModule(ModuleInterface $module): void
    {
        $this->satisfyingModule = $module;
    }

    /**
     * All module classes that this module satisfies
     *
     * @return array<class-string<ModuleInterface>>
     */
    public function getSatisfiedModuleClasses(): array
    {
        return [];
    }

    /**
     * All module classes that this module depends upon, to initialize them
     *
     * @return string[]
     * @phpstan-return array<class-string<ModuleInterface>>
     */
    abstract public function getDependedModuleClasses(): array;

    /**
     * All DEV module classes that this module depends upon, to initialize them
     *
     * @return array<class-string<ModuleInterface>>
     */
    public function getDevDependedModuleClasses(): array
    {
        return [];
    }

    /**
     * All DEV PHPUnit module classes that this module depends upon, to initialize them
     *
     * @return array<class-string<ModuleInterface>>
     */
    public function getDevPHPUnitDependedModuleClasses(): array
    {
        return [];
    }

    /**
     * All conditional module classes that this module depends upon, to initialize them
     *
     * @return array<class-string<ModuleInterface>>
     */
    public function getDependedConditionalModuleClasses(): array
    {
        return [];
    }

    /**
     * Compiler Passes for the System Container
     *
     * @return array<class-string<CompilerPassInterface>>
     */
    public function getSystemContainerCompilerPassClasses(): array
    {
        return [];
    }

    /**
     * Initialize services
     *
     * @param array<class-string<ModuleInterface>> $skipSchemaModuleClasses
     */
    protected function initializeContainerServices(
        bool $skipSchema,
        array $skipSchemaModuleClasses,
    ): void {
    }

    /**
     * Define runtime constants
     * @param array<class-string<ModuleInterface>> $skipSchemaModuleClasses
     */
    protected function defineRuntimeConstants(
        bool $skipSchema,
        array $skipSchemaModuleClasses
    ): void {
    }

    /**
     * Function called by the Bootloader before booting the system
     */
    public function configure(): void
    {
    }

    /**
     * Function called by the Bootloader when booting the system
     */
    public function bootSystem(): void
    {
    }

    /**
     * Function called by the Bootloader after all components have been loaded
     */
    public function moduleLoaded(): void
    {
    }

    /**
     * Function called by the Bootloader when booting the system
     */
    public function preBoot(): void
    {
    }

    /**
     * Function called by the Bootloader when booting the system
     */
    public function boot(): void
    {
    }

    /**
     * Function called by the Bootloader when booting the system
     */
    public function afterBoot(): void
    {
    }

    /**
     * Indicates if the Module is enabled
     */
    public function isEnabled(): bool
    {
        if ($this->enabled === null) {
            $this->enabled = $this->calculateIsEnabled(false);
        }
        return $this->enabled;
    }

    /**
     * Calculate if the module must be enabled or not.
     *
     * @param boolean $ignoreDependencyOnSatisfiedModules Indicate if to check if the satisfied module is resolved or not. Needed to avoid circular references to enable both satisfying and satisfied components.
     */
    public function calculateIsEnabled(bool $ignoreDependencyOnSatisfiedModules): bool
    {
        /**
         * Check that there is some other module that satisfies
         * the contracts of this module (if required), and
         * that this components is itself enabled.
         *
         * The satisfying module depends on the satisfied module,
         * and the other way around too. To avoid circular recursions
         * there is param $ignoreDependencyOnSatisfiedModules.
         */
        if ($this->requiresSatisfyingModule()) {
            if ($this->satisfyingModule === null) {
                return false;
            }
            if (!$this->satisfyingModule->calculateIsEnabled(true)) {
                return false;
            }
        }

        // If any dependency is disabled, then disable this module too
        if ($this->onlyEnableIfAllDependenciesAreEnabled()) {
            $satisfiedModuleClasses = $this->getSatisfiedModuleClasses();
            foreach ($this->getDependedModuleClasses() as $dependedModuleClass) {
                if ($ignoreDependencyOnSatisfiedModules && in_array($dependedModuleClass, $satisfiedModuleClasses)) {
                    continue;
                }
                $dependedModule = App::getModule($dependedModuleClass);
                if (!$dependedModule->isEnabled()) {
                    return false;
                }
            }
        }

        return $this->resolveEnabled();
    }

    public function onlyEnableIfAllDependenciesAreEnabled(): bool
    {
        return true;
    }

    protected function resolveEnabled(): bool
    {
        return true;
    }

    /**
     * Indicates if the Module must skipSchema
     */
    public function skipSchema(): bool
    {
        return false;
    }

    /**
     * ModuleConfiguration class for the Module
     */
    public function getConfiguration(): ?ModuleConfigurationInterface
    {
        return $this->moduleConfiguration;
    }

    /**
     * ModuleInfo class for the Module
     */
    public function getInfo(): ?ModuleInfoInterface
    {
        return $this->moduleInfo;
    }

    /**
     * @param array<string,mixed> $configuration
     */
    protected function initializeConfiguration(array $configuration): void
    {
        $moduleConfigurationClass = $this->getModuleConfigurationClass();
        if ($moduleConfigurationClass === null) {
            return;
        }
        $this->moduleConfiguration = new $moduleConfigurationClass($configuration);
    }

    /**
     * ModuleConfiguration class for the Module
     *
     * @return class-string<ModuleConfigurationInterface>|null
     */
    protected function getModuleConfigurationClass(): ?string
    {
        $classNamespace = ClassHelpers::getClassPSR4Namespace(\get_called_class());
        $moduleConfigurationClass = $classNamespace . '\\ModuleConfiguration';
        if (!class_exists($moduleConfigurationClass)) {
            return null;
        }
        /** @var class-string<ModuleConfigurationInterface> */
        return $moduleConfigurationClass;
    }

    protected function initializeInfo(): void
    {
        $moduleInfoClass = $this->getModuleInfoClass();
        if ($moduleInfoClass === null) {
            return;
        }
        $this->moduleInfo = new $moduleInfoClass($this);
    }

    /**
     * ModuleInfo class for the Module
     *
     * @return class-string<ModuleInfoInterface>|null
     */
    protected function getModuleInfoClass(): ?string
    {
        $classNamespace = ClassHelpers::getClassPSR4Namespace(\get_called_class());
        $moduleInfoClass = $classNamespace . '\\ModuleInfo';
        if (!class_exists($moduleInfoClass)) {
            return null;
        }
        /** @var class-string<ModuleInfoInterface> */
        return $moduleInfoClass;
    }
}
