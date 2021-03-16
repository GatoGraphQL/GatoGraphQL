<?php

declare(strict_types=1);

namespace GraphQLAPI\PluginSkeleton;

use GraphQLAPI\PluginSkeleton\AbstractPlugin;

abstract class AbstractMainPlugin extends AbstractPlugin
{
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
