<?php

declare(strict_types=1);

namespace PoP\Root\Component;

/**
 * Initialize component
 */
abstract class AbstractComponent implements ComponentInterface
{
    use InitializeContainerServicesInComponentTrait;

    /**
     * Component configuration.
     *
     * @var array<string,mixed>
     */
    protected array $configuration = [];

    /**
     * Cannot override the constructor
     */
    final function __construct()
    {        
    }

    public function setConfiguration(array $configuration): void
    {
        self::$configuration = $configuration;
    }
    public function hasConfigurationValue(string $option)
    {
        return isset(self::$configuration[$option]);
    }
    public function getConfigurationValue(string $option)
    {
        return self::$configuration[$option];
    }

    protected function maybeInitializeConfigurationValue(
        string $envVariable,
        mixed &$selfProperty,
        mixed $defaultValue = null,
        ?callable $callback = null
    ): void {
        if (!isset(self::$initialized[$envVariable])) {
            self::$initialized[$envVariable] = true;

            // Only set default value if passing the param,
            // to avoid overriding a value already set in the param definition
            if ($defaultValue !== null) {
                $selfProperty = $defaultValue;
            }
            // Initialize from configuration, environment or hook
            if (self::hasConfigurationValue($envVariable)) {
                // Priority: option has been set in the $configuration
                $selfProperty = self::getConfigurationValue($envVariable);
            } else {
                // Get the value from the environment function
                $envValue = getenv($envVariable);
                if ($envValue !== false) {
                    // Modify the type of the variable, from string to bool/int/array
                    $selfProperty = $callback ? $callback($envValue) : $envValue;
                }
                /**
                 * Important: it must use the Hooks service from the System Container,
                 * and not the (Application) Container, because ComponentConfiguration::foo()
                 * may be accessed when initializing (Application) container services
                 * in `Component.initialize()`, so it must already be available by then
                 */
                $hooksAPI = SystemHooksAPIFacade::getInstance();
                $class = \get_called_class();
                $hookName = ComponentConfigurationHelpers::getHookName(
                    $class,
                    $envVariable
                );
                $selfProperty = $hooksAPI->applyFilters(
                    $hookName,
                    $selfProperty,
                    $class,
                    $envVariable
                );
            }
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
}
