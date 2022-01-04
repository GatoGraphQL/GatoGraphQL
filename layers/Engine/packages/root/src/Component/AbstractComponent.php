<?php

declare(strict_types=1);

namespace PoP\Root\Component;

abstract class AbstractComponent implements ComponentInterface
{
    use InitializeContainerServicesInComponentTrait;

    protected ?ComponentConfigurationInterface $componentConfiguration = null;

    /**
     * Cannot override the constructor
     */
    final function __construct()
    {
        $componentConfigurationClass = $this->getComponentConfigurationClass();
        if ($componentConfigurationClass !== null) {    
            $this->componentConfiguration = new $componentConfigurationClass();
        }
    }

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
        $this->maybeSetConfiguration($configuration);

        // Initialize the self component
        $this->initializeContainerServices($configuration, $skipSchema, $skipSchemaComponentClasses);

        // Allow the component to define runtime constants
        $this->defineRuntimeConstants($configuration, $skipSchema, $skipSchemaComponentClasses);
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
     * @param array<string, mixed> $configuration
     * @param string[] $skipSchemaComponentClasses
     */
    protected function initializeContainerServices(
        array $configuration = [],
        bool $skipSchema = false,
        array $skipSchemaComponentClasses = []
    ): void {
    }

    /**
     * Define runtime constants
     */
    protected function defineRuntimeConstants(
        array $configuration = [],
        bool $skipSchema = false,
        array $skipSchemaComponentClasses = []
    ): void {
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
    protected function getComponentConfigurationClass(): ?string
    {
        $class = \get_called_class();
        $parts = \explode('\\', $class);
        if (\count($parts) < 3) {
            return null;
        }
        $componentConfigurationClass = $parts[0] . '\\' . $parts[1] . '\\ComponentConfiguration';
        if (!class_exists($componentConfigurationClass)) {
            return null;
        }
        return $componentConfigurationClass;
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
    protected function maybeSetConfiguration(array $configuration): void
    {
        $this->getConfiguration()?->setConfiguration($configuration);
    }
}
