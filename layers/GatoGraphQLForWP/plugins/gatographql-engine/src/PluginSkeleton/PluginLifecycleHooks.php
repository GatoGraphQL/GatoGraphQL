<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\PluginSkeleton;

class PluginLifecycleHooks
{
    /**
     * Hook to initialize extensions
     */
    public final const INITIALIZE_EXTENSION = __CLASS__ . ':initializeExtension';
    /**
     * Hook to configure extensions
     */
    public final const CONFIGURE_EXTENSION_COMPONENTS = __CLASS__ . ':configureExtensionComponents';
    /**
     * Hook to configure extensions
     */
    public final const CONFIGURE_EXTENSION = __CLASS__ . ':configureExtension';
    /**
     * Hook to boot extensions
     */
    public final const BOOT_EXTENSION = __CLASS__ . ':bootExtension';
}
