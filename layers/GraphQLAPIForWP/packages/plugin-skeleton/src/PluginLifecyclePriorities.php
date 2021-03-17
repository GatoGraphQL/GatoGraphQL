<?php

declare(strict_types=1);

namespace GraphQLAPI\PluginSkeleton;

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
 * 2. GraphQL API extensions => setup(): priority 100
 * 3. GraphQL API => initialize(): priority 110
 * 4. GraphQL API extensions => initialize(): priority 120
 * 5. GraphQL API => bootSystem(): priority 130
 * 3. GraphQL API => configure(): priority 140
 * 4. GraphQL API extensions => configure(): priority 150
 * 5. GraphQL API => bootApplication(): priority 160
 * 6. GraphQL API => boot(): priority 170
 * 7. GraphQL API extensions => boot(): priority 180
 */
class PluginLifecyclePriorities
{
    public const SETUP_EXTENSIONS = 100;
    public const INITIALIZE_PLUGIN = 110;
    public const INITIALIZE_EXTENSIONS = 120;
    public const BOOT_SYSTEM = 130;
    public const CONFIGURE_PLUGIN = 140;
    public const CONFIGURE_EXTENSIONS = 150;
    public const BOOT_APPLICATION = 160;
    public const BOOT_PLUGIN = 170;
    public const BOOT_EXTENSIONS = 180;
}
