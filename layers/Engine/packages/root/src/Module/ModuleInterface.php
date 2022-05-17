<?php

declare(strict_types=1);

namespace PoP\Root\Module;

/**
 * Initialize module
 */
interface ModuleInterface
{
    /**
     * Initialize the module
     *
     * @param array<string, mixed> $configuration
     * @param boolean $skipSchema Indicate if to skip initializing the schema
     * @param string[] $skipSchemaModuleClasses
     */
    public function initialize(
        array $configuration,
        bool $skipSchema,
        array $skipSchemaModuleClasses,
    ): void;

    /**
     * Calculate if the module must be enabled or not.
     *
     * @param boolean $ignoreDependencyOnSatisfiedModules Indicate if to check if the satisfied module is resolved or not. Needed to avoid circular references to enable both satisfying and satisfied modules.
     */
    public function calculateIsEnabled(bool $ignoreDependencyOnSatisfiedModules): bool;

    /**
     * Indicate what other module satisfies the contracts by this module.
     */
    public function setSatisfyingModule(ModuleInterface $module): void;

    /**
     * @return string[]
     */
    public function getSatisfiedModuleClasses(): array;

    /**
     * All module classes that this module depends upon, to initialize them
     *
     * @return string[]
     */
    public function getDependedModuleClasses(): array;

    /**
     * All DEV module classes that this module depends upon, to initialize them
     *
     * @return string[]
     */
    public function getDevDependedModuleClasses(): array;

    /**
     * All DEV PHPUnit module classes that this module depends upon, to initialize them
     *
     * @return string[]
     */
    public function getDevPHPUnitDependedModuleClasses(): array;

    /**
     * All conditional module classes that this module depends upon, to initialize them
     *
     * @return string[]
     */
    public function getDependedConditionalModuleClasses(): array;

    /**
     * Function called by the Bootloader before booting the system
     */
    public function configure(): void;

    /**
     * Function called by the Bootloader when booting the system
     */
    public function bootSystem(): void;

    /**
     * Function called by the Bootloader after all modules have been loaded
     */
    public function moduleLoaded(): void;

    /**
     * Function called by the Bootloader when booting the system
     */
    public function boot(): void;

    /**
     * Function called by the Bootloader when booting the system
     */
    public function afterBoot(): void;

    /**
     * Initialize services for the system container
     */
    public function initializeSystem(): void;

    /**
     * Compiler Passes for the System Container
     *
     * @return string[]
     */
    public function getSystemContainerCompilerPassClasses(): array;

     /**
     * Enable each module to set default configuration for
     * itself and its depended modules
     *
     * @param array<string, mixed> $moduleClassConfiguration
     */
    public function customizeModuleClassConfiguration(
        array &$moduleClassConfiguration
    ): void;

    /**
     * Indicates if the Module is enabled
     */
    public function isEnabled(): bool;

    /**
     * Indicates if the Module must skipSchema
     */
    public function skipSchema(): bool;

    /**
     * ModuleConfiguration for the Module
     */
    public function getConfiguration(): ?ModuleConfigurationInterface;

    /**
     * ModuleInfo for the Module
     */
    public function getInfo(): ?ModuleInfoInterface;
}
