<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\PluginSkeleton;

use GraphQLAPI\GraphQLAPI\Facades\Registries\SystemModuleRegistryFacade;
use PoP\ComponentModel\Misc\GeneralUtils;

/**
 * This class is hosted within the graphql-api-for-wp plugin, and not
 * within the extension plugin. That means that the main plugin
 * must be installed, for any extension to work.
 *
 * This class doesn't have an `activate` function, because `activate`
 * can't be executed within "plugins_loaded", on which we find out if the
 * main plugin is installed and activated.
 *
 * @see https://developer.wordpress.org/reference/functions/register_activation_hook/#more-information
 *
 * Then, the activation must be done on the extension's main file.
 *
 * @see https://github.com/leoloso/PoP/blob/bdbe7c911f3a7919c42e19d42a1354de1a94806c/layers/GraphQLAPIForWP/plugins/convert-case-directives/graphql-api-convert-case-directives.php#L29
 */
abstract class AbstractExtension extends AbstractPlugin
{
    /**
     * Plugin set-up, executed after the GraphQL API plugin is loaded,
     * and before it is initialized
     */
    final public function setup(): void
    {
        parent::setup();

        /**
         * Priority 100: before the GraphQL API plugin is initialized
         */
        \add_action(
            'plugins_loaded',
            function (): void {
                /**
                 * Initialize/configure/boot this extension plugin
                 */
                \add_action(
                    PluginLifecycleHooks::INITIALIZE_EXTENSION,
                    [$this, 'initialize']
                );
                \add_action(
                    PluginLifecycleHooks::CONFIGURE_EXTENSION,
                    [$this, 'configure']
                );
                \add_action(
                    PluginLifecycleHooks::BOOT_EXTENSION,
                    [$this, 'boot']
                );

                // Execute the plugin's custom setup, if any
                $this->doSetup();
            },
            PluginLifecyclePriorities::SETUP_EXTENSIONS
        );
    }

    /**
     * Add schema Component classes to skip initializing
     *
     * @return string[] List of `Component` class which must not initialize their Schema services
     */
    public function getSchemaComponentClassesToSkip(): array
    {
        return $this->getSkippingSchemaComponentClasses();
    }

    /**
     * Provide the classes of the components whose
     * schema initialization must be skipped
     *
     * @return string[]
     */
    protected function getSkippingSchemaComponentClasses(): array
    {
        $moduleRegistry = SystemModuleRegistryFacade::getInstance();

        // Component classes are skipped if the module is disabled
        $skipSchemaModuleComponentClasses = array_filter(
            $this->getModuleComponentClasses(),
            function ($module) use ($moduleRegistry) {
                return !$moduleRegistry->isModuleEnabled($module);
            },
            ARRAY_FILTER_USE_KEY
        );
        return GeneralUtils::arrayFlatten(
            array_values(
                $skipSchemaModuleComponentClasses
            )
        );
    }

    /**
     * Provide the list of modules to check if they are enabled and,
     * if they are not, what component classes must skip initialization
     *
     * @return array<string,string[]>
     */
    protected function getModuleComponentClasses(): array
    {
        return [];
    }

    /**
     * Plugin's configuration
     */
    public function configure(): void
    {
        // Function to override
    }

    /**
     * Plugin's booting
     */
    public function boot(): void
    {
        // Function to override
    }

    /**
     * Plugin set-up
     */
    protected function doSetup(): void
    {
        // Function to override
    }
}
