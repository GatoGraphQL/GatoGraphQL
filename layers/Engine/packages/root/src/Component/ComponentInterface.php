<?php

declare(strict_types=1);

namespace PoP\Root\Component;

/**
 * Initialize component
 */
interface ComponentInterface
{
    /**
     * Initialize the component
     *
     * @param array<string, mixed> $configuration
     * @param boolean $skipSchema Indicate if to skip initializing the schema
     * @param string[] $skipSchemaComponentClasses
     */
    public function initialize(
        array $configuration,
        bool $skipSchema,
        array $skipSchemaComponentClasses,
    ): void;

    /**
     * All component classes that this component depends upon, to initialize them
     *
     * @return string[]
     */
    /**
     * Classes from PoP components that must be initialized before this component
     *
     * @return string[]
     */
    public function getDependedComponentClasses(): array;

    /**
     * All DEV component classes that this component depends upon, to initialize them
     *
     * @return string[]
     */
    public function getDevDependedComponentClasses(): array;

    /**
     * All conditional component classes that this component depends upon, to initialize them
     *
     * @return string[]
     */
    public function getDependedConditionalComponentClasses(): array;

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
    public function beforeBoot(): void;

    /**
     * Function called by the Bootloader when booting the system
     */
    public function boot(): void;

    /**
     * Function called by the Bootloader when booting the system
     */
    public function afterBoot(): void;

    /**
     * Have the components initialize their state on a global, shared way
     *
     * @param array<string,mixed> $state
     */
    public function initializeAppState(array &$state): void;

    /**
     * Once all properties by all Components have been set,
     * have this second pass consolidate the state
     *
     * @param array<string,mixed> $state
     */
    public function augmentAppState(array &$state): void;

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
     * @param array<string, mixed> $componentClassConfiguration
     */
    public function customizeComponentClassConfiguration(
        array &$componentClassConfiguration
    ): void;

    /**
     * Indicates if the Component is enabled
     */
    public function isEnabled(): bool;

    /**
     * Indicates if the Component must skipSchema
     */
    public function skipSchema(): bool;

    /**
     * ComponentConfiguration for the Component
     */
    public function getConfiguration(): ?ComponentConfigurationInterface;

    /**
     * ComponentInfo for the Component
     */
    public function getInfo(): ?ComponentInfoInterface;
}
