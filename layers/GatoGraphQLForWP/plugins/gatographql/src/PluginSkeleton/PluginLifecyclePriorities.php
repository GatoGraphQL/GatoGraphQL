<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\PluginSkeleton;

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
 * 1. Gato GraphQL => setup(): immediately (not on "plugins_loaded")
 * 2. System => handleNewActivations(): priority 90 (before container is to be created)
 * 3. Gato GraphQL extensions => setup(): priority 100
 * 4. Gato GraphQL => initialize(): priority 110
 * 5. Gato GraphQL extensions => initialize(): priority 120
 * 6. Gato GraphQL => bootSystem(): priority 130
 * 7. Gato GraphQL => configure(): priority 140
 * 8. Gato GraphQL extensions => configure(): priority 150
 * 9. Gato GraphQL => bootApplication(): priority 160
 * 10. Gato GraphQL => boot(): priority 170
 * 11. Gato GraphQL extensions => boot(): priority 180
 * 12. After everything: priority 190
 */
class PluginLifecyclePriorities
{
    public final const HANDLE_NEW_ACTIVATIONS = 80;
    public final const SETUP_EXTENSIONS = 90;
    public final const INITIALIZE_APP = 100;
    public final const INITIALIZE_PLUGIN = 110;
    public final const INITIALIZE_EXTENSIONS = 120;
    public final const CONFIGURE_COMPONENTS = 130;
    public final const BOOT_SYSTEM = 140;
    public final const CONFIGURE_PLUGIN = 150;
    public final const CONFIGURE_EXTENSIONS = 160;
    public final const BOOT_APPLICATION = 170;
    public final const BOOT_PLUGIN = 180;
    public final const BOOT_EXTENSIONS = 190;
    public final const HANDLE_COMMERCIAL_EXTENSIONS = 200;
    public final const AFTER_EVERYTHING = 210;
}
