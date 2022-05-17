<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\PluginSkeleton;

use GraphQLAPI\GraphQLAPI\Facades\Registries\CustomPostTypeRegistryFacade;
use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\CustomPostTypeInterface;
use PoP\Root\App;
use PoP\Root\Helpers\ClassHelpers;

abstract class AbstractPlugin implements PluginInterface
{
    protected ?PluginInfoInterface $pluginInfo = null;

    protected string $pluginBaseName;
    protected string $pluginName;

    public function __construct(
        protected string $pluginFile, /** The main plugin file */
        protected string $pluginVersion,
        ?string $pluginName = null,
    ) {
        $this->pluginBaseName = \plugin_basename($pluginFile);
        $this->pluginName = $pluginName ?? $this->pluginBaseName;

        // Have the Plugin set its own info on the corresponding PluginInfo
        $this->initializeInfo();
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
     * Plugin dir
     */
    public function getPluginDir(): string
    {
        return \dirname($this->pluginFile);
    }

    /**
     * Plugin URL
     */
    public function getPluginURL(): string
    {
        return \plugin_dir_url($this->pluginFile);
    }

    /**
     * PluginInfo class for the Plugin
     */
    public function getInfo(): ?PluginInfoInterface
    {
        return $this->pluginInfo;
    }

    protected function initializeInfo(): void
    {
        $pluginInfoClass = $this->getPluginInfoClass();
        if ($pluginInfoClass === null) {
            return;
        }
        $this->pluginInfo = new $pluginInfoClass($this);
    }

    /**
     * PluginInfo class for the Plugin
     */
    protected function getPluginInfoClass(): ?string
    {
        $classNamespace = ClassHelpers::getClassPSR4Namespace(\get_called_class());
        $pluginInfoClass = $classNamespace . '\\' . $this->getPluginInfoClassName();
        if (!class_exists($pluginInfoClass)) {
            return null;
        }
        return $pluginInfoClass;
    }

    /**
     * PluginInfo class name for the Plugin
     */
    abstract protected function getPluginInfoClassName(): ?string;

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
        // Initialize the containers
        $moduleClasses = $this->getModuleClassesToInitialize();
        App::getAppLoader()->addComponentClassesToInitialize($moduleClasses);
    }

    /**
     * After initialized, and before booting,
     * allow the components to inject their own configuration
     */
    public function configureComponents(): void
    {
        // Set the plugin folder on the plugin's Module
        $pluginFolder = dirname($this->pluginFile);
        $this->getPluginComponent()->setPluginFolder($pluginFolder);
    }

    /**
     * Plugin's Module
     */
    protected function getPluginComponent(): PluginComponentInterface
    {
        /** @var PluginComponentInterface */
        return App::getModule($this->getComponentClass());
    }

    /**
     * Package's Module class, of type PluginComponentInterface.
     * By standard, it is "NamespaceOwner\Project\Module::class"
     */
    protected function getComponentClass(): string
    {
        $classNamespace = ClassHelpers::getClassPSR4Namespace(\get_called_class());
        return $classNamespace . '\\Module';
    }

    /**
     * Add Module classes to be initialized
     *
     * @return string[] List of `Module` class to initialize
     */
    public function getModuleClassesToInitialize(): array
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
        // (in ModuleConfiguration classes)
        $this->callPluginInitializationConfiguration();

        // Only after initializing the System Container,
        // we can obtain the configuration (which may depend on hooks)
        App::getAppLoader()->addComponentClassConfiguration(
            $this->getComponentClassConfiguration()
        );
        App::getAppLoader()->addSchemaComponentClassesToSkip(
            $this->getSchemaComponentClassesToSkip()
        );
    }

    /**
     * Configure the plugin.
     */
    abstract protected function callPluginInitializationConfiguration(): void;

    /**
     * Plugin's booting
     */
    public function boot(): void
    {
        // Function to override
    }

    /**
     * Add configuration for the Module classes
     *
     * @return array<string, mixed> [key]: Module class, [value]: Configuration
     */
    public function getComponentClassConfiguration(): array
    {
        return [];
    }

    /**
     * Add schema Module classes to skip initializing
     *
     * @return string[] List of `Module` class which must not initialize their Schema services
     */
    abstract protected function getSchemaComponentClassesToSkip(): array;

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
        return ClassHelpers::getClassPSR4Namespace(get_called_class());
    }

    /**
     * Plugin set-up, executed immediately when loading the plugin.
     */
    public function setup(): void
    {
        // Functions to execute when activating/deactivating the plugin
        \register_deactivation_hook($this->getPluginFile(), $this->deactivate(...));
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
        \register_activation_hook($this->getPluginFile(), $this->activate(...));
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

        /**
         * No need to invalidate the cache here anymore,
         * since AbstractMainPlugin already invalidates it
         * for ANY deactivated plugin.
         */
        // $this->purgeContainer();
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
    public function pluginJustActivated(): void
    {
    }

    /**
     * Execute logic after the plugin/extension has just been updated
     */
    public function pluginJustUpdated(string $storedVersion): void
    {
    }
}
