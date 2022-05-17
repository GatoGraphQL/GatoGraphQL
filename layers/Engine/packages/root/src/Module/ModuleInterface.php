<?php

declare(strict_types=1);

namespace PoP\Root\Module;

/**
 * Initialize component
 */
interface ModuleInterface
{
    /**
     * Initialize the component
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
     * Calculate if the component must be enabled or not.
     *
     * @param boolean $ignoreDependencyOnSatisfiedModules Indicate if to check if the satisfied component is resolved or not. Needed to avoid circular references to enable both satisfying and satisfied components.
     */
    public function calculateIsEnabled(bool $ignoreDependencyOnSatisfiedModules): bool;

    /**
     * Indicate what other component satisfies the contracts by this component.
     */
    public function setSatisfyingModule(ModuleInterface $component): void;

    /**
     * All component classes that this component satisfies
     *
     * @return string[]
     */
    public function getSatisfiedModuleClasses(): array;

    /**
     * All component classes that this component depends upon, to initialize them
     *
     * @return string[]
     */
    public function getDependedModuleClasses(): array;

    /**
     * All DEV component classes that this component depends upon, to initialize them
     *
     * @return string[]
     */
    public function getDevDependedModuleClasses(): array;

    /**
     * All DEV PHPUnit component classes that this component depends upon, to initialize them
     *
     * @return string[]
     */
    public function getDevPHPUnitDependedModuleClasses(): array;

    /**
     * All conditional component classes that this component depends upon, to initialize them
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
     * Function called by the Bootloader after all components have been loaded
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
     * Enable each component to set default configuration for
     * itself and its depended components
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
