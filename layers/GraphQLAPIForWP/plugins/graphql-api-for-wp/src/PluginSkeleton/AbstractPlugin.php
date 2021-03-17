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
     * The PSR-4 namespace, with format "vendor\project"
     */
    public function getPluginNamespace(): string
    {
        $class = get_called_class();
        $parts = explode('\\', $class);
        return $parts[0] . '\\' . $parts[1];
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
     * Remove permalinks when deactivating the plugin
     *
     * @see https://developer.wordpress.org/plugins/plugin-basics/activation-deactivation-hooks/
     */
    public function deactivate(): void
    {
        $this->unregisterPluginCustomPostTypes();

        $this->regenerateTimestamp();
    }
}
