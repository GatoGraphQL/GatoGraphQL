<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\PluginSkeleton;

use Exception;
use GatoGraphQL\ExternalDependencyWrappers\Symfony\Component\Exception\IOException;
use GatoGraphQL\ExternalDependencyWrappers\Symfony\Component\Filesystem\FilesystemWrapper;
use GatoGraphQL\GatoGraphQL\App;
use GatoGraphQL\GatoGraphQL\AppThread;
use GatoGraphQL\GatoGraphQL\Constants\BlockAttributeNames;
use GatoGraphQL\GatoGraphQL\Constants\BlockAttributeValues;
use GatoGraphQL\GatoGraphQL\Container\InternalGraphQLServerContainerBuilderFactory;
use GatoGraphQL\GatoGraphQL\Container\InternalGraphQLServerSystemContainerBuilderFactory;
use GatoGraphQL\GatoGraphQL\Facades\UserSettingsManagerFacade;
use GatoGraphQL\GatoGraphQL\Marketplace\Constants\LicenseStatus;
use GatoGraphQL\GatoGraphQL\Module;
use GatoGraphQL\GatoGraphQL\ModuleConfiguration;
use GatoGraphQL\GatoGraphQL\PluginApp;
use GatoGraphQL\GatoGraphQL\PluginAppGraphQLServerNames;
use GatoGraphQL\GatoGraphQL\PluginAppHooks;
use GatoGraphQL\GatoGraphQL\Services\Blocks\EndpointSchemaConfigurationBlock;
use GatoGraphQL\GatoGraphQL\Services\Blocks\PersistedQueryEndpointAPIHierarchyBlock;
use GatoGraphQL\GatoGraphQL\Services\Blocks\PersistedQueryEndpointGraphiQLBlock;
use GatoGraphQL\GatoGraphQL\Services\Blocks\PersistedQueryEndpointOptionsBlock;
use GatoGraphQL\GatoGraphQL\Services\Blocks\SchemaConfigMutationSchemeBlock;
use GatoGraphQL\GatoGraphQL\Services\Blocks\SchemaConfigPayloadTypesForMutationsBlock;
use GatoGraphQL\GatoGraphQL\Services\CustomPostTypes\GraphQLPersistedQueryEndpointCustomPostType;
use GatoGraphQL\GatoGraphQL\Services\CustomPostTypes\GraphQLSchemaConfigurationCustomPostType;
use GatoGraphQL\GatoGraphQL\Services\Taxonomies\GraphQLEndpointCategoryTaxonomy;
use GatoGraphQL\GatoGraphQL\Settings\Options;
use GatoGraphQL\GatoGraphQL\StateManagers\AppThreadHookManagerWrapper;
use GatoGraphQL\GatoGraphQL\StaticHelpers\SettingsHelpers;
use GraphQLByPoP\GraphQLServer\AppStateProviderServices\GraphQLServerAppStateProviderServiceInterface;
use GraphQLByPoP\GraphQLServer\Configuration\MutationSchemes;
use PoP\RootWP\AppLoader as WPDeferredAppLoader;
use PoP\RootWP\StateManagers\HookManager;
use PoP\Root\AppLoader as ImmediateAppLoader;

use PoP\Root\Environment as RootEnvironment;
use PoP\Root\Facades\Instances\InstanceManagerFacade;
use PoP\Root\Helpers\ClassHelpers;
use PoP\Root\Module\ModuleInterface;
use WP_Error;
use WP_Upgrader;
use function __;
use function add_action;
use function do_action;
use function get_called_class;
use function get_option;
use function is_admin;
use function update_option;

abstract class AbstractMainPlugin extends AbstractPlugin implements MainPluginInterface
{
    /**
     * If there is any error when initializing the plugin,
     * set this var to `true` to stop loading it and show an error message.
     */
    private ?Exception $inititalizationException = null;

    protected MainPluginInitializationConfigurationInterface $pluginInitializationConfiguration;

    public function __construct(
        string $pluginFile, /** The main plugin file */
        string $pluginVersion,
        ?string $pluginName = null,
        ?string $commitHash = null,
        ?MainPluginInitializationConfigurationInterface $pluginInitializationConfiguration = null,
    ) {
        parent::__construct(
            $pluginFile,
            $pluginVersion,
            $pluginName,
            $commitHash,
        );
        $this->pluginInitializationConfiguration = $pluginInitializationConfiguration ?? $this->createInitializationConfiguration();
    }

    protected function createInitializationConfiguration(): MainPluginInitializationConfigurationInterface
    {
        $pluginInitializationConfigurationClass = $this->getPluginInitializationConfigurationClass();
        return new $pluginInitializationConfigurationClass();
    }

    /**
     * PluginInitializationConfiguration class for the Plugin
     *
     * @return class-string<MainPluginInitializationConfigurationInterface>
     */
    protected function getPluginInitializationConfigurationClass(): string
    {
        $classNamespace = ClassHelpers::getClassPSR4Namespace(get_called_class());
        /** @var class-string<MainPluginInitializationConfigurationInterface> */
        return $classNamespace . '\\PluginInitializationConfiguration';
    }

    /**
     * PluginInfo class name for the Plugin
     */
    protected function getPluginInfoClassName(): ?string
    {
        return 'PluginInfo';
    }

    /**
     * Configure the plugin.
     * This defines hooks to set environment variables,
     * so must be executed before those hooks are triggered for first time
     * (in ModuleConfiguration classes)
     */
    protected function callPluginInitializationConfiguration(): void
    {
        $this->pluginInitializationConfiguration->initialize();
    }

    /**
     * Add configuration for the Module classes
     *
     * @return array<class-string<ModuleInterface>,mixed> [key]: Module class, [value]: Configuration
     */
    public function getModuleClassConfiguration(): array
    {
        return $this->pluginInitializationConfiguration->getModuleClassConfiguration();
    }

    /**
     * Add schema Module classes to skip initializing
     *
     * @return array<class-string<ModuleInterface>> List of `Module` class which must not initialize their Schema services
     */
    public function getSchemaModuleClassesToSkip(): array
    {
        return $this->pluginInitializationConfiguration->getSchemaModuleClassesToSkip();
    }

    /**
     * This method dumps the container whenever activating a depended-upon
     * plugin, or deactivating a Gato GraphQL extension.
     *
     * When activating an extension plugin for Gato GraphQL,
     * the container will be regenerated through method
     * `handleNewActivations` (in this same class).
     *
     * When deactivating an extension, the cached service container
     * must be dumped, so that they can be regenerated.
     *
     * Likewise, when activating/deactivating a depended-upon plugin
     * (eg: "Events Manager", required by "Gato GraphQL - Events Manager")
     * the container must be regenerated.
     */
    public function maybeRegenerateContainerWhenPluginActivatedOrDeactivated(string $pluginFile): bool
    {
        if (in_array($pluginFile, $this->getDependentOnPluginFiles())) {
            $this->purgeContainer();
            return true;
        }

        $extensionManager = PluginApp::getExtensionManager();
        if (in_array($pluginFile, $extensionManager->getInactiveExtensionsDependedUponPluginFiles())) {
            $this->purgeContainer();
            return true;
        }

        /**
         * Check that the activated/deactivated plugin is
         * a Gato GraphQL extension, or any plugin depended-upon
         * by any extension.
         */
        $extensionBaseNameInstances = $extensionManager->getExtensions();
        foreach ($extensionBaseNameInstances as $extensionBaseName => $extensionInstance) {
            if (
                $extensionBaseName === $pluginFile
                || in_array($pluginFile, $extensionInstance->getDependentOnPluginFiles())
            ) {
                $this->purgeContainer();
                return true;
            }
        }

        return false;
    }

    /**
     * When updating a plugin from the wp-admin Updates screen,
     * purge the container to avoid the plugin being inactive,
     * yet the compiled container still loads its code.
     *
     * @param array<string,mixed> $options
     *
     * @see https://developer.wordpress.org/reference/hooks/upgrader_process_complete/
     */
    public function maybeRegenerateContainerWhenPluginUpdated(
        WP_Upgrader $upgrader_object,
        array $options,
    ): void {
        if ($options['action'] !== 'update' || $options['type'] !== 'plugin') {
            return;
        }
        /** @var string $pluginFile */
        foreach ($options['plugins'] as $pluginFile) {
            $purgedContainer = $this->maybeRegenerateContainerWhenPluginActivatedOrDeactivated($pluginFile);
            if ($purgedContainer) {
                return;
            }
        }
    }

    /**
     * When deactivating the main plugin or an extension,
     * remove the stored version from the DB
     */
    public function maybeRemoveStoredPluginVersionWhenPluginDeactivated(string $pluginFile): void
    {
        $removePluginFileFromStoredPluginVersions = false;

        // Check if this is the main plugin
        if (PluginApp::getMainPlugin()->getPluginFile() === $pluginFile) {
            $removePluginFileFromStoredPluginVersions = true;
        } else {
            // Check if this is any extension plugin
            $extensionManager = PluginApp::getExtensionManager();
            $extensionBaseNames = array_keys($extensionManager->getExtensions());
            $removePluginFileFromStoredPluginVersions = in_array($pluginFile, $extensionBaseNames);
        }

        if (!$removePluginFileFromStoredPluginVersions) {
            return;
        }

        $this->removePluginFileFromStoredPluginVersions($pluginFile);
    }

    protected function removePluginFileFromStoredPluginVersions(string $pluginBaseName): void
    {
        $storedPluginVersions = get_option(PluginOptions::PLUGIN_VERSIONS, []);
        unset($storedPluginVersions[$pluginBaseName]);
        update_option(PluginOptions::PLUGIN_VERSIONS, $storedPluginVersions);
    }


    /**
     * Remove the cached folders (service container and config),
     * and regenerate the timestamp
     */
    protected function purgeContainer(): void
    {
        $this->removeCachedFolders();

        // Regenerate the timestamp
        $userSettingsManager = UserSettingsManagerFacade::getInstance();
        $userSettingsManager->storeContainerTimestamp();

        // Store empty settings
        $this->maybeStoreEmptySettings();
    }

    /**
     * If accessing the plugin for first time, save empty settings
     * for ALL the options, so that hook "update_option_{$option}"
     * is triggered the first time the user saves the settings
     * (otherwise the entry is created, and the hook is not fired).
     */
    protected function maybeStoreEmptySettings(): void
    {
        foreach ($this->getAllSettingsOptions() as $option) {
            if (get_option($option) !== false) {
                continue;
            }
            update_option($option, []);
        }
    }

    /**
     * @return string[]
     */
    protected function getAllSettingsOptions(): array
    {
        return [
            Options::SCHEMA_CONFIGURATION,
            Options::ENDPOINT_CONFIGURATION,
            Options::SERVER_CONFIGURATION,
            Options::PLUGIN_CONFIGURATION,
            Options::API_KEYS,
            Options::PLUGIN_MANAGEMENT,
        ];
    }

    /**
     * Remove the cached folders
     */
    protected function removeCachedFolders(): void
    {
        $fileSystemWrapper = new FilesystemWrapper();
        try {
            /** @var MainPluginInfoInterface */
            $mainPluginInfo = PluginApp::getMainPlugin()->getInfo();
            $fileSystemWrapper->remove($mainPluginInfo->getCacheDir());
        } catch (IOException) {
            // If the folder does not exist, do nothing
        }
    }

    /**
     * Remove permalinks when deactivating the plugin
     *
     * @see https://developer.wordpress.org/plugins/plugin-basics/activation-deactivation-hooks/
     */
    public function deactivate(): void
    {
        parent::deactivate();

        // Remove the timestamps
        $this->removeTimestamps();
    }

    /**
     * Regenerate the timestamps
     */
    protected function removeTimestamps(): void
    {
        $userSettingsManager = UserSettingsManagerFacade::getInstance();
        $userSettingsManager->removeTimestamps();
    }

    /**
     * There are three stages for the main plugin, and for each extension plugin:
     * `setup`, `initialize` and `boot`.
     *
     * This is because:
     *
     * - The plugin must execute its logic before the extensions
     * - The services can't be booted before all services have been initialized
     *
     * To attain the needed order, we execute them using hook "plugins_loaded",
     * with all the priorities defined in PluginLifecyclePriorities
     */
    public function setup(): void
    {
        parent::setup();

        /**
         * Operations to do when activating/deactivating plugins
         */
        add_action(
            'activate_plugin',
            function (string $pluginFile): void {
                $this->maybeRegenerateContainerWhenPluginActivatedOrDeactivated($pluginFile);
            }
        );
        add_action(
            'deactivate_plugin',
            function (string $pluginFile): void {
                $this->maybeRegenerateContainerWhenPluginActivatedOrDeactivated($pluginFile);
            }
        );
        add_action('deactivate_plugin', $this->maybeRemoveStoredPluginVersionWhenPluginDeactivated(...));

        add_action('upgrader_process_complete', $this->maybeRegenerateContainerWhenPluginUpdated(...), 10, 2);

        // Dump the container whenever a new plugin or extension is activated
        $this->handleNewActivations();

        // Initialize the procedure to register/initialize plugin and extensions
        $this->executeSetupProcedure();
    }

    /**
     * Check if the plugin has just been activated (for first time) or updated,
     * or if an extension has just been activated (for first time).
     *
     * Regenerate the container here, and not in the `activate` function,
     * because `activate` doesn't get called within the "plugins_loaded" hook.
     * This is not an issue to register the main plugin, but it is for extensions,
     * since they need to ask if the main plugin exists (since AbstractExtension
     * is located there), and that can only be done in "plugins_loaded".
     */
    protected function handleNewActivations(): void
    {
        /**
         * Logic to check if the main plugin or any extension has
         * just been activated (for first time) or updated.
         */
        add_action(
            PluginAppHooks::INITIALIZE_APP,
            function (string $pluginAppGraphQLServerName): void {
                if (
                    $pluginAppGraphQLServerName === PluginAppGraphQLServerNames::INTERNAL
                    || !is_admin()
                    || $this->inititalizationException !== null
                ) {
                    return;
                }
                $storedPluginVersions = get_option(PluginOptions::PLUGIN_VERSIONS, []);
                $registeredExtensionBaseNameInstances = PluginApp::getExtensionManager()->getExtensions();

                // Check if the main plugin has been activated or updated
                $isMainPluginJustFirstTimeActivated = !isset($storedPluginVersions[$this->pluginBaseName]);
                $isMainPluginJustUpdated = !$isMainPluginJustFirstTimeActivated && $storedPluginVersions[$this->pluginBaseName] !== $this->getPluginVersionWithCommitHash();

                // Check if any extension has been activated or updated
                $justFirstTimeActivatedExtensions = [];
                $justUpdatedExtensions = [];
                foreach ($registeredExtensionBaseNameInstances as $extensionBaseName => $extensionInstance) {
                    if (!isset($storedPluginVersions[$extensionBaseName])) {
                        $justFirstTimeActivatedExtensions[$extensionBaseName] = $extensionInstance;
                    } elseif ($storedPluginVersions[$extensionBaseName] !== $extensionInstance->getPluginVersionWithCommitHash()) {
                        $justUpdatedExtensions[$extensionBaseName] = $extensionInstance;
                    }
                }

                // If there were no changes, nothing to do
                if (
                    !$isMainPluginJustFirstTimeActivated
                    && !$isMainPluginJustUpdated
                    && $justFirstTimeActivatedExtensions === []
                    && $justUpdatedExtensions === []
                ) {
                    return;
                }

                // Recalculate the updated entry and update on the DB
                $storedPluginVersions[$this->pluginBaseName] = $this->getPluginVersionWithCommitHash();
                foreach (array_merge($justFirstTimeActivatedExtensions, $justUpdatedExtensions) as $extensionBaseName => $extensionInstance) {
                    $storedPluginVersions[$extensionBaseName] = $extensionInstance->getPluginVersionWithCommitHash();
                }
                update_option(PluginOptions::PLUGIN_VERSIONS, $storedPluginVersions);

                // Regenerate the timestamp, to generate the service container
                $this->purgeContainer();

                /**
                 * Enable to implement custom additional functionality (eg: show admin notice with changelog)
                 * Watch out! Execute at the very end, just in case they need to access the service container,
                 * which is not initialized yet (eg: for calling `$userSettingsManager->getSetting`)
                 */
                add_action(
                    PluginAppHooks::INITIALIZE_APP,
                    function () use (
                        $isMainPluginJustFirstTimeActivated,
                        $isMainPluginJustUpdated,
                        $storedPluginVersions,
                        $justFirstTimeActivatedExtensions,
                        $justUpdatedExtensions,
                    ): void {
                        if ($isMainPluginJustFirstTimeActivated) {
                            $this->pluginJustFirstTimeActivated();
                        } elseif ($isMainPluginJustUpdated) {
                            $this->pluginJustUpdated($storedPluginVersions[$this->pluginBaseName]);
                        }
                        foreach ($justFirstTimeActivatedExtensions as $extensionBaseName => $extensionInstance) {
                            $extensionInstance->pluginJustFirstTimeActivated();
                        }
                        foreach ($justUpdatedExtensions as $extensionBaseName => $extensionInstance) {
                            $extensionInstance->pluginJustUpdated($storedPluginVersions[$extensionBaseName]);
                        }
                    },
                    PluginLifecyclePriorities::AFTER_EVERYTHING
                );

                /**
                 * Execute at the end of hook "init", because
                 * `AbstractCustomPostType` initializes the
                 * custom post types on this hook, and the CPT
                 * also adds rewrites that must be flushed.
                 *
                 * Watch out! Can't do `flush_rewrite_rules(...)`,
                 * because then Rector throws Exception:
                 *
                 *   Call to undefined method PhpParser\PrettyPrinter\Standard::pPHPStan_Node_FunctionCallableNode()". On line: 499
                 */
                add_action('init', 'flush_rewrite_rules', PHP_INT_MAX);
            },
            PluginLifecyclePriorities::HANDLE_NEW_ACTIVATIONS
        );
    }

    /**
     * Execute logic after the plugin/extension has just been activated
     * (for first time)
     */
    public function pluginJustFirstTimeActivated(): void
    {
        parent::pluginJustFirstTimeActivated();

        /**
         * Taxonomies are registered on "init", hence must insert
         * data only after that.
         *
         * @see layers/GatoGraphQLForWP/plugins/gatographql/src/Services/Taxonomies/AbstractTaxonomy.php
         */
        \add_action(
            'init',
            $this->maybeInstallInitialData(...),
            PHP_INT_MAX
        );
    }

    /**
     * Install initial data:
     * 
     * - Persisted Queries with common admin tasks
     */
    protected function maybeInstallInitialData(): void
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        if (!$moduleConfiguration->installInitialData()) {
            return;
        }
        
        $instanceManager = InstanceManagerFacade::getInstance();
        /** @var GraphQLSchemaConfigurationCustomPostType */
        $graphQLSchemaConfigurationCustomPostType = $instanceManager->getInstance(GraphQLSchemaConfigurationCustomPostType::class);
        /** @var SchemaConfigMutationSchemeBlock */
        $schemaConfigMutationSchemeBlock = $instanceManager->getInstance(SchemaConfigMutationSchemeBlock::class);
        /** @var SchemaConfigPayloadTypesForMutationsBlock */
        $schemaConfigPayloadTypesForMutationsBlock = $instanceManager->getInstance(SchemaConfigPayloadTypesForMutationsBlock::class);
        
        /**
         * First create the Schema Configurations
         */
        $nestedMutationsBlockData = [
            'blockName' => $schemaConfigMutationSchemeBlock->getBlockFullName(),
            'innerContent' => [],
            'attrs' => [
                SchemaConfigMutationSchemeBlock::ATTRIBUTE_NAME_MUTATION_SCHEME => MutationSchemes::NESTED_WITH_REDUNDANT_ROOT_FIELDS,
            ]
        ];
        $nestedMutationsSchemaConfigurationCustomPostID = \wp_insert_post([
			'post_status' => 'publish',
			'post_type' => $graphQLSchemaConfigurationCustomPostType->getCustomPostType(),
			'post_title' => \__('Nested mutations', 'gatographql'),
			'post_content' => serialize_blocks([
                $nestedMutationsBlockData,
            ]),
        ]);
        $entityAsPayloadTypeBlockData = [
            'blockName' => $schemaConfigPayloadTypesForMutationsBlock->getBlockFullName(),
            'innerContent' => [],
            'attrs' => [
                BlockAttributeNames::ENABLED_CONST => BlockAttributeValues::DISABLED,
            ]
        ];
        $nestedMutationsPlusEntityAsPayloadTypeSchemaConfigurationCustomPostID = \wp_insert_post([
			'post_status' => 'publish',
			'post_type' => $graphQLSchemaConfigurationCustomPostType->getCustomPostType(),
			'post_title' => \__('Nested mutations + Entity as mutation payload type', 'gatographql'),
            'post_content' => serialize_blocks([
                $nestedMutationsBlockData,
                $entityAsPayloadTypeBlockData,
            ]),
        ]);


        /**
         * Create Endpoint Categories
         */
        /** @var GraphQLEndpointCategoryTaxonomy */
        $graphQLEndpointCategoryTaxonomy = $instanceManager->getInstance(GraphQLEndpointCategoryTaxonomy::class);

        $adminPersistedQueryTaxInputData = [
            $graphQLEndpointCategoryTaxonomy->getTaxonomy() => [],
        ];
        $adminEndpointCategoryOptions = [];
        $adminEndpointCategory = \wp_insert_term(
            \__('Admin', 'gatographql'),
            $graphQLEndpointCategoryTaxonomy->getTaxonomy(),
            [
                'description' => \__('Internal admin tasks', 'gatographql'),
            ]
        );
        if (!($adminEndpointCategory instanceof WP_Error)) {
            $adminEndpointCategoryID = $adminEndpointCategory['term_id'];
            $adminPersistedQueryTaxInputData[$graphQLEndpointCategoryTaxonomy->getTaxonomy()][] = $adminEndpointCategoryID;
            $adminEndpointCategoryOptions['parent'] = $adminEndpointCategoryID;
        }

        $adminReportPersistedQueryTaxInputData = $adminPersistedQueryTaxInputData;
        $adminReportEndpointCategory = \wp_insert_term(
            \__('Report', 'gatographql'),
            $graphQLEndpointCategoryTaxonomy->getTaxonomy(),
            array_merge(
                $adminEndpointCategoryOptions,
                [
                    'description' => \__('Visualize data', 'gatographql'),
                ]
            )
        );
        if (!($adminReportEndpointCategory instanceof WP_Error)) {
            $adminReportEndpointCategoryID = $adminReportEndpointCategory['term_id'];
            $adminReportPersistedQueryTaxInputData[$graphQLEndpointCategoryTaxonomy->getTaxonomy()][] = $adminReportEndpointCategoryID;
        }

        $adminTransformPersistedQueryTaxInputData = $adminPersistedQueryTaxInputData;
        $adminTransformEndpointCategory = \wp_insert_term(
            \__('Transform', 'gatographql'),
            $graphQLEndpointCategoryTaxonomy->getTaxonomy(),
            array_merge(
                $adminEndpointCategoryOptions,
                [
                    'description' => \__('Transform data', 'gatographql'),
                ]
            )
        );
        if (!($adminTransformEndpointCategory instanceof WP_Error)) {
            $adminTransformEndpointCategoryID = $adminTransformEndpointCategory['term_id'];
            $adminTransformPersistedQueryTaxInputData[$graphQLEndpointCategoryTaxonomy->getTaxonomy()][] = $adminTransformEndpointCategoryID;
        }

        $adminImportPersistedQueryTaxInputData = $adminPersistedQueryTaxInputData;
        $adminImportEndpointCategory = \wp_insert_term(
            \__('Import', 'gatographql'),
            $graphQLEndpointCategoryTaxonomy->getTaxonomy(),
            array_merge(
                $adminEndpointCategoryOptions,
                [
                    'description' => \__('Import data', 'gatographql'),
                ]
            )
        );
        if (!($adminImportEndpointCategory instanceof WP_Error)) {
            $adminImportEndpointCategoryID = $adminImportEndpointCategory['term_id'];
            $adminImportPersistedQueryTaxInputData[$graphQLEndpointCategoryTaxonomy->getTaxonomy()][] = $adminImportEndpointCategoryID;
        }

        $adminNotifyPersistedQueryTaxInputData = $adminPersistedQueryTaxInputData;
        $adminNotifyEndpointCategory = \wp_insert_term(
            \__('Notify', 'gatographql'),
            $graphQLEndpointCategoryTaxonomy->getTaxonomy(),
            array_merge(
                $adminEndpointCategoryOptions,
                [
                    'description' => \__('Send notifications', 'gatographql'),
                ]
            )
        );
        if (!($adminNotifyEndpointCategory instanceof WP_Error)) {
            $adminNotifyEndpointCategoryID = $adminNotifyEndpointCategory['term_id'];
            $adminNotifyPersistedQueryTaxInputData[$graphQLEndpointCategoryTaxonomy->getTaxonomy()][] = $adminNotifyEndpointCategoryID;
        }

        $adminAutomatePersistedQueryTaxInputData = $adminPersistedQueryTaxInputData;
        $adminAutomateEndpointCategory = \wp_insert_term(
            \__('Automate', 'gatographql'),
            $graphQLEndpointCategoryTaxonomy->getTaxonomy(),
            array_merge(
                $adminEndpointCategoryOptions,
                [
                    'description' => \__('Automations', 'gatographql'),
                ]
            )
        );
        if (!($adminAutomateEndpointCategory instanceof WP_Error)) {
            $adminAutomateEndpointCategoryID = $adminAutomateEndpointCategory['term_id'];
            $adminAutomatePersistedQueryTaxInputData[$graphQLEndpointCategoryTaxonomy->getTaxonomy()][] = $adminAutomateEndpointCategoryID;
        }

        $adminDispatchPersistedQueryTaxInputData = $adminPersistedQueryTaxInputData;
        $adminDispatchEndpointCategory = \wp_insert_term(
            \__('Dispatch', 'gatographql'),
            $graphQLEndpointCategoryTaxonomy->getTaxonomy(),
            array_merge(
                $adminEndpointCategoryOptions,
                [
                    'description' => \__('Send data to services', 'gatographql'),
                ]
            )
        );
        if (!($adminDispatchEndpointCategory instanceof WP_Error)) {
            $adminDispatchEndpointCategoryID = $adminDispatchEndpointCategory['term_id'];
            $adminDispatchPersistedQueryTaxInputData[$graphQLEndpointCategoryTaxonomy->getTaxonomy()][] = $adminDispatchEndpointCategoryID;
        }

        $adminFetchPersistedQueryTaxInputData = $adminPersistedQueryTaxInputData;
        $adminFetchEndpointCategory = \wp_insert_term(
            \__('Fetch', 'gatographql'),
            $graphQLEndpointCategoryTaxonomy->getTaxonomy(),
            array_merge(
                $adminEndpointCategoryOptions,
                [
                    'description' => \__('Fetch data from services', 'gatographql'),
                ]
            )
        );
        if (!($adminFetchEndpointCategory instanceof WP_Error)) {
            $adminFetchEndpointCategoryID = $adminFetchEndpointCategory['term_id'];
            $adminFetchPersistedQueryTaxInputData[$graphQLEndpointCategoryTaxonomy->getTaxonomy()][] = $adminFetchEndpointCategoryID;
        }

        $adminGatewayPersistedQueryTaxInputData = $adminPersistedQueryTaxInputData;
        $adminGatewayEndpointCategory = \wp_insert_term(
            \__('Gateway', 'gatographql'),
            $graphQLEndpointCategoryTaxonomy->getTaxonomy(),
            array_merge(
                $adminEndpointCategoryOptions,
                [
                    'description' => \__('API Gateway', 'gatographql'),
                ]
            )
        );
        if (!($adminGatewayEndpointCategory instanceof WP_Error)) {
            $adminGatewayEndpointCategoryID = $adminGatewayEndpointCategory['term_id'];
            $adminGatewayPersistedQueryTaxInputData[$graphQLEndpointCategoryTaxonomy->getTaxonomy()][] = $adminGatewayEndpointCategoryID;
        }

        $adminSyncPersistedQueryTaxInputData = $adminPersistedQueryTaxInputData;
        $adminSyncEndpointCategory = \wp_insert_term(
            \__('Sync', 'gatographql'),
            $graphQLEndpointCategoryTaxonomy->getTaxonomy(),
            array_merge(
                $adminEndpointCategoryOptions,
                [
                    'description' => \__('Synchronize data across sites', 'gatographql'),
                ]
            )
        );
        if (!($adminSyncEndpointCategory instanceof WP_Error)) {
            $adminSyncEndpointCategoryID = $adminSyncEndpointCategory['term_id'];
            $adminSyncPersistedQueryTaxInputData[$graphQLEndpointCategoryTaxonomy->getTaxonomy()][] = $adminSyncEndpointCategoryID;
        }

        $adminWebhookPersistedQueryTaxInputData = $adminPersistedQueryTaxInputData;
        $adminWebhookEndpointCategory = \wp_insert_term(
            \__('Webhook', 'gatographql'),
            $graphQLEndpointCategoryTaxonomy->getTaxonomy(),
            array_merge(
                $adminEndpointCategoryOptions,
                [
                    'description' => \__('Process incoming data via Webhooks', 'gatographql'),
                ]
            )
        );
        if (!($adminWebhookEndpointCategory instanceof WP_Error)) {
            $adminWebhookEndpointCategoryID = $adminWebhookEndpointCategory['term_id'];
            $adminWebhookPersistedQueryTaxInputData[$graphQLEndpointCategoryTaxonomy->getTaxonomy()][] = $adminWebhookEndpointCategoryID;
        }


        /**
         * Then create the ancestor Persisted Queries to organize them
         */
        /** @var GraphQLPersistedQueryEndpointCustomPostType */
        $graphQLPersistedQueryEndpointCustomPostType = $instanceManager->getInstance(GraphQLPersistedQueryEndpointCustomPostType::class);
        /** @var PersistedQueryEndpointGraphiQLBlock */
        $persistedQueryEndpointGraphiQLBlock = $instanceManager->getInstance(PersistedQueryEndpointGraphiQLBlock::class);
        /** @var EndpointSchemaConfigurationBlock */
        $endpointSchemaConfigurationBlock = $instanceManager->getInstance(EndpointSchemaConfigurationBlock::class);
        /** @var PersistedQueryEndpointOptionsBlock */
        $persistedQueryEndpointOptionsBlock = $instanceManager->getInstance(PersistedQueryEndpointOptionsBlock::class);
        /** @var PersistedQueryEndpointAPIHierarchyBlock */
        $persistedQueryEndpointAPIHierarchyBlock = $instanceManager->getInstance(PersistedQueryEndpointAPIHierarchyBlock::class);

        $adminPersistedQueryOptions = [
			'post_status' => 'private',
			'post_type' => $graphQLPersistedQueryEndpointCustomPostType->getCustomPostType(),
        ];
        $adminAncestorPersistedQueryCustomPostID = \wp_insert_post(array_merge(
            $adminPersistedQueryOptions,
            [
                'post_title' => \__('Admin', 'gatographql'),
                'post_excerpt' => \__('Execute admin tasks', 'gatographql'),
                'tax_input' => $adminPersistedQueryTaxInputData,
                'post_content' => serialize_blocks([
                    [
                        'blockName' => $persistedQueryEndpointGraphiQLBlock->getBlockFullName(),
                        'innerContent' => [],
                    ],
                    [
                        'blockName' => $endpointSchemaConfigurationBlock->getBlockFullName(),
                        'innerContent' => [],
                    ],
                    [
                        'blockName' => $persistedQueryEndpointOptionsBlock->getBlockFullName(),
                        'innerContent' => [],
                        'attrs' => [
                            BlockAttributeNames::IS_ENABLED => false,
                        ]
                    ],
                    [
                        'blockName' => $persistedQueryEndpointAPIHierarchyBlock->getBlockFullName(),
                        'innerContent' => [],
                    ],
                ]),
            ]
        ));
        $adminReportAncestorPersistedQueryCustomPostID = \wp_insert_post(array_merge(
            $adminPersistedQueryOptions,
            [
                'post_title' => \__('Report', 'gatographql'),
                'post_excerpt' => \__('Queries to visualize data', 'gatographql'),
                'tax_input' => $adminPersistedQueryTaxInputData,
                'post_content' => serialize_blocks([
                    [
                        'blockName' => $persistedQueryEndpointGraphiQLBlock->getBlockFullName(),
                        'innerContent' => [],
                    ],
                    [
                        'blockName' => $endpointSchemaConfigurationBlock->getBlockFullName(),
                        'innerContent' => [],
                    ],
                    [
                        'blockName' => $persistedQueryEndpointOptionsBlock->getBlockFullName(),
                        'innerContent' => [],
                        'attrs' => [
                            BlockAttributeNames::IS_ENABLED => false,
                        ]
                    ],
                    [
                        'blockName' => $persistedQueryEndpointAPIHierarchyBlock->getBlockFullName(),
                        'innerContent' => [],
                    ],
                ]),
            ]
        ));

        /**
         * Finally create the Persisted Queries
         */        
    }

    /**
     * There are three stages for the main plugin, and for each extension plugin:
     * `setup`, `initialize` and `boot`.
     *
     * This is because:
     *
     * - The plugin must execute its logic before the extensions
     * - The services can't be booted before all services have been initialized
     *
     * To attain the needed order, we execute them using hook "plugins_loaded",
     * with all the priorities defined in PluginLifecyclePriorities
     */
    final protected function executeSetupProcedure(): void
    {
        /**
         * Wait until "plugins_loaded" to initialize the plugin, because:
         *
         * - ModuleListTableAction requires `wp_verify_nonce`, loaded in pluggable.php
         * - Allow other plugins to inject their own functionality
         */
        add_action(
            PluginAppHooks::INITIALIZE_APP,
            function (string $pluginAppGraphQLServerName): void {
                /**
                 * The "external" (i.e. standard) server has not been initialized yet,
                 * hence there can be no Initialization Exception
                 */
                if (
                    $pluginAppGraphQLServerName === PluginAppGraphQLServerNames::INTERNAL
                    && $this->inititalizationException !== null
                ) {
                    return;
                }
                App::setAppThread(new AppThread($pluginAppGraphQLServerName));
                $hookManager = new HookManager();
                /**
                 * Boot the external GraphQL server only after the
                 * WordPress hooks have triggered, but the internal
                 * GraphQL server immediately (as by then all those
                 * hooks will have been triggered, and so it'd not
                 * be initialized)
                 */
                $isInternalGraphQLServer = $pluginAppGraphQLServerName === PluginAppGraphQLServerNames::INTERNAL;
                App::initialize(
                    $isInternalGraphQLServer
                        ? new ImmediateAppLoader()
                        : new WPDeferredAppLoader(),
                    $isInternalGraphQLServer
                        ? new AppThreadHookManagerWrapper($hookManager)
                        : $hookManager,
                    null,
                    $isInternalGraphQLServer
                        ? new InternalGraphQLServerContainerBuilderFactory()
                        : null,
                    $isInternalGraphQLServer
                        ? new InternalGraphQLServerSystemContainerBuilderFactory()
                        : null
                );
            },
            PluginLifecyclePriorities::INITIALIZE_APP
        );
        add_action(
            PluginAppHooks::INITIALIZE_APP,
            function (): void {
                if ($this->inititalizationException !== null) {
                    return;
                }
                $this->initialize();
            },
            PluginLifecyclePriorities::INITIALIZE_PLUGIN
        );
        add_action(
            PluginAppHooks::INITIALIZE_APP,
            function (): void {
                if ($this->inititalizationException !== null) {
                    return;
                }
                do_action(PluginLifecycleHooks::INITIALIZE_EXTENSION);
            },
            PluginLifecyclePriorities::INITIALIZE_EXTENSIONS
        );
        add_action(
            PluginAppHooks::INITIALIZE_APP,
            function (): void {
                if ($this->inititalizationException !== null) {
                    return;
                }
                $this->initializeModules();
            },
            PluginLifecyclePriorities::CONFIGURE_COMPONENTS
        );
        add_action(
            PluginAppHooks::INITIALIZE_APP,
            function (string $pluginAppGraphQLServerName): void {
                if ($this->inititalizationException !== null) {
                    return;
                }
                $this->bootSystem($pluginAppGraphQLServerName);
            },
            PluginLifecyclePriorities::BOOT_SYSTEM
        );
        add_action(
            PluginAppHooks::INITIALIZE_APP,
            function (string $pluginAppGraphQLServerName): void {
                if ($this->inititalizationException !== null) {
                    return;
                }
                $this->configure($pluginAppGraphQLServerName);
            },
            PluginLifecyclePriorities::CONFIGURE_PLUGIN
        );
        add_action(
            PluginAppHooks::INITIALIZE_APP,
            function (): void {
                if ($this->inititalizationException !== null) {
                    return;
                }
                do_action(PluginLifecycleHooks::CONFIGURE_EXTENSION);
            },
            PluginLifecyclePriorities::CONFIGURE_EXTENSIONS
        );
        add_action(
            PluginAppHooks::INITIALIZE_APP,
            function (string $pluginAppGraphQLServerName): void {
                if ($this->inititalizationException !== null) {
                    return;
                }
                $this->bootApplication($pluginAppGraphQLServerName);
            },
            PluginLifecyclePriorities::BOOT_APPLICATION
        );
        add_action(
            PluginAppHooks::INITIALIZE_APP,
            function (): void {
                if ($this->inititalizationException !== null) {
                    return;
                }
                $this->boot();
            },
            PluginLifecyclePriorities::BOOT_PLUGIN
        );
        add_action(
            PluginAppHooks::INITIALIZE_APP,
            function (): void {
                if ($this->inititalizationException !== null) {
                    return;
                }
                do_action(PluginLifecycleHooks::BOOT_EXTENSION);
            },
            PluginLifecyclePriorities::BOOT_EXTENSIONS
        );
        add_action(
            PluginAppHooks::INITIALIZE_APP,
            function (string $pluginAppGraphQLServerName): void {
                $this->handleInitializationException($pluginAppGraphQLServerName);
            },
            PHP_INT_MAX
        );
    }

    /**
     * Initialize the components
     */
    public function initializeModules(): void
    {
        App::getAppLoader()->initializeModules();

        /**
         * After initialized, and before booting,
         * allow the components to inject their own configuration
         */
        $this->configureComponents();
        do_action(PluginLifecycleHooks::CONFIGURE_EXTENSION_COMPONENTS);
    }

    /**
     * Boot the system
     */
    public function bootSystem(string $pluginAppGraphQLServerName): void
    {
        // If the service container has an error, Symfony DI will throw an exception
        try {
            // Boot all PoP components, from this plugin and all extensions
            $appLoader = App::getAppLoader();
            $appLoader->setContainerCacheConfiguration(
                $this->pluginInitializationConfiguration->getContainerCacheConfiguration($pluginAppGraphQLServerName)
            );
            $appLoader->bootSystem();

            // Custom logic
            $this->doBootSystem();
        } catch (Exception $e) {
            $this->inititalizationException = $e;
        }
    }

    /**
     * Custom function to boot the system. Override if needed
     */
    protected function doBootSystem(): void
    {
    }

    /**
     * Boot the application
     */
    public function bootApplication(string $pluginAppGraphQLServerName): void
    {
        // If the service container has an error, Symfony DI will throw an exception
        try {
            // Boot all PoP components, from this plugin and all extensions
            $appLoader = App::getAppLoader();
            $appLoader->bootApplication();

            /**
             * After booting the application, we can access the Application
             * Container services.
             *
             * ------------------------------------------------------------
             *
             * For the InternalGraphQLServer: explicitly set the required
             * state to execute GraphQL queries.
             *
             * For the Standard GraphQL Server there is no need, as it will
             * already produce this state from the AppStateProvider.
             *
             * @see layers/GatoGraphQLForWP/plugins/gatographql/src/State/AbstractGraphQLEndpointExecuterAppStateProvider.php
             *
             * Please notice: Setting the AppState as needed by GraphQL here
             * means that the InternalGraphQLServer is configured to always
             * process a GraphQL request, independently of what variables were
             * actually set in the request.
             *
             * But that's not the case for the standard server, which can then
             * also process other responses too (eg: it supports ?datastructure=rest).
             */
            if ($pluginAppGraphQLServerName === PluginAppGraphQLServerNames::INTERNAL) {
                $graphQLRequestAppState = $this->getGraphQLServerAppStateProviderService()->getGraphQLRequestAppState();
                $appLoader->setInitialAppState($graphQLRequestAppState);
            }

            $appLoader->bootApplicationModules();

            // Custom logic
            $this->doBootApplication();
        } catch (Exception $e) {
            $this->inititalizationException = $e;
        }
    }

    protected function getGraphQLServerAppStateProviderService(): GraphQLServerAppStateProviderServiceInterface
    {
        /** @var GraphQLServerAppStateProviderServiceInterface */
        return App::getContainer()->get(GraphQLServerAppStateProviderServiceInterface::class);
    }

    /**
     * Custom function to boot the application. Override if needed
     */
    protected function doBootApplication(): void
    {
    }

    /**
     * If in development, throw the exception.
     * If in production, show the error as an admin notice.
     */
    protected function handleInitializationException(string $pluginAppGraphQLServerName): void
    {
        if (
            $this->inititalizationException !== null
            && RootEnvironment::isApplicationEnvironmentDev()
        ) {
            throw $this->inititalizationException;
        }

        /**
         * Add the admin_notice error only once, by the Main AppThread
         */
        if ($pluginAppGraphQLServerName === PluginAppGraphQLServerNames::INTERNAL) {
            return;
        }

        add_action(
            'admin_notices',
            function (): void {
                if ($this->inititalizationException === null) {
                    return;
                }

                /** @var Exception */
                $inititalizationException = $this->inititalizationException;

                $errorMessage = sprintf(
                    '%s%s',
                    __('<p><em>(This message is visible only by the admin.)</em></p>', 'gatographql'),
                    sprintf(
                        __('<p>Something went wrong initializing plugin <strong>%s</strong> (so it has not been loaded):</p><code>%s</code><p>Stack trace:</p><pre>%s</pre>', 'gatographql'),
                        $this->pluginName,
                        $inititalizationException->getMessage(),
                        $inititalizationException->getTraceAsString()
                    )
                );
                $adminNotice_safe = sprintf(
                    '<div class="notice notice-error">%s</div>',
                    $errorMessage
                );
                echo $adminNotice_safe;
            }
        );
    }

    /**
     * Plugin's booting
     */
    public function boot(): void
    {
        parent::boot();

        \add_filter(
            'admin_body_class',
            function (string $classes): string {
                $extensions = PluginApp::getExtensionManager()->getExtensions();
                if ($extensions === []) {
                    return $classes;
                }

                $commercialExtensionActivatedLicenseObjectProperties = SettingsHelpers::getCommercialExtensionActivatedLicenseObjectProperties();
                foreach ($extensions as $extension) {
                    if (!$extension->isCommercial()) {
                        continue;
                    }
                    // Check that the extension has "active" status
                    $extensionCommercialExtensionActivatedLicenseObjectProperties = $commercialExtensionActivatedLicenseObjectProperties[$extension->getPluginSlug()] ?? null;
                    if ($extensionCommercialExtensionActivatedLicenseObjectProperties === null) {
                        continue;
                    }
                    if ($extensionCommercialExtensionActivatedLicenseObjectProperties->status !== LicenseStatus::ACTIVE) {
                        continue;
                    }
                    // The extension is registered and active!
                    return $classes . ' is-gatographql-customer';
                }
                return $classes;
            }
        );
    }
}
