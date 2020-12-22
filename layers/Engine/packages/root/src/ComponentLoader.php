<?php

declare(strict_types=1);

namespace PoP\Root;

/**
 * Component Loader
 */
class ComponentLoader
{
    /**
     * Has the component been initialized?
     *
     * @var string[]
     */
    protected static $initializedClasses = [];

    /**
     * Initialize the PoP components
     *
     * @param string[] $componentClasses List of `Component` class to initialize
     * @param array<string, mixed> $componentClassConfiguration [key]: Component class, [value]: Configuration
     * @param string[] $skipSchemaComponentClasses List of `Component` class to not initialize
     */
    public static function initializeComponents(
        array $componentClasses,
        array $componentClassConfiguration = [],
        array $skipSchemaComponentClasses = []
    ): void {
        /**
         * Get the list of components, in the order in which they must be initialized
         */
        $orderedComponentClasses = self::getComponentsOrderedForInitialization(
            $componentClasses
        );

        /**
         * Allow each component to customize the configuration for itself,
         * and for its depended-upon components.
         * Hence this is executed from bottom to top
         */
        foreach (array_reverse($orderedComponentClasses) as $componentClass) {
            $componentClass::customizeComponentClassConfiguration($componentClassConfiguration);
        }

        /**
         * Initialize the components
         */
        foreach ($orderedComponentClasses as $componentClass) {
            // Temporary solution until migrated:
            // Initialize all depended-upon migration plugins
            foreach ($componentClass::getDependedMigrationPlugins() as $migrationPlugin) {
                // All migration plugins must be composed of "owner/package",
                // and have `initialize.php` as entry point
                require_once dirname(__DIR__, 3) . '/' . $migrationPlugin . '/initialize.php';
            }

            // Initialize the component, passing its configuration, and checking if its schema must be skipped
            $componentConfiguration = $componentClassConfiguration[$componentClass] ?? [];
            $skipSchemaForComponent = in_array($componentClass, $skipSchemaComponentClasses);
            $componentClass::initialize(
                $componentConfiguration,
                $skipSchemaForComponent,
                $skipSchemaComponentClasses
            );
        }
    }

    /**
     * Get the array of components ordered by how they must be initialized,
     * following the Composer dependencies tree
     *
     * @param string[] $componentClasses List of `Component` class to initialize
     */
    protected static function getComponentsOrderedForInitialization(
        array $componentClasses
    ): array {
        $orderedComponentClasses = [];
        self::addComponentsOrderedForInitialization(
            $componentClasses,
            $orderedComponentClasses
        );
        return $orderedComponentClasses;
    }

    /**
     * Get the array of components ordered by how they must be initialized,
     * following the Composer dependencies tree
     *
     * @param string[] $componentClasses List of `Component` class to initialize
     */
    protected static function addComponentsOrderedForInitialization(
        array $componentClasses,
        array &$orderedComponentClasses
    ): void {
        /**
         * If any component class has already been initialized,
         * then do nothing
         */
        $componentClasses = array_values(array_diff(
            $componentClasses,
            self::$initializedClasses
        ));
        foreach ($componentClasses as $componentClass) {
            self::$initializedClasses[] = $componentClass;

            // Initialize all depended-upon PoP components
            self::addComponentsOrderedForInitialization(
                $componentClass::getDependedComponentClasses(),
                $orderedComponentClasses
            );

            // Initialize all depended-upon PoP conditional components, if they are installed
            self::addComponentsOrderedForInitialization(
                array_filter(
                    $componentClass::getDependedConditionalComponentClasses(),
                    'class_exists'
                ),
                $orderedComponentClasses
            );

            // We reached the bottom of the rung, add the component to the list
            $orderedComponentClasses[] = $componentClass;
        }
    }
}
