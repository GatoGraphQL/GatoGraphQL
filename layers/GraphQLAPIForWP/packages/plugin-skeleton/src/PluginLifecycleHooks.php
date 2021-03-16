<?php

declare(strict_types=1);

namespace GraphQLAPI\PluginSkeleton;

class PluginLifecycleHooks
{
    /**
     * Hook to initalize extension plugins
     */
    public const INITIALIZE_EXTENSION_PLUGIN = __CLASS__ . ':initializeExtensionPlugin';
    /**
     * Hook to configure extension plugins
     */
    public const CONFIGURE_EXTENSION_PLUGIN = __CLASS__ . ':configureExtensionPlugin';
    /**
     * Hook to boot extension plugins
     */
    public const BOOT_EXTENSION_PLUGIN = __CLASS__ . ':bootExtensionPlugin';
}
