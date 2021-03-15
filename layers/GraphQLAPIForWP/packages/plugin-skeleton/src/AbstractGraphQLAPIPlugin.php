<?php

declare(strict_types=1);

namespace GraphQLAPI\PluginSkeleton;

abstract class AbstractGraphQLAPIPlugin
{
    /**
     * Hook to initalize extension plugins
     */
    public const HOOK_INITIALIZE_EXTENSION_PLUGIN = __CLASS__ . ':initializeExtensionPlugin';
    /**
     * Hook to configure extension plugins
     */
    public const HOOK_CONFIGURE_EXTENSION_PLUGIN = __CLASS__ . ':configureExtensionPlugin';
    /**
     * Hook to boot extension plugins
     */
    public const HOOK_BOOT_EXTENSION_PLUGIN = __CLASS__ . ':bootExtensionPlugin';
}
