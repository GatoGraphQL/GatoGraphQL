<?php

declare(strict_types=1);

namespace PoP\Root\Component;

use PoP\Root\Component\ComponentInterface;

/**
 * Initialize component
 */
abstract class AbstractComponent implements ComponentInterface
{
    use InitializeContainerServicesInComponentTrait;

    /**
     * Enable each component to set default configuration for
     * itself and its depended components
     *
     * @param array<string, mixed> $componentClassConfiguration
     */
    public static function customizeComponentClassConfiguration(
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
    final public static function initialize(
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
     *
     * @param array<string, mixed> $configuration
     */
    final public static function initializeSystem(
        array $configuration = []
    ): void {
        static::initializeSystemContainerServices($configuration);
    }

    /**
     * Initialize services for the system container
     *
     * @param array<string, mixed> $configuration
     */
    protected static function initializeSystemContainerServices(
        array $configuration = []
    ): void {
        // Override
    }

    /**
     * All component classes that this component depends upon, to initialize them
     *
     * @return string[]
     */
    abstract public static function getDependedComponentClasses(): array;

    /**
     * All conditional component classes that this component depends upon, to initialize them
     *
     * @return string[]
     */
    public static function getDependedConditionalComponentClasses(): array
    {
        return [];
    }

    /**
     * All migration plugins that this component depends upon, to initialize them
     *
     * @return string[]
     */
    public static function getDependedMigrationPlugins(): array
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
     * Function called by the Bootloader after all components have been loaded
     *
     * @return void
     */
    public static function beforeBoot(): void
    {
    }

    /**
     * Function called by the Bootloader when booting the system
     *
     * @return void
     */
    public static function boot(): void
    {
    }

    /**
     * Function called by the Bootloader when booting the system
     *
     * @return void
     */
    public static function afterBoot(): void
    {
    }

    /**
     * Get all the compiler pass classes required to register on the container
     *
     * @return string[]
     */
    public static function getContainerCompilerPassClasses(): array
    {
        return [];
    }
}
