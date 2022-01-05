<?php

declare(strict_types=1);

namespace PoP\Root\Component;

use PoP\Root\Helpers\ClassHelpers;

abstract class AbstractComponent implements ComponentInterface
{
    use InitializeContainerServicesInComponentTrait;

    protected ?ComponentConfigurationInterface $componentConfiguration = null;

    /**
     * Enable each component to set default configuration for
     * itself and its depended components
     *
     * @param array<string, mixed> $componentClassConfiguration
     */
    public function customizeComponentClassConfiguration(
        array &$componentClassConfiguration
    ): void {
    }

    /**
     * Initialize the component
     *
     * @param array<string, mixed> $configuration
     * @param boolean $skipSchema Indicate if to skip initializing the schema
     * @param string[] $skipSchemaComponentClasses
     */
    final public function initialize(
        array $configuration = [],
        bool $skipSchema = false,
        array $skipSchemaComponentClasses = []
    ): void {
        // Set the configuration on the corresponding ComponentConfiguration
        $this->initializeConfiguration($configuration);

        // Have the Component set its own info on the corresponding ComponentInfo
        $this->initializeInfo();

        // Initialize the self component
        $this->initializeContainerServices($skipSchema, $skipSchemaComponentClasses);

        // Allow the component to define runtime constants
        $this->defineRuntimeConstants($skipSchema, $skipSchemaComponentClasses);
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
     * All component classes that this component depends upon, to initialize them
     *
     * @return string[]
     */
    abstract public function getDependedComponentClasses(): array;

    /**
     * All DEV component classes that this component depends upon, to initialize them
     *
     * @return string[]
     */
    public function getDevDependedComponentClasses(): array
    {
        return [];
    }

    /**
     * All conditional component classes that this component depends upon, to initialize them
     *
     * @return string[]
     */
    public function getDependedConditionalComponentClasses(): array
    {
        return [];
    }

    /**
     * Compiler Passes for the System Container
     *
     * @return string[]
     */
    public function getSystemContainerCompilerPassClasses(): array
    {
        return [];
    }

    /**
     * Initialize services
     *
     * @param string[] $skipSchemaComponentClasses
     */
    protected function initializeContainerServices(
        bool $skipSchema = false,
        array $skipSchemaComponentClasses = []
    ): void {
    }

    /**
     * Define runtime constants
     */
    protected function defineRuntimeConstants(
        bool $skipSchema = false,
        array $skipSchemaComponentClasses = []
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
    public function beforeBoot(): void
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
     * Indicates if the Component is enabled
     */
    public function isEnabled(): bool
    {
        return true;
    }

    /**
     * ComponentConfiguration class for the Component
     */
    public function getConfiguration(): ?ComponentConfigurationInterface
    {
        return $this->componentConfiguration;
    }

    /**
     * @param array<string,mixed> $configuration
     */
    protected function initializeConfiguration(array $configuration): void
    {
        $componentConfigurationClass = $this->getComponentConfigurationClass();
        if ($componentConfigurationClass === null) {
            return;
        }
        $this->componentConfiguration = new $componentConfigurationClass($configuration);
    }

    /**
     * ComponentConfiguration class for the Component
     */
    protected function getComponentConfigurationClass(): ?string
    {
        $classNamespace = ClassHelpers::getClassPSR4Namespace(\get_called_class());
        $componentConfigurationClass = $classNamespace . '\\ComponentConfiguration';
        if (!class_exists($componentConfigurationClass)) {
            return null;
        }
        return $componentConfigurationClass;
    }

    protected function initializeInfo(): void
    {
        $componentInfoClass = $this->getComponentInfoClass();
        if ($componentInfoClass === null) {
            return;
        }
        $this->componentInfo = new $componentInfoClass();
    }

    /**
     * ComponentInfo class for the Component
     */
    protected function getComponentInfoClass(): ?string
    {
        $classNamespace = ClassHelpers::getClassPSR4Namespace(\get_called_class());
        $componentInfoClass = $classNamespace . '\\ComponentInfo';
        if (!class_exists($componentInfoClass)) {
            return null;
        }
        return $componentInfoClass;
    }
}
