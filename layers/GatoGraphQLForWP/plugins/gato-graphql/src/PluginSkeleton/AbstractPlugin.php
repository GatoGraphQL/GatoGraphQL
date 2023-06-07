<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\PluginSkeleton;

use GatoGraphQL\GatoGraphQL\Facades\Registries\CustomPostTypeRegistryFacade;
use GatoGraphQL\GatoGraphQL\PluginSkeleton\PluginInfoInterface;
use GatoGraphQL\GatoGraphQL\Services\CustomPostTypes\CustomPostTypeInterface;
use PoP\Root\App;
use PoP\Root\Helpers\ClassHelpers;
use PoP\Root\Module\ModuleInterface;

abstract class AbstractPlugin implements PluginInterface
{
    protected ?PluginInfoInterface $pluginInfo = null;

    protected string $pluginBaseName;
    protected string $pluginName;

    public function __construct(
        protected string $pluginFile, /** The main plugin file */
        protected string $pluginVersion,
        ?string $pluginName = null,
        protected ?string $commitHash = null, /** Useful for development to regenerate the container when testing the generated plugin */
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
     * Dependencies on other plugins, to regenerate the schema
     * when these are activated/deactived
     *
     * @return string[]
     */
    public function getDependentOnPluginFiles(): array
    {
        return [];
    }

    /**
     * Plugin version
     */
    public function getPluginVersion(): string
    {
        return $this->pluginVersion;
    }

    /**
     * Commit hash when merging PR in repo, injected during the CI run
     * when generating the .zip plugin.
     */
    public function getCommitHash(): ?string
    {
        return $this->commitHash;
    }

    /**
     * Plugin version + "#{commit hash}" (if it exists)
     */
    public function getPluginVersionWithCommitHash(): string
    {
        return $this->pluginVersion . ($this->commitHash ? '#' . $this->commitHash : $this->commitHash);
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
     *
     * @return class-string<PluginInfoInterface>|null
     */
    protected function getPluginInfoClass(): ?string
    {
        $classNamespace = ClassHelpers::getClassPSR4Namespace(\get_called_class());
        $pluginInfoClass = $classNamespace . '\\' . $this->getPluginInfoClassName();
        if (!class_exists($pluginInfoClass)) {
            return null;
        }
        /** @var class-string<PluginInfoInterface> */
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
        $this->initializeModuleClasses();
    }

    /**
     * Initialize Plugin's modules
     */
    protected function initializeModuleClasses(): void
    {
        // Initialize the containers
        $moduleClasses = $this->getModuleClassesToInitialize();
        App::getAppLoader()->addModuleClassesToInitialize($moduleClasses);
    }

    /**
     * After initialized, and before booting,
     * allow the modules to inject their own configuration
     */
    public function configureComponents(): void
    {
        // Set the plugin folder on the plugin's Module
        $pluginFolder = dirname($this->pluginFile);
        $this->getPluginModule()->setPluginFolder($pluginFolder);
    }

    /**
     * Plugin's Module
     */
    protected function getPluginModule(): PluginModuleInterface
    {
        /** @var PluginModuleInterface */
        return App::getModule($this->getModuleClass());
    }

    /**
     * Package's Module class, of type PluginModuleInterface.
     * By standard, it is "NamespaceOwner\Project\Module::class"
     *
     * @phpstan-return class-string<ModuleInterface>
     */
    protected function getModuleClass(): string
    {
        $classNamespace = ClassHelpers::getClassPSR4Namespace(\get_called_class());
        /** @var class-string<ModuleInterface> */
        return $classNamespace . '\\Module';
    }

    /**
     * Add Module classes to be initialized
     *
     * @return array<class-string<ModuleInterface>> List of `Module` class to initialize
     */
    abstract protected function getModuleClassesToInitialize(): array;

    /**
     * Plugin configuration
     */
    public function configure(string $pluginAppGraphQLServerName): void
    {
        /**
         * Configure the plugin. This defines hooks to set
         * environment variables, so must be executed before
         * those hooks are triggered for first time
         * (in ModuleConfiguration classes)
         */
        $this->callPluginInitializationConfiguration();

        /**
         * Only after initializing the System Container,
         * we can obtain the configuration (which may depend on hooks).
         */
        $appLoader = App::getAppLoader();
        $appLoader->addModuleClassConfiguration(
            $this->getModuleClassConfiguration()
        );

        $appLoader->addSchemaModuleClassesToSkip($this->getSchemaModuleClassesToSkip());
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
     * @return array<class-string<ModuleInterface>,mixed> [key]: Module class, [value]: Configuration
     */
    public function getModuleClassConfiguration(): array
    {
        return [];
    }

    /**
     * Add schema Module classes to skip initializing
     *
     * @return array<class-string<ModuleInterface>> List of `Module` class which must not initialize their Schema services
     */
    abstract protected function getSchemaModuleClassesToSkip(): array;

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
        // register_activation_hook($this->getPluginFile(), $this->activate(...));
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
