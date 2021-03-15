<?php

declare(strict_types=1);

namespace GraphQLAPI\PluginSkeleton;

use PoP\Engine\AppLoader;

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

    /**
     * The main plugin file
     */
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

    /**
     * Plugin's initialization
     */
    public function initialize(): void
    {
        $this->initializeComponentClasses();
    }

    /**
     * Inititalize Plugin's components
     */
    protected function initializeComponentClasses(): void
    {
        // Set the plugin folder on all the Extension Components
        $componentClasses = $this->getComponentClassesToInitialize();
        $pluginFolder = dirname($this->pluginFile);
        foreach ($componentClasses as $componentClass) {
            if (is_a($componentClass, AbstractPluginComponent::class, true)) {
                $componentClass::setPluginFolder($pluginFolder);
            }
        }

        // Initialize the containers
        AppLoader::addComponentClassesToInitialize($componentClasses);
    }

    /**
     * Add Component classes to be initialized
     *
     * @return string[] List of `Component` class to initialize
     */
    public function getComponentClassesToInitialize(): array
    {
        return [];
    }
}
