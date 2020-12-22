<?php

declare(strict_types=1);

namespace PoP\Root\Component;

use PoP\Root\Managers\ComponentManager;
use PoP\Root\Component\ComponentInterface;

/**
 * Initialize component
 */
abstract class AbstractComponent implements ComponentInterface
{
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
    public static function initialize(
        array $configuration = [],
        bool $skipSchema = false,
        array $skipSchemaComponentClasses = []
    ): void {
        // Initialize the self component
        static::doInitialize($configuration, $skipSchema, $skipSchemaComponentClasses);
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
    protected static function doInitialize(
        array $configuration = [],
        bool $skipSchema = false,
        array $skipSchemaComponentClasses = []
    ): void {
        // Register itself in the Manager
        ComponentManager::register(get_called_class());
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
}
