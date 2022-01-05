<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\PluginSkeleton;

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
 * 1. GraphQL API => setup(): immediately (not on "plugins_loaded")
 * 2. System => handleNewActivations(): priority 90 (before container is to be created)
 * 3. GraphQL API extensions => setup(): priority 100
 * 4. GraphQL API => initialize(): priority 110
 * 5. GraphQL API extensions => initialize(): priority 120
 * 6. GraphQL API => bootSystem(): priority 130
 * 7. GraphQL API => configure(): priority 140
 * 8. GraphQL API extensions => configure(): priority 150
 * 9. GraphQL API => bootApplication(): priority 160
 * 10. GraphQL API => boot(): priority 170
 * 11. GraphQL API extensions => boot(): priority 180
 * 12. After everything: priority 190
 */
class PluginLifecyclePriorities
{
    public const HANDLE_NEW_ACTIVATIONS = 80;
    public const SETUP_EXTENSIONS = 90;
    public const INITIALIZE_APP = 100;
    public const INITIALIZE_PLUGIN = 110;
    public const INITIALIZE_EXTENSIONS = 120;
    public const CONFIGURE_COMPONENTS = 130;
    public const BOOT_SYSTEM = 140;
    public const CONFIGURE_PLUGIN = 150;
    public const CONFIGURE_EXTENSIONS = 160;
    public const BOOT_APPLICATION = 170;
    public const BOOT_PLUGIN = 180;
    public const BOOT_EXTENSIONS = 190;
    public const AFTER_EVERYTHING = 200;
}
