<?php

declare(strict_types=1);

namespace GraphQLAPI\PluginSkeleton;

use GraphQLAPI\GraphQLAPI\Facades\Registries\CustomPostTypeRegistryFacade;
use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\CustomPostTypeInterface;
use GraphQLAPI\GraphQLAPI\Facades\UserSettingsManagerFacade;
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

    /**
     * Plugin configuration
     */
    public function configure(): void
    {
        // Configure the plugin. This defines hooks to set environment variables,
        // so must be executed before those hooks are triggered for first time
        // (in ComponentConfiguration classes)
        $this->callPluginConfiguration();

        // Only after initializing the System Container,
        // we can obtain the configuration
        // (which may depend on hooks)
        AppLoader::addComponentClassConfiguration(
            $this->getComponentClassConfiguration()
        );
        AppLoader::addSchemaComponentClassesToSkip(
            $this->getSchemaComponentClassesToSkip()
        );
    }

    /**
     * Configure the plugin.
     */
    protected function callPluginConfiguration(): void
    {
        // Override if needed
    }

    /**
     * Add configuration for the Component classes
     *
     * @return array<string, mixed> [key]: Component class, [value]: Configuration
     */
    public function getComponentClassConfiguration(): array
    {
        return [];
    }

    /**
     * Add schema Component classes to skip initializing
     *
     * @return string[] List of `Component` class which must not initialize their Schema services
     */
    public function getSchemaComponentClassesToSkip(): array
    {
        return [];
    }

    /**
     * Regenerate the timestamp
     */
    protected function regenerateTimestamp(): void
    {
        $userSettingsManager = UserSettingsManagerFacade::getInstance();
        $userSettingsManager->storeTimestamp();
    }

    /**
     * Remove the CPTs from the DB
     */
    protected function unregisterPluginCustomPostTypes(): void
    {
        // Unregister all CPTs from this plugin
        if ($customPostTypes = $this->getPluginCustomPostTypes()) {
            foreach ($customPostTypes as $customPostType) {
                $customPostType->unregisterPostType();
            }

            // Clear the permalinks to remove the CPT's rules from the database
            \flush_rewrite_rules();
        }
    }

    /**
     * Get the CPTs from this plugin
     *
     * @return CustomPostTypeInterface[]
     */
    public function getPluginCustomPostTypes(): array
    {
        $customPostTypeRegistry = CustomPostTypeRegistryFacade::getInstance();
        // Filter the ones that belong to this plugin
        // Use $serviceDefinitionID for if the class is overriden
        return array_values(array_filter(
            $customPostTypeRegistry->getCustomPostTypes(),
            fn (string $serviceDefinitionID) => str_starts_with(
                $serviceDefinitionID,
                $this->getExtensionNamespace() . '\\'
            ),
            ARRAY_FILTER_USE_KEY
        ));
    }

    /**
     * The PSR-4 namespace, with format "vendor\project"
     */
    public function getExtensionNamespace(): string
    {
        $class = get_called_class();
        $parts = explode('\\', $class);
        return $parts[0] . '\\' . $parts[1];
    }
}
