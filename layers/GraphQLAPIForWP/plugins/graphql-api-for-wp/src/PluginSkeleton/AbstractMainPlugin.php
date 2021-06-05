<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\PluginSkeleton;

use GraphQLAPI\GraphQLAPI\Facades\UserSettingsManagerFacade;
use GraphQLAPI\GraphQLAPI\PluginEnvironment;
use GraphQLAPI\GraphQLAPI\PluginSkeleton\AbstractPlugin;

abstract class AbstractMainPlugin extends AbstractPlugin
{
    /**
     * Get the plugin's immutable configuration values
     *
     * @return array<string, mixed>
     */
    protected function doGetFullConfiguration(): array
    {
        return array_merge(
            parent::doGetFullConfiguration(),
            [
                /**
                 * Where to store the config cache,
                 * for both /service-containers and /config-via-symfony-cache
                 * (config persistent cache: component model configuration + schema)
                 */
                'cache-dir' => PluginEnvironment::getCacheDir(),
            ]
        );
    }

    /**
     * Activate the plugin
     */
    public function activate(): void
    {
        // Override if needed
    }

    /**
     * Remove permalinks when deactivating the plugin
     *
     * @see https://developer.wordpress.org/plugins/plugin-basics/activation-deactivation-hooks/
     */
    public function deactivate(): void
    {
        parent::deactivate();

        // Remove the timestamp
        $this->removeTimestamp();
    }

    /**
     * Regenerate the timestamp
     */
    protected function removeTimestamp(): void
    {
        $userSettingsManager = UserSettingsManagerFacade::getInstance();
        $userSettingsManager->removeTimestamp();
    }

    /**
     * There are three stages for the main plugin, and for each extension plugin:
     * `setup`, `initialize` and `boot`.
     *
     * This is because:
     *
     * - The plugin must execute its logic before the extensions
     * - The services can't be booted before all services have been initialized
     *
     * To attain the needed order, we execute them using hook "plugins_loaded",
     * with all the priorities defined in PluginLifecyclePriorities
     */
    public function setup(): void
    {
        parent::setup();

        /**
         * PoP depends on hook "init" to set-up the endpoint rewrite,
         * as in function `addRewriteEndpoints` in `AbstractEndpointHandler`
         * However, activating the plugin takes place AFTER hooks "plugins_loaded"
         * and "init". Hence, the code cannot flush the rewrite_rules when the plugin
         * is activated, and any non-default GraphQL endpoint is not set.
         *
         * The solution (hack) is to check if the plugin has just been installed,
         * and then apply the logic, on every request in the admin!
         *
         * @see https://developer.wordpress.org/reference/functions/register_activation_hook/#process-flow
         */
        \register_activation_hook($this->getPluginFile(), [$this, 'activate']);

        // Dump the container whenever a new plugin or extension is activated
        $this->handleNewActivations();

        // Initialize the procedure to register/initialize plugin and extensions
        $this->executeSetupProcedure();
    }

    /**
     * Check if the plugin has just been activated or updated,
     * or if an extension has just been activated.
     *
     * Regenerate the container here, and not in the `activate` function,
     * because `activate` doesn't get called within the "plugins_loaded" hook.
     * This is not an issue to register the main plugin, but it is for extensions,
     * since they need to ask if the main plugin exists (since AbstractExtension
     * is located there), and that can only be done in "plugins_loaded".
     */
    protected function handleNewActivations(): void
    {
        /**
         * Logic to check if the main plugin has just been activated or updated.
         */
        \add_action(
            'plugins_loaded',
            function (): void {
                if (!\is_admin()) {
                    return;
                }
                $regenerate = false;
                // If there is no version stored, it's the first screen after activating the plugin
                $storedVersion = \get_option(PluginOptions::PLUGIN_VERSIONS, $this->pluginVersion);
                $isPluginJustActivated = $storedVersion === false;
                $isPluginJustUpdated = $storedVersion !== false && $storedVersion !== $this->pluginVersion;
                if (!$isPluginJustActivated || !$isPluginJustUpdated) {
                    return;
                }
                $regenerate = true;
                // Implement custom additional functionality
                if ($isPluginJustActivated) {
                    $this->pluginJustActivated();
                } elseif ($isPluginJustUpdated) {
                    $this->pluginJustUpdated($storedVersion);
                }

                if ($regenerate) {
                    // Update to the current version
                    \update_option(PluginOptions::PLUGIN_VERSIONS, $this->pluginVersion);
                    // If new CPTs have rewrite rules, these must be flushed
                    \flush_rewrite_rules();
                    // Regenerate the timestamp, to generate the service container
                    $this->regenerateTimestamp();
                }
            },
            PluginLifecyclePriorities::HANDLE_NEW_ACTIVATIONS
        );
    }

    /**
     * There are three stages for the main plugin, and for each extension plugin:
     * `setup`, `initialize` and `boot`.
     *
     * This is because:
     *
     * - The plugin must execute its logic before the extensions
     * - The services can't be booted before all services have been initialized
     *
     * To attain the needed order, we execute them using hook "plugins_loaded",
     * with all the priorities defined in PluginLifecyclePriorities
     */
    final protected function executeSetupProcedure(): void
    {
        /**
         * Wait until "plugins_loaded" to initialize the plugin, because:
         *
         * - ModuleListTableAction requires `wp_verify_nonce`, loaded in pluggable.php
         * - Allow other plugins to inject their own functionality
         */
        \add_action('plugins_loaded', [$this, 'initialize'], PluginLifecyclePriorities::INITIALIZE_PLUGIN);
        \add_action('plugins_loaded', function () {
            \do_action(PluginLifecycleHooks::INITIALIZE_EXTENSION);
        }, PluginLifecyclePriorities::INITIALIZE_EXTENSIONS);
        \add_action('plugins_loaded', [$this, 'bootSystem'], PluginLifecyclePriorities::BOOT_SYSTEM);
        \add_action('plugins_loaded', [$this, 'configure'], PluginLifecyclePriorities::CONFIGURE_PLUGIN);
        \add_action('plugins_loaded', function () {
            \do_action(PluginLifecycleHooks::CONFIGURE_EXTENSION);
        }, PluginLifecyclePriorities::CONFIGURE_EXTENSIONS);
        \add_action('plugins_loaded', [$this, 'bootApplication'], PluginLifecyclePriorities::BOOT_APPLICATION);
        \add_action('plugins_loaded', [$this, 'boot'], PluginLifecyclePriorities::BOOT_PLUGIN);
        \add_action('plugins_loaded', function () {
            \do_action(PluginLifecycleHooks::BOOT_EXTENSION);
        }, PluginLifecyclePriorities::BOOT_EXTENSIONS);
    }

    /**
     * Execute logic after the plugin has just been activated
     */
    protected function pluginJustActivated(): void
    {
    }

    /**
     * Execute logic after the plugin has just been activated
     */
    protected function extensionJustActivated(): void
    {
    }

    /**
     * Execute logic after the plugin has just been updated
     */
    protected function pluginJustUpdated(string $storedVersion): void
    {
    }

    /**
     * Boot the system
     */
    abstract public function bootSystem(): void;

    /**
     * Boot the application
     */
    abstract public function bootApplication(): void;
}
