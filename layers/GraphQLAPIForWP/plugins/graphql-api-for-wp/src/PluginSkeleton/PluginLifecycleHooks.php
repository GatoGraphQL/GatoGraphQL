<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\PluginSkeleton;

class PluginLifecycleHooks
{
    /**
     * Hook to initalize extensions
     */
    public const INITIALIZE_EXTENSION = __CLASS__ . ':initializeExtension';
    /**
     * Hook to configure extensions
     */
    public const CONFIGURE_EXTENSION_COMPONENTS = __CLASS__ . ':configureExtensionComponents';
    /**
     * Hook to configure extensions
     */
    public const CONFIGURE_EXTENSION = __CLASS__ . ':configureExtension';
    /**
     * Hook to boot extensions
     */
    public const BOOT_EXTENSION = __CLASS__ . ':bootExtension';
}
