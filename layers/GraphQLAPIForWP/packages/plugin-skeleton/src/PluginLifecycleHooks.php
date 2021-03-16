<?php

declare(strict_types=1);

namespace GraphQLAPI\PluginSkeleton;

class PluginLifecycleHooks
{
    /**
     * Hook to initalize extension plugins
     */
    public const INITIALIZE_EXTENSION = __CLASS__ . ':initializeExtension';
    /**
     * Hook to configure extension plugins
     */
    public const CONFIGURE_EXTENSION = __CLASS__ . ':configureExtension';
    /**
     * Hook to boot extension plugins
     */
    public const BOOT_EXTENSION = __CLASS__ . ':bootExtension';
}
