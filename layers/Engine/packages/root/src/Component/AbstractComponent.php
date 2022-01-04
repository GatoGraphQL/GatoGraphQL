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
     * Cannot override the constructor
     */
    final function __construct()
    {        
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
        static::initializeContainerServices($configuration, $skipSchema, $skipSchemaComponentClasses);

        // Allow the component to define runtime constants
        static::defineRuntimeConstants($configuration, $skipSchema, $skipSchemaComponentClasses);
    }

    /**
     * Initialize services for the system container
     */
    final public function initializeSystem(): void
    {
        static::initializeSystemContainerServices();
    }

    /**
     * Initialize services for the system container
     */
    protected static function initializeSystemContainerServices(): void
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
    protected static function initializeContainerServices(
        array $configuration = [],
        bool $skipSchema = false,
        array $skipSchemaComponentClasses = []
    ): void {
    }

    /**
     * Define runtime constants
     */
    protected static function defineRuntimeConstants(
        array $configuration = [],
        bool $skipSchema = false,
        array $skipSchemaComponentClasses = []
    ): void {
    }

    /**
     * Function called by the Bootloader when booting the system
     */
    public static function bootSystem(): void
    {
    }

    /**
     * Function called by the Bootloader after all components have been loaded
     */
    public static function beforeBoot(): void
    {
    }

    /**
     * Function called by the Bootloader when booting the system
     */
    public static function boot(): void
    {
    }

    /**
     * Function called by the Bootloader when booting the system
     */
    public static function afterBoot(): void
    {
    }
}
