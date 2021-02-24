<?php

declare(strict_types=1);

namespace GraphQLAPI\ConvertCaseDirectives\PluginScaffolding;

use GraphQLAPI\GraphQLAPI\Plugin as GraphQLAPIPlugin;
use GraphQLAPI\GraphQLAPI\Facades\UserSettingsManagerFacade;

abstract class AbstractPlugin
{
    /**
     * The plugin name
     *
     * @return void
     */
    abstract protected function getPluginName(): string;

    /**
     * Indicate if the main plugin is installed and activated
     *
     * @return boolean
     */
    final protected function isGraphQLAPIPluginActive(): bool
    {
        return class_exists('\GraphQLAPI\GraphQLAPI\Plugin');
    }

    /**
     * If the GraphQL API plugin is not installed and activated,
     * show an error for the admin
     *
     * @return void
     */
    protected function addAdminNoticeError(): void
    {
        \add_action('admin_notices', function () {
            \_e(sprintf(
                '<div class="notice notice-error is-dismissible">' .
                    '<p>%s</p>' .
                '</div>',
                sprintf(
                    \__('Plugin <strong>%1$s</strong> is not installed or activated. Without it, plugin <strong>%2$s</strong> cannot be enabled.'),
                    \__('GraphQL API for WordPress'),
                    $this->getPluginName()
                )
            ));
        });
    }

    /**
     * Plugin set-up, executed after the GraphQL API plugin is loaded,
     * and before it is initialized
     *
     * @return void
     */
    final public function setup(): void
    {
        // Functions to execute when activating/deactivating the plugin
        \register_activation_hook($this->getPluginFile(), [$this, 'activate']);
        \register_deactivation_hook($this->getPluginFile(), [$this, 'deactivate']);

        /**
         * Priority 0: before the GraphQL API plugin is initialized
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
                // // Add to the registry the ModuleResolvers from this plugin
                // $moduleRegistry = ModuleRegistryFacade::getInstance();
                // foreach ($this->getModuleResolverClasses() as $moduleResolverClass) {
                //     $moduleRegistry->addModuleResolver(new $moduleResolverClass());
                // }
                // Execute the plugin's custom setup, if any
                $this->doSetup();

                /**
                 * Initialize/boot this extension plugin
                 */
                \add_action(
                    GraphQLAPIPlugin::HOOK_INITIALIZE_EXTENSION_PLUGIN,
                    [$this, 'initialize']
                );
                \add_action(
                    GraphQLAPIPlugin::HOOK_BOOT_EXTENSION_PLUGIN,
                    [$this, 'boot']
                );
            },
            0
        );
    }

    /**
     * Plugin's initialization
     *
     * @return void
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
        // Execute the plugin's custom setup
        $this->doInitialize();
    }

    /**
     * Plugin's booting
     *
     * @return void
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
     * List of ModuleResolver classes used in the plugin
     *
     * @return array
     */
    protected function getModuleResolverClasses(): array
    {
        return [];
    }

    /**
     * Plugin set-up
     *
     * @return void
     */
    public function doSetup(): void
    {
        // Function to override
    }

    /**
     * Initialize plugin. Function to override
     *
     * @return void
     */
    protected function doInitialize(): void
    {
    }

    /**
     * Initialize plugin. Function to override
     *
     * @return void
     */
    protected function doBoot(): void
    {
    }

    /**
     * Plugin main file
     *
     * @return string
     */
    abstract protected function getPluginFile(): string;

    /**
     * Get permalinks to work when activating the plugin
     *
     * @see    https://codex.wordpress.org/Function_Reference/register_post_type#Flushing_Rewrite_on_Activation
     * @return void
     */
    public function activate(): void
    {
        if (!$this->isGraphQLAPIPluginActive()) {
            return;
        }
        $this->regenerateTimestamp();
    }

    /**
     * Remove permalinks when deactivating the plugin
     *
     * @see    https://developer.wordpress.org/plugins/plugin-basics/activation-deactivation-hooks/
     * @return void
     */
    public function deactivate(): void
    {
        if (!$this->isGraphQLAPIPluginActive()) {
            return;
        }
        $this->regenerateTimestamp();
    }

    /**
     * Regenerate the timestamp
     *
     * @return void
     */
    protected function regenerateTimestamp(): void
    {
        $userSettingsManager = UserSettingsManagerFacade::getInstance();
        $userSettingsManager->storeTimestamp();
    }
}
