<?php

declare(strict_types=1);

namespace GraphQLAPI\PluginSkeleton;

abstract class AbstractPlugin
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

    protected string $pluginFile;

    final public function __construct(string $pluginFile)
    {
        $this->pluginFile = $pluginFile;
    }

    /**
     * Plugin main file
     */
    protected function getPluginFile(): string
    {
        return $this->pluginFile;
    }

    /**
     * The plugin name
     */
    protected function getPluginName(): string
    {
        return trim(substr($this->pluginFile, strlen(\WP_PLUGIN_DIR)), '/');
    }
}
