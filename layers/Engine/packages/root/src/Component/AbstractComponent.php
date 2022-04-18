<?php

declare(strict_types=1);

namespace PoP\Root\Component;

use PoP\Root\App;
use PoP\Root\Helpers\ClassHelpers;

abstract class AbstractComponent implements ComponentInterface
{
    use InitializeContainerServicesInComponentTrait;

    /**
     * Indicate if there is some other component that satisfies
     * the contracts by this component.
     *
     * For instance, the packages under CMSSchema have generic contracts
     * for any CMS, that require to be satisfied for some specific CMS
     * (such as WordPress).
     */
    private bool $hasSatisfyingComponent = false;
    private ?bool $enabled = null;
    protected ?ComponentConfigurationInterface $componentConfiguration = null;
    protected ?ComponentInfoInterface $componentInfo = null;

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
     * Indicate if this component requires some other component
     * to satisfy its contracts.
     *
     * For instance, the packages under CMSSchema have generic contracts
     * for any CMS, that require to be satisfied for some specific CMS
     * (such as WordPress).
     */
    protected function requiresSatisfyingComponent(): bool
    {
        return false;
    }

    /**
     * Indicate that there is some other component that satisfies
     * the contracts by this component.
     */
    protected function setHasSatisfyingComponent(): void
    {
        $this->hasSatisfyingComponent = true;
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
     * All DEV PHPUnit component classes that this component depends upon, to initialize them
     *
     * @return string[]
     */
    public function getDevPHPUnitDependedComponentClasses(): array
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
    public function componentLoaded(): void
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
        if ($this->enabled === null) {
            $this->setEnabled();
        }
        return $this->enabled;
    }

    private function setEnabled(): void
    {
        /**
         * Check that there is some other component that satisfies
         * the contracts of this component (if required).
         */
        if ($this->requiresSatisfyingComponent() && !$this->hasSatisfyingComponent) {
            $this->enabled = false;
            return;
        }

        // If any dependency is disabled, then disable this component too
        if ($this->onlyEnableIfAllDependenciesAreEnabled()) {
            foreach ($this->getDependedComponentClasses() as $dependedComponentClass) {
                $dependedComponent = App::getComponent($dependedComponentClass);
                if (!$dependedComponent->isEnabled()) {
                    $this->enabled = false;
                    return;
                }
            }
        }

        $this->enabled = $this->resolveEnabled();
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
}
