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
    public static function initialize(
        array $configuration = [],
        bool $skipSchema = false,
        array $skipSchemaComponentClasses = []
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
    public static function getDependedComponentClasses(): array;

    /**
     * All DEV component classes that this component depends upon, to initialize them
     *
     * @return string[]
     */
    public static function getDevDependedComponentClasses(): array;

    /**
     * All conditional component classes that this component depends upon, to initialize them
     *
     * @return string[]
     */
    public static function getDependedConditionalComponentClasses(): array;

    // /**
    //  * Initialize services
    //  */
    // public static function init(): void;
    /**
     * Function called by the Bootloader after all components have been loaded
     */
    public static function beforeBoot(): void;

    /**
     * Function called by the Bootloader when booting the system
     */
    public static function boot(): void;

    /**
     * Function called by the Bootloader when booting the system
     */
    public static function afterBoot(): void;
}
