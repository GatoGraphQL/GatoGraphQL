<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\PluginSkeleton;

use GraphQLAPI\GraphQLAPI\Facades\Registries\CustomPostTypeRegistryFacade;
use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\CustomPostTypeInterface;
use GraphQLAPI\GraphQLAPI\Facades\UserSettingsManagerFacade;
use PoP\Engine\AppLoader;

abstract class AbstractPlugin
{
    /**
     * @var array<string, mixed>|null
     */
    private ?array $config = null;

    protected string $pluginBaseName;
    protected string $pluginName;

    public function __construct(
        protected string $pluginFile, /** The main plugin file */
        protected string $pluginVersion,
        ?string $pluginName = null,
    ) {
        $this->pluginBaseName = \plugin_basename($pluginFile);
        $this->pluginName = $pluginName ?? $this->pluginBaseName;
    }

    /**
     * Plugin name
     */
    public function getPluginName(): string
    {
        return $this->pluginName;
    }

    /**
     * Plugin base name
     */
    public function getPluginBaseName(): string
    {
        return $this->pluginBaseName;
    }

    /**
     * Plugin main file
     */
    public function getPluginFile(): string
    {
        return $this->pluginFile;
    }

    /**
     * Plugin version
     */
    public function getPluginVersion(): string
    {
        return $this->pluginVersion;
    }

    /**
     * Get the plugin's immutable configuration values
     *
     * @return array<string, mixed>
     */
    final public function getFullConfiguration(): array
    {
        if ($this->config === null) {
            $this->config = array_merge(
                // These configuration values are mandatory
                [
                    'version' => $this->pluginVersion,
                    'file' => $this->pluginFile,
                    'baseName' => $this->pluginBaseName,
                    'name' => $this->pluginName,
                ],
                // These are custom configuration values
                $this->doGetFullConfiguration()
            );
        }
        return $this->config;
    }

    /**
     * Get a plugin's immutable configuration value
     */
    final public function getConfig(string $key): mixed
    {
        $config = $this->getFullConfiguration();
        return $config[$key];
    }

    /**
     * Get the plugin's immutable configuration values
     *
     * @return array<string, mixed>
     */
    protected function doGetFullConfiguration(): array
    {
        return [
            'dir' => dirname($this->pluginFile),
            'url' => plugin_dir_url($this->pluginFile),
        ];
    }

    /**
     * Plugin's initialization
     */
    public function initialize(): void
    {
        $this->initializeComponentClasses();
    }

    /**
     * Initialize Plugin's components
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
    abstract protected function callPluginConfiguration(): void;

    /**
     * Plugin's booting
     */
    public function boot(): void
    {
        // Function to override
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
                $customPostType->unregisterCustomPostType();
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
                $this->getPluginNamespace() . '\\'
            ),
            ARRAY_FILTER_USE_KEY
        ));
    }

    /**
     * The PSR-4 namespace, with format "Vendor\Project"
     */
    public function getPluginNamespace(): string
    {
        return PluginHelpers::getClassPSR4Namespace(get_called_class());
    }

    /**
     * Plugin set-up, executed immediately when loading the plugin.
     */
    public function setup(): void
    {
        // Functions to execute when activating/deactivating the plugin
        \register_deactivation_hook($this->getPluginFile(), [$this, 'deactivate']);
        /**
         * PoP depends on hook "init" to set-up the endpoint rewrite,
         * as in function `addRewriteEndpoints` in `AbstractEndpointHandler`
         * However, activating the plugin takes place AFTER hooks "plugins_loaded"
         * and "init". Hence, the code cannot flush the rewrite_rules when the plugin
         * is activated, and any non-default GraphQL endpoint is not set.
         *
         * The solution (hack) is to check if the plugin has just been installed,
         * and then apply the logic, on every request in the admin!
         *
         * @see https://developer.wordpress.org/reference/functions/register_activation_hook/#process-flow
         */
        \register_activation_hook($this->getPluginFile(), [$this, 'activate']);
    }

    /**
     * Activate the plugin
     */
    public function activate(): void
    {
        // Override if needed
    }

    /**
     * Remove permalinks when deactivating the plugin
     *
     * @see https://developer.wordpress.org/plugins/plugin-basics/activation-deactivation-hooks/
     */
    public function deactivate(): void
    {
        $this->removePluginVersion();

        $this->unregisterPluginCustomPostTypes();

        $this->regenerateTimestamp();
    }

    /**
     * Remove the plugin or extension's version from the wp_options table
     */
    private function removePluginVersion(): void
    {
        $pluginVersions = \get_option(PluginOptions::PLUGIN_VERSIONS, []);
        unset($pluginVersions[$this->pluginBaseName]);
        \update_option(PluginOptions::PLUGIN_VERSIONS, $pluginVersions);
    }

    /**
     * Execute logic after the plugin/extension has just been activated
     */
    protected function pluginJustActivated(): void
    {
    }

    /**
     * Execute logic after the plugin/extension has just been updated
     */
    protected function pluginJustUpdated(string $storedVersion): void
    {
    }
}
