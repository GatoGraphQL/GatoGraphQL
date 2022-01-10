<?php

declare(strict_types=1);

namespace PoP\Root\Component;

use PoP\Root\App;
use PoP\Root\Helpers\ClassHelpers;

abstract class AbstractComponent implements ComponentInterface
{
    use InitializeContainerServicesInComponentTrait;

    private ?bool $enabled = null;
    protected ?ComponentConfigurationInterface $componentConfiguration = null;
    protected ?ComponentInfoInterface $componentInfo = null;
    protected ?AppStateProviderInterface $componentAppState = null;

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
        array $configuration,
        bool $skipSchema,
        array $skipSchemaComponentClasses,
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
        bool $skipSchema,
        array $skipSchemaComponentClasses,
    ): void {
    }

    /**
     * Define runtime constants
     */
    protected function defineRuntimeConstants(
        bool $skipSchema,
        array $skipSchemaComponentClasses
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
     * Have the components initialize their state on a global, shared way
     *
     * @param array<string,mixed> $state
     */
    public function initializeAppState(array &$state): void
    {
        $this->componentAppState = $this->getComponentAppState();
        if ($this->componentAppState === null) {
            return;
        }
        $this->componentAppState->initialize($state);
    }

    /**
     * Once all properties by all Components have been set,
     * have this second pass consolidate the state
     *
     * @param array<string,mixed> $state
     */
    public function augmentAppState(array &$state): void
    {
        if ($this->componentAppState === null) {
            return;
        }
        $this->componentAppState->augment($state);
    }

    /**
     * Indicates if the Component is enabled
     */
    public function isEnabled(): bool
    {
        if ($this->enabled === null) {
            // If any dependency is disabled, then disable this component too
            foreach ($this->getDependedComponentClasses() as $dependedComponentClass) {
                $dependedComponent = App::getComponent($dependedComponentClass);
                if (!$dependedComponent->isEnabled()) {
                    $this->enabled = false;
                    return $this->enabled;
                }
            }
            $this->enabled = $this->resolveEnabled();
        }
        return $this->enabled;
    }

    protected function resolveEnabled(): bool
    {
        return true;
    }

    /**
     * Indicates if the Component must skipSchema
     */
    public function skipSchema(): bool
    {
        return false;
    }

    /**
     * ComponentConfiguration class for the Component
     */
    public function getConfiguration(): ?ComponentConfigurationInterface
    {
        return $this->componentConfiguration;
    }

    /**
     * ComponentInfo class for the Component
     */
    public function getInfo(): ?ComponentInfoInterface
    {
        return $this->componentInfo;
    }

    /**
     * ComponentAppState class for the Component
     */
    public function getAppState(): ?AppStateProviderInterface
    {
        return $this->componentAppState;
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
        $this->componentInfo = new $componentInfoClass($this);
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

    /**
     * Have the components initialize their state on a global, shared way
     *
     * @param array<string,mixed> $state
     */
    protected function getComponentAppState(): ?AppStateProviderInterface
    {
        $componentAppStateClass = $this->getComponentAppStateClass();
        if ($componentAppStateClass === null) {
            return null;
        }
        // Get the ComponentAppState from the container,
        // so it can use services
        if (!App::getContainer()->has($componentAppStateClass)) {
            return null;
        }
        return App::getContainer()->get($componentAppStateClass);
    }

    /**
     * ComponentAppState class for the Component
     */
    protected function getComponentAppStateClass(): ?string
    {
        $classNamespace = ClassHelpers::getClassPSR4Namespace(\get_called_class());
        $componentAppStateClass = $classNamespace . '\\ComponentAppState';
        if (!class_exists($componentAppStateClass)) {
            return null;
        }
        return $componentAppStateClass;
    }
}
