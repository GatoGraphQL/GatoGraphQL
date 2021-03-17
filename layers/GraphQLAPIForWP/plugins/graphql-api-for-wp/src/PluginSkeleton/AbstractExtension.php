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
     * Indicate if the main plugin is installed and activated
     */
    final protected function isGraphQLAPIPluginActive(): bool
    {
        return class_exists('\GraphQLAPI\GraphQLAPI\Plugin');
    }

    /**
     * If the GraphQL API plugin is not installed and activated,
     * show an error for the admin
     */
    protected function addAdminNoticeError(): void
    {
        if ($errorMessage = $this->getGraphQLAPIPluginInactiveAdminNoticeErrorMessage()) {
            \add_action('admin_notices', function () use ($errorMessage) {
                \_e(sprintf(
                    '<div class="notice notice-error is-dismissible">' .
                        '<p>%s</p>' .
                    '</div>',
                    $errorMessage
                ));
            });
        }
    }

    /**
     * The message to show in the admin notices, when the GraphQL API plugin
     * is not installed or activated
     */
    protected function getGraphQLAPIPluginInactiveAdminNoticeErrorMessage(): ?string
    {
        return sprintf(
            \__('Plugin <strong>%1$s</strong> is not installed or activated. Without it, plugin <strong>%2$s</strong> cannot be enabled.'),
            \__('GraphQL API for WordPress'),
            $this->getPluginName()
        );
    }

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
                 * Check that the GraphQL API plugin is installed and activated.
                 * Otherwise show an error, and skip initializing the plugin.
                 */
                if (!$this->isGraphQLAPIPluginActive()) {
                    // Show an error message to the admin
                    $this->addAdminNoticeError();
                    // Exit
                    return;
                }

                // Execute the plugin's custom setup, if any
                $this->doSetup();

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
     * Plugin's initialization
     */
    final public function initialize(): void
    {
        /**
         * Check that the GraphQL API plugin is installed and activated.
         */
        if (!$this->isGraphQLAPIPluginActive()) {
            // Exit
            return;
        }

        parent::initialize();
    }

    /**
     * Plugin's configuration
     */
    final public function configure(): void
    {
        /**
         * Check that the GraphQL API plugin is installed and activated.
         */
        if (!$this->isGraphQLAPIPluginActive()) {
            // Exit
            return;
        }

        parent::configure();

        // Execute the plugin's custom config
        $this->doConfigure();
    }

    /**
     * Plugin's booting
     */
    final public function boot(): void
    {
        /**
         * Check that the GraphQL API plugin is installed and activated.
         */
        if (!$this->isGraphQLAPIPluginActive()) {
            // Exit
            return;
        }

        // Execute the plugin's custom setup
        $this->doBoot();
    }

    /**
     * Initialize plugin. Function to override
     */
    protected function doBoot(): void
    {
    }

    /**
     * Plugin set-up
     */
    protected function doSetup(): void
    {
        // Function to override
    }

    /**
     * Plugin configuration
     */
    protected function doConfigure(): void
    {
        // Function to override
    }
}
