<?php

declare(strict_types=1);

namespace GraphQLAPI\PluginSkeleton;

use GraphQLAPI\GraphQLAPI\Facades\UserSettingsManagerFacade;
use GraphQLAPI\PluginSkeleton\AbstractPlugin;

abstract class AbstractMainPlugin extends AbstractPlugin
{
    /**
     * Activate the plugin
     */
    public function activate(): void
    {
        parent::activate();

        // By removing the option (in case it already exists from a previously-installed version),
        // the next request will know the plugin was just installed
        \update_option(PluginOptions::PLUGIN_VERSION, false);
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
     * To attain the needed order, we execute them using hook "plugins_loaded":
     *
     * 1. GraphQL API => setup(): immediately
     * 2. GraphQL API extensions => setup(): priority 0
     * 3. GraphQL API => initialize(): priority 10
     * 4. GraphQL API extensions => initialize(): priority 20
     * 5. GraphQL API => bootSystem(): priority 30
     * 3. GraphQL API => configure(): priority 40
     * 4. GraphQL API extensions => configure(): priority 50
     * 5. GraphQL API => bootApplication(): priority 60
     * 6. GraphQL API => boot(): priority 70
     * 7. GraphQL API extensions => boot(): priority 80
     */
    public function setup(): void
    {
        parent::setup();

        \add_action(
            'admin_init',
            function (): void {
                // If there is no version stored, it's the first screen after activating the plugin
                $isPluginJustActivated = \get_option(PluginOptions::PLUGIN_VERSION) === false;
                if (!$isPluginJustActivated) {
                    return;
                }
                // Update to the current version
                \update_option(PluginOptions::PLUGIN_VERSION, \GRAPHQL_API_VERSION);
                // Required logic after plugin is activated
                \flush_rewrite_rules();

                $this->pluginJustActivated();
            }
        );

        \add_action(
            'admin_init',
            function (): void {
                // If the flag is true, it's the first screen after activating an extension
                $justActivatedExtension = \get_option(PluginOptions::ACTIVATED_EXTENSION);
                if (!$justActivatedExtension) {
                    return;
                }
                // Remove the entry
                \delete_option(PluginOptions::ACTIVATED_EXTENSION);
                // Required logic after extension is activated
                \flush_rewrite_rules();

                $this->extensionJustActivated((string) $justActivatedExtension);
            }
        );

        \add_action(
            'admin_init',
            function (): void {
                // Do not execute when doing Ajax, since we can't show the one-time
                // admin notice to the user then
                if (\wp_doing_ajax()) {
                    return;
                }
                // Check if the plugin has been updated: if the stored version in the DB
                // and the current plugin's version are different
                // It could also be false from the first time we install the plugin
                $storedVersion = \get_option(PluginOptions::PLUGIN_VERSION, \GRAPHQL_API_VERSION);
                if (!$storedVersion || $storedVersion == \GRAPHQL_API_VERSION) {
                    return;
                }
                // Update to the current version
                \update_option(PluginOptions::PLUGIN_VERSION, \GRAPHQL_API_VERSION);

                $this->pluginJustUpdated($storedVersion);
            }
        );

        /**
         * Wait until "plugins_loaded" to initialize the plugin, because:
         *
         * - ModuleListTableAction requires `wp_verify_nonce`, loaded in pluggable.php
         * - Allow other plugins to inject their own functionality
         */
        \add_action('plugins_loaded', [$this, 'initialize'], 10);
        \add_action('plugins_loaded', function () {
            \do_action(PluginLifecycleHooks::INITIALIZE_EXTENSION);
        }, 20);
        \add_action('plugins_loaded', [$this, 'bootSystem'], 30);
        \add_action('plugins_loaded', [$this, 'configure'], 40);
        \add_action('plugins_loaded', function () {
            \do_action(PluginLifecycleHooks::CONFIGURE_EXTENSION);
        }, 50);
        \add_action('plugins_loaded', [$this, 'bootApplication'], 60);
        \add_action('plugins_loaded', [$this, 'boot'], 70);
        \add_action('plugins_loaded', function () {
            \do_action(PluginLifecycleHooks::BOOT_EXTENSION);
        }, 80);
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
    protected function extensionJustActivated(string $extension): void
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

    public function boot(): void
    {
        // Doing nothing yet
    }

    /**
     * Boot the application
     */
    abstract public function bootApplication(): void;
}
