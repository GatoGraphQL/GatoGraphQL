<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\PluginSkeleton;

use Exception;
use GatoGraphQL\ExternalDependencyWrappers\Composer\Semver\SemverWrapper;
use GatoGraphQL\ExternalDependencyWrappers\Symfony\Component\Exception\IOException;
use GatoGraphQL\ExternalDependencyWrappers\Symfony\Component\Filesystem\FilesystemWrapper;
use GatoGraphQL\GatoGraphQL\App;
use GatoGraphQL\GatoGraphQL\AppThread;
use GatoGraphQL\GatoGraphQL\Constants\BlockAttributeNames;
use GatoGraphQL\GatoGraphQL\Constants\BlockAttributeValues;
use GatoGraphQL\GatoGraphQL\Constants\TutorialLessons;
use GatoGraphQL\GatoGraphQL\Constants\VirtualTutorialLessons;
use GatoGraphQL\GatoGraphQL\Container\InternalGraphQLServerContainerBuilderFactory;
use GatoGraphQL\GatoGraphQL\Container\InternalGraphQLServerSystemContainerBuilderFactory;
use GatoGraphQL\GatoGraphQL\Facades\UserSettingsManagerFacade;
use GatoGraphQL\GatoGraphQL\Marketplace\Constants\LicenseProperties;
use GatoGraphQL\GatoGraphQL\Marketplace\Constants\LicenseStatus;
use GatoGraphQL\GatoGraphQL\Marketplace\LicenseValidationServiceInterface;
use GatoGraphQL\GatoGraphQL\Module;
use GatoGraphQL\GatoGraphQL\ModuleConfiguration;
use GatoGraphQL\GatoGraphQL\ModuleResolvers\Extensions\ExtensionModuleResolver;
use GatoGraphQL\GatoGraphQL\PluginApp;
use GatoGraphQL\GatoGraphQL\PluginAppGraphQLServerNames;
use GatoGraphQL\GatoGraphQL\PluginAppHooks;
use GatoGraphQL\GatoGraphQL\Services\Blocks\AbstractGraphiQLBlock;
use GatoGraphQL\GatoGraphQL\Services\Blocks\CustomEndpointOptionsBlock;
use GatoGraphQL\GatoGraphQL\Services\Blocks\EndpointGraphiQLBlock;
use GatoGraphQL\GatoGraphQL\Services\Blocks\EndpointSchemaConfigurationBlock;
use GatoGraphQL\GatoGraphQL\Services\Blocks\EndpointVoyagerBlock;
use GatoGraphQL\GatoGraphQL\Services\Blocks\PersistedQueryEndpointAPIHierarchyBlock;
use GatoGraphQL\GatoGraphQL\Services\Blocks\PersistedQueryEndpointGraphiQLBlock;
use GatoGraphQL\GatoGraphQL\Services\Blocks\PersistedQueryEndpointOptionsBlock;
use GatoGraphQL\GatoGraphQL\Services\Blocks\SchemaConfigMutationSchemeBlock;
use GatoGraphQL\GatoGraphQL\Services\Blocks\SchemaConfigPayloadTypesForMutationsBlock;
use GatoGraphQL\GatoGraphQL\Services\CustomPostTypes\GraphQLCustomEndpointCustomPostType;
use GatoGraphQL\GatoGraphQL\Services\CustomPostTypes\GraphQLPersistedQueryEndpointCustomPostType;
use GatoGraphQL\GatoGraphQL\Services\CustomPostTypes\GraphQLSchemaConfigurationCustomPostType;
use GatoGraphQL\GatoGraphQL\Services\DataComposers\GraphQLDocumentDataComposer;
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
use RuntimeException;
use WP_Error;
use WP_Term;
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

                $previousPluginVersions = $storedPluginVersions;

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
                        $previousPluginVersions,
                        $storedPluginVersions,
                        $justFirstTimeActivatedExtensions,
                        $justUpdatedExtensions,
                    ): void {
                        if ($isMainPluginJustFirstTimeActivated) {
                            $this->pluginJustFirstTimeActivated();
                        } elseif ($isMainPluginJustUpdated) {
                            $this->pluginJustUpdated($storedPluginVersions[$this->pluginBaseName], $previousPluginVersions[$this->pluginBaseName]);
                        }
                        foreach ($justFirstTimeActivatedExtensions as $extensionBaseName => $extensionInstance) {
                            $extensionInstance->pluginJustFirstTimeActivated();
                        }
                        foreach ($justUpdatedExtensions as $extensionBaseName => $extensionInstance) {
                            $extensionInstance->pluginJustUpdated($storedPluginVersions[$extensionBaseName], $previousPluginVersions[$extensionBaseName]);
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
            function (): void {
                $this->maybeInstallPluginSetupData();
            },
            PHP_INT_MAX
        );
    }

    /**
     * Execute logic after the plugin/extension has just been updated
     */
    public function pluginJustUpdated(string $newVersion, string $previousVersion): void
    {
        parent::pluginJustUpdated($newVersion, $previousVersion);

        /**
         * Taxonomies are registered on "init", hence must insert
         * data only after that.
         *
         * @see layers/GatoGraphQLForWP/plugins/gatographql/src/Services/Taxonomies/AbstractTaxonomy.php
         */
        \add_action(
            'init',
            function () use ($previousVersion): void {
                // The version could contain the hash commit. Remove it!
                $commitHashPos = strpos($previousVersion, self::PLUGIN_VERSION_COMMIT_HASH_IDENTIFIER);
                if ($commitHashPos !== false) {
                    $previousVersion = substr($previousVersion, 0, $commitHashPos);
                }
                $this->maybeInstallPluginSetupData($previousVersion);
            },
            PHP_INT_MAX
        );

        $this->revalidateCommercialExtensionActivatedLicenses();
        
    }

    /**
     * Execute a /validate operation for all existing
     * licenses on the site. If any license has been
     * disabled, the corresponding extension will also
     * be disabled.
     */
    protected function revalidateCommercialExtensionActivatedLicenses(): void
    {
        $commercialExtensionActivatedLicenseKeys = $this->getCommercialExtensionActivatedLicenseKeys();
        if ($commercialExtensionActivatedLicenseKeys === []) {
            return;
        }
        
        $instanceManager = InstanceManagerFacade::getInstance();
        /** @var LicenseValidationServiceInterface */
        $licenseValidationService = $instanceManager->getInstance(LicenseValidationServiceInterface::class);
        
        $licenseValidationService->validateGatoGraphQLCommercialExtensions(
            $commercialExtensionActivatedLicenseKeys
        );
    }

    /**
     * @return array<string,string> Key: extension slug, Value: License key
     */
    protected function getCommercialExtensionActivatedLicenseKeys(): array
    {
        $commercialExtensionActivatedLicenseKeys = [];

        /** @var array<string,mixed> */
        $commercialExtensionActivatedLicenseEntries = get_option(Options::COMMERCIAL_EXTENSION_ACTIVATED_LICENSE_ENTRIES, []);
        foreach ($commercialExtensionActivatedLicenseEntries as $extensionSlug => $extensionLicenseProperties) {
            $commercialExtensionActivatedLicenseKeys[$extensionSlug] = $extensionLicenseProperties[LicenseProperties::LICENSE_KEY];
        }

        return $commercialExtensionActivatedLicenseKeys;
    }

    /**
     * Install the initial plugin data
     */
    protected function maybeInstallPluginSetupData(?string $previousVersion = null): void
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        if (!$moduleConfiguration->installPluginSetupData()) {
            return;
        }

        /**
         * Use a transient to make sure that only one instance
         * will install the data. Otherwise, two WP REST API
         * requests happening simultaneously might both execute
         * this logic
         */
        $transientName = 'gatographql-installing-plugin-setup-data';
        $transient = \get_transient($transientName);
        if ($transient !== false) {
            // Another instance is executing this code right now
            return;
        }

        \set_transient($transientName, true, 30);
        $this->installPluginSetupData($previousVersion);
        \delete_transient($transientName);
    }

    /**
     * Provide the installation in stages, version by version, to
     * be able to execute it both when installing/activating the plugin,
     * or updating it to a new version with setup data.
     *
     * The plugin's setup data will be installed if:
     *
     * - $previousVersion = null => Activating the plugin for first time
     * - $previousVersion < someVersion => Updating to a new version that has data to install
     */
    protected function installPluginSetupData(?string $previousVersion = null): void
    {
        $versionCallbacks = [
            '1.1' => $this->installPluginSetupDataForVersion1Dot1(...),
            '1.2' => $this->installPluginSetupDataForVersion1Dot2(...),
            '1.4' => $this->installPluginSetupDataForVersion1Dot4(...),
            '1.5' => $this->installPluginSetupDataForVersion1Dot5(...),
        ];
        foreach ($versionCallbacks as $version => $callback) {
            if ($previousVersion !== null && SemverWrapper::satisfies($previousVersion, '>= ' . $version)) {
                continue;
            }
            $callback();
        }
    }

    protected function installPluginSetupDataForVersion1Dot1(): void
    {
        $instanceManager = InstanceManagerFacade::getInstance();

        /**
         * Create custom endpoint
         */
        /** @var EndpointSchemaConfigurationBlock */
        $endpointSchemaConfigurationBlock = $instanceManager->getInstance(EndpointSchemaConfigurationBlock::class);

        $nestedMutationsSchemaConfigurationCustomPostID = $this->getNestedMutationsSchemaConfigurationCustomPostID();
        $defaultCustomEndpointBlocks = $this->getDefaultCustomEndpointBlocks();
        $adminCustomEndpointOptions = $this->getAdminCustomEndpointOptions();
        \wp_insert_post(array_merge(
            $adminCustomEndpointOptions,
            [
                'post_title' => \__('Nested mutations', 'gatographql'),
                'post_excerpt' => \__('Private client to execute queries that need nested mutations', 'gatographql'),
                'post_content' => serialize_blocks($this->addInnerContentToBlockAtts([
                    [
                        'blockName' => $endpointSchemaConfigurationBlock->getBlockFullName(),
                        'attrs' => [
                            EndpointSchemaConfigurationBlock::ATTRIBUTE_NAME_SCHEMA_CONFIGURATION => $nestedMutationsSchemaConfigurationCustomPostID ?? EndpointSchemaConfigurationBlock::ATTRIBUTE_VALUE_SCHEMA_CONFIGURATION_DEFAULT,
                        ],
                    ],
                    ...$defaultCustomEndpointBlocks
                ])),
            ]
        ));


        /**
         * Create the ancestor Persisted Queries for organization
         */
        /** @var PersistedQueryEndpointGraphiQLBlock */
        $persistedQueryEndpointGraphiQLBlock = $instanceManager->getInstance(PersistedQueryEndpointGraphiQLBlock::class);

        $adminPersistedQueryOptions = $this->getAdminPersistedQueryOptions();
        $defaultSchemaConfigurationPersistedQueryBlocks = $this->getDefaultSchemaConfigurationPersistedQueryBlocks();
        $nestedMutationsSchemaConfigurationPersistedQueryBlocks = $this->getNestedMutationsSchemaConfigurationPersistedQueryBlocks();
        $nestedMutationsPlusEntityAsPayloadTypeSchemaConfigurationPersistedQueryBlocks = $this->getNestedMutationsPlusEntityAsPayloadTypeSchemaConfigurationPersistedQueryBlocks();

        /**
         * Create the Persisted Queries
         */
        \wp_insert_post(array_merge(
            $adminPersistedQueryOptions,
            [
                'post_title' => \__('Duplicate post', 'gatographql'),
                'post_content' => serialize_blocks($this->addInnerContentToBlockAtts([
                    [
                        'blockName' => $persistedQueryEndpointGraphiQLBlock->getBlockFullName(),
                        'attrs' => [
                            AbstractGraphiQLBlock::ATTRIBUTE_NAME_QUERY => $this->readSetupGraphQLPersistedQueryAndEncodeForOutput(
                                'admin/transform/duplicate-post',
                                TutorialLessons::DUPLICATING_A_BLOG_POST,
                            ),
                        ],
                    ],
                    ...$defaultSchemaConfigurationPersistedQueryBlocks,
                ])),
            ]
        ));
        \wp_insert_post(array_merge(
            $adminPersistedQueryOptions,
            [
                'post_title' => \__('Duplicate posts', 'gatographql'),
                'post_content' => serialize_blocks($this->addInnerContentToBlockAtts([
                    [
                        'blockName' => $persistedQueryEndpointGraphiQLBlock->getBlockFullName(),
                        'attrs' => [
                            AbstractGraphiQLBlock::ATTRIBUTE_NAME_QUERY => $this->readSetupGraphQLPersistedQueryAndEncodeForOutput(
                                'admin/transform/duplicate-posts',
                                TutorialLessons::DUPLICATING_MULTIPLE_BLOG_POSTS_AT_ONCE,
                            ),
                            AbstractGraphiQLBlock::ATTRIBUTE_NAME_VARIABLES => $this->readSetupGraphQLVariablesJSONAndEncodeForOutput(
                                'admin/transform/duplicate-posts',
                            ),
                        ],
                    ],
                    ...$nestedMutationsPlusEntityAsPayloadTypeSchemaConfigurationPersistedQueryBlocks,
                ])),
            ]
        ));
        \wp_insert_post(array_merge(
            $adminPersistedQueryOptions,
            [
                'post_title' => \__('Replace strings in post', 'gatographql'),
                'post_content' => serialize_blocks($this->addInnerContentToBlockAtts([
                    [
                        'blockName' => $persistedQueryEndpointGraphiQLBlock->getBlockFullName(),
                        'attrs' => [
                            AbstractGraphiQLBlock::ATTRIBUTE_NAME_QUERY => $this->readSetupGraphQLPersistedQueryAndEncodeForOutput(
                                'admin/transform/replace-strings-in-post',
                                TutorialLessons::SEARCH_REPLACE_AND_STORE_AGAIN,
                            ),
                            AbstractGraphiQLBlock::ATTRIBUTE_NAME_VARIABLES => $this->readSetupGraphQLVariablesJSONAndEncodeForOutput(
                                'admin/transform/replace-strings-in-post',
                            ),
                        ],
                    ],
                    ...$defaultSchemaConfigurationPersistedQueryBlocks,
                ])),
            ]
        ));
        \wp_insert_post(array_merge(
            $adminPersistedQueryOptions,
            [
                'post_title' => \__('Replace strings in posts', 'gatographql'),
                'post_content' => serialize_blocks($this->addInnerContentToBlockAtts([
                    [
                        'blockName' => $persistedQueryEndpointGraphiQLBlock->getBlockFullName(),
                        'attrs' => [
                            AbstractGraphiQLBlock::ATTRIBUTE_NAME_QUERY => $this->readSetupGraphQLPersistedQueryAndEncodeForOutput(
                                'admin/transform/replace-strings-in-posts',
                                TutorialLessons::ADAPTING_CONTENT_IN_BULK,
                            ),
                            AbstractGraphiQLBlock::ATTRIBUTE_NAME_VARIABLES => $this->readSetupGraphQLVariablesJSONAndEncodeForOutput(
                                'admin/transform/replace-strings-in-posts',
                            ),
                        ],
                    ],
                    ...$nestedMutationsSchemaConfigurationPersistedQueryBlocks,
                ])),
            ]
        ));
        \wp_insert_post(array_merge(
            $adminPersistedQueryOptions,
            [
                'post_title' => \__('Regex replace strings in post', 'gatographql'),
                'post_content' => serialize_blocks($this->addInnerContentToBlockAtts([
                    [
                        'blockName' => $persistedQueryEndpointGraphiQLBlock->getBlockFullName(),
                        'attrs' => [
                            AbstractGraphiQLBlock::ATTRIBUTE_NAME_QUERY => $this->readSetupGraphQLPersistedQueryAndEncodeForOutput(
                                'admin/transform/regex-replace-strings-in-post',
                                TutorialLessons::SEARCH_REPLACE_AND_STORE_AGAIN,
                            ),
                            AbstractGraphiQLBlock::ATTRIBUTE_NAME_VARIABLES => $this->readSetupGraphQLVariablesJSONAndEncodeForOutput(
                                'admin/transform/regex-replace-strings-in-post',
                            ),
                        ],
                    ],
                    ...$defaultSchemaConfigurationPersistedQueryBlocks,
                ])),
            ]
        ));
        \wp_insert_post(array_merge(
            $adminPersistedQueryOptions,
            [
                'post_title' => \__('Regex replace strings in posts', 'gatographql'),
                'post_content' => serialize_blocks($this->addInnerContentToBlockAtts([
                    [
                        'blockName' => $persistedQueryEndpointGraphiQLBlock->getBlockFullName(),
                        'attrs' => [
                            AbstractGraphiQLBlock::ATTRIBUTE_NAME_QUERY => $this->readSetupGraphQLPersistedQueryAndEncodeForOutput(
                                'admin/transform/regex-replace-strings-in-posts',
                                TutorialLessons::ADAPTING_CONTENT_IN_BULK,
                            ),
                            AbstractGraphiQLBlock::ATTRIBUTE_NAME_VARIABLES => $this->readSetupGraphQLVariablesJSONAndEncodeForOutput(
                                'admin/transform/regex-replace-strings-in-posts',
                            ),
                        ],
                    ],
                    ...$nestedMutationsSchemaConfigurationPersistedQueryBlocks,
                ])),
            ]
        ));
        \wp_insert_post(array_merge(
            $adminPersistedQueryOptions,
            [
                'post_title' => \__('Add missing links in post', 'gatographql'),
                'post_content' => serialize_blocks($this->addInnerContentToBlockAtts([
                    [
                        'blockName' => $persistedQueryEndpointGraphiQLBlock->getBlockFullName(),
                        'attrs' => [
                            AbstractGraphiQLBlock::ATTRIBUTE_NAME_QUERY => $this->readSetupGraphQLPersistedQueryAndEncodeForOutput(
                                'admin/transform/add-missing-links-in-post',
                                TutorialLessons::SEARCH_REPLACE_AND_STORE_AGAIN,
                            ),
                        ],
                    ],
                    ...$defaultSchemaConfigurationPersistedQueryBlocks,
                ])),
            ]
        ));
        \wp_insert_post(array_merge(
            $adminPersistedQueryOptions,
            [
                'post_title' => \__('Replace "http" with "https" in image sources in post', 'gatographql'),
                'post_content' => serialize_blocks($this->addInnerContentToBlockAtts([
                    [
                        'blockName' => $persistedQueryEndpointGraphiQLBlock->getBlockFullName(),
                        'attrs' => [
                            AbstractGraphiQLBlock::ATTRIBUTE_NAME_QUERY => $this->readSetupGraphQLPersistedQueryAndEncodeForOutput(
                                'admin/transform/replace-http-with-https-in-image-sources-in-post',
                                TutorialLessons::SEARCH_REPLACE_AND_STORE_AGAIN,
                            ),
                        ],
                    ],
                    ...$defaultSchemaConfigurationPersistedQueryBlocks,
                ])),
            ]
        ));
        \wp_insert_post(array_merge(
            $adminPersistedQueryOptions,
            [
                'post_title' => \__('Replace domain in posts', 'gatographql'),
                'post_content' => serialize_blocks($this->addInnerContentToBlockAtts([
                    [
                        'blockName' => $persistedQueryEndpointGraphiQLBlock->getBlockFullName(),
                        'attrs' => [
                            AbstractGraphiQLBlock::ATTRIBUTE_NAME_QUERY => $this->readSetupGraphQLPersistedQueryAndEncodeForOutput(
                                'admin/transform/replace-domain-in-posts',
                                TutorialLessons::SITE_MIGRATIONS,
                            ),
                        ],
                    ],
                    ...$nestedMutationsSchemaConfigurationPersistedQueryBlocks,
                ])),
            ]
        ));
        \wp_insert_post(array_merge(
            $adminPersistedQueryOptions,
            [
                'post_title' => \__('Replace post slug in posts', 'gatographql'),
                'post_content' => serialize_blocks($this->addInnerContentToBlockAtts([
                    [
                        'blockName' => $persistedQueryEndpointGraphiQLBlock->getBlockFullName(),
                        'attrs' => [
                            AbstractGraphiQLBlock::ATTRIBUTE_NAME_QUERY => $this->readSetupGraphQLPersistedQueryAndEncodeForOutput(
                                'admin/transform/replace-post-slug-in-posts',
                                TutorialLessons::SITE_MIGRATIONS,
                                [
                                    ExtensionModuleResolver::MULTIPLE_QUERY_EXECUTION,
                                ]
                            ),
                        ],
                    ],
                    ...$nestedMutationsSchemaConfigurationPersistedQueryBlocks,
                ])),
            ]
        ));
        \wp_insert_post(array_merge(
            $adminPersistedQueryOptions,
            [
                'post_title' => \__('Insert block in posts', 'gatographql'),
                'post_content' => serialize_blocks($this->addInnerContentToBlockAtts([
                    [
                        'blockName' => $persistedQueryEndpointGraphiQLBlock->getBlockFullName(),
                        'attrs' => [
                            AbstractGraphiQLBlock::ATTRIBUTE_NAME_QUERY => $this->readSetupGraphQLPersistedQueryAndEncodeForOutput(
                                'admin/transform/insert-block-in-posts',
                                TutorialLessons::INSERTING_REMOVING_A_GUTENBERG_BLOCK_IN_BULK,
                            ),
                        ],
                    ],
                    ...$nestedMutationsSchemaConfigurationPersistedQueryBlocks,
                ])),
            ]
        ));
        \wp_insert_post(array_merge(
            $adminPersistedQueryOptions,
            [
                'post_title' => \__('Remove block from posts', 'gatographql'),
                'post_content' => serialize_blocks($this->addInnerContentToBlockAtts([
                    [
                        'blockName' => $persistedQueryEndpointGraphiQLBlock->getBlockFullName(),
                        'attrs' => [
                            AbstractGraphiQLBlock::ATTRIBUTE_NAME_QUERY => $this->readSetupGraphQLPersistedQueryAndEncodeForOutput(
                                'admin/transform/remove-block-from-posts',
                                TutorialLessons::INSERTING_REMOVING_A_GUTENBERG_BLOCK_IN_BULK,
                            ),
                        ],
                    ],
                    ...$nestedMutationsSchemaConfigurationPersistedQueryBlocks,
                ])),
            ]
        ));
        \wp_insert_post(array_merge(
            $adminPersistedQueryOptions,
            [
                'post_title' => \__('Translate post (Gutenberg)', 'gatographql'),
                'post_content' => serialize_blocks($this->addInnerContentToBlockAtts([
                    [
                        'blockName' => $persistedQueryEndpointGraphiQLBlock->getBlockFullName(),
                        'attrs' => [
                            AbstractGraphiQLBlock::ATTRIBUTE_NAME_QUERY => $this->readSetupGraphQLPersistedQueryAndEncodeForOutput(
                                'admin/transform/translate-post-gutenberg',
                                TutorialLessons::TRANSLATING_BLOCK_CONTENT_IN_A_POST_TO_A_DIFFERENT_LANGUAGE,
                            ),
                        ],
                    ],
                    ...$defaultSchemaConfigurationPersistedQueryBlocks,
                ])),
            ]
        ));
        \wp_insert_post(array_merge(
            $adminPersistedQueryOptions,
            [
                'post_title' => \__('Translate posts (Gutenberg)', 'gatographql'),
                'post_content' => serialize_blocks($this->addInnerContentToBlockAtts([
                    [
                        'blockName' => $persistedQueryEndpointGraphiQLBlock->getBlockFullName(),
                        'attrs' => [
                            AbstractGraphiQLBlock::ATTRIBUTE_NAME_QUERY => $this->readSetupGraphQLPersistedQueryAndEncodeForOutput(
                                'admin/transform/translate-posts-gutenberg',
                                TutorialLessons::BULK_TRANSLATING_BLOCK_CONTENT_IN_MULTIPLE_POSTS_TO_A_DIFFERENT_LANGUAGE,
                            ),
                        ],
                    ],
                    ...$nestedMutationsSchemaConfigurationPersistedQueryBlocks,
                ])),
            ]
        ));
        \wp_insert_post(array_merge(
            $adminPersistedQueryOptions,
            [
                'post_title' => \__('Import post from WordPress site', 'gatographql'),
                'post_content' => serialize_blocks($this->addInnerContentToBlockAtts([
                    [
                        'blockName' => $persistedQueryEndpointGraphiQLBlock->getBlockFullName(),
                        'attrs' => [
                            AbstractGraphiQLBlock::ATTRIBUTE_NAME_QUERY => $this->readSetupGraphQLPersistedQueryAndEncodeForOutput(
                                'admin/sync/import-post-from-wp-site',
                                TutorialLessons::IMPORTING_A_POST_FROM_ANOTHER_WORDPRESS_SITE,
                            ),
                        ],
                    ],
                    ...$defaultSchemaConfigurationPersistedQueryBlocks,
                ])),
            ]
        ));
        \wp_insert_post(array_merge(
            $adminPersistedQueryOptions,
            [
                'post_title' => \__('Export post to WordPress site', 'gatographql'),
                'post_content' => serialize_blocks($this->addInnerContentToBlockAtts([
                    [
                        'blockName' => $persistedQueryEndpointGraphiQLBlock->getBlockFullName(),
                        'attrs' => [
                            AbstractGraphiQLBlock::ATTRIBUTE_NAME_QUERY => $this->readSetupGraphQLPersistedQueryAndEncodeForOutput(
                                'admin/sync/export-post-to-wp-site',
                                TutorialLessons::DISTRIBUTING_CONTENT_FROM_AN_UPSTREAM_TO_MULTIPLE_DOWNSTREAM_SITES,
                            ),
                        ],
                    ],
                    ...$defaultSchemaConfigurationPersistedQueryBlocks,
                ])),
            ]
        ));
        \wp_insert_post(array_merge(
            $adminPersistedQueryOptions,
            [
                'post_title' => \__('Fetch posts by thumbnail', 'gatographql'),
                'post_content' => serialize_blocks($this->addInnerContentToBlockAtts([
                    [
                        'blockName' => $persistedQueryEndpointGraphiQLBlock->getBlockFullName(),
                        'attrs' => [
                            AbstractGraphiQLBlock::ATTRIBUTE_NAME_QUERY => $this->readSetupGraphQLPersistedQueryAndEncodeForOutput(
                                'admin/report/posts-by-thumbnail',
                                TutorialLessons::SEARCHING_WORDPRESS_DATA,
                            ),
                        ],
                    ],
                    ...$defaultSchemaConfigurationPersistedQueryBlocks,
                ])),
            ]
        ));
        \wp_insert_post(array_merge(
            $adminPersistedQueryOptions,
            [
                'post_title' => \__('Fetch users by locale', 'gatographql'),
                'post_content' => serialize_blocks($this->addInnerContentToBlockAtts([
                    [
                        'blockName' => $persistedQueryEndpointGraphiQLBlock->getBlockFullName(),
                        'attrs' => [
                            AbstractGraphiQLBlock::ATTRIBUTE_NAME_QUERY => $this->readSetupGraphQLPersistedQueryAndEncodeForOutput(
                                'admin/report/users-by-locale',
                                TutorialLessons::SEARCHING_WORDPRESS_DATA,
                            ),
                        ],
                    ],
                    ...$defaultSchemaConfigurationPersistedQueryBlocks,
                ])),
            ]
        ));
        \wp_insert_post(array_merge(
            $adminPersistedQueryOptions,
            [
                'post_title' => \__('Fetch comments by period', 'gatographql'),
                'post_content' => serialize_blocks($this->addInnerContentToBlockAtts([
                    [
                        'blockName' => $persistedQueryEndpointGraphiQLBlock->getBlockFullName(),
                        'attrs' => [
                            AbstractGraphiQLBlock::ATTRIBUTE_NAME_QUERY => $this->readSetupGraphQLPersistedQueryAndEncodeForOutput(
                                'admin/report/comments-by-period',
                                TutorialLessons::QUERYING_DYNAMIC_DATA,
                            ),
                        ],
                    ],
                    ...$defaultSchemaConfigurationPersistedQueryBlocks,
                ])),
            ]
        ));
        \wp_insert_post(array_merge(
            $adminPersistedQueryOptions,
            [
                'post_title' => \__('Fetch image URLs in blocks', 'gatographql'),
                'post_content' => serialize_blocks($this->addInnerContentToBlockAtts([
                    [
                        'blockName' => $persistedQueryEndpointGraphiQLBlock->getBlockFullName(),
                        'attrs' => [
                            AbstractGraphiQLBlock::ATTRIBUTE_NAME_QUERY => $this->readSetupGraphQLPersistedQueryAndEncodeForOutput(
                                'admin/report/images-in-blocks',
                                TutorialLessons::RETRIEVING_STRUCTURED_DATA_FROM_BLOCKS,
                            ),
                        ],
                    ],
                    ...$defaultSchemaConfigurationPersistedQueryBlocks,
                ])),
            ]
        ));

        $webhookPersistedQueryOptions = $this->getWebhookPersistedQueryOptions();
        \wp_insert_post(array_merge(
            $webhookPersistedQueryOptions,
            [
                'post_title' => \__('Register a newsletter subscriber from InstaWP to Mailchimp', 'gatographql'),
                'post_excerpt' => \__('Setup this persisted query\'s URL as webhook in an InstaWP template, to automatically capture the email from the visitors who ticked the "Subscribe to mailing list" checkbox (when creating a sandbox site), and send it straight to a Mailchimp list. More info: gatographql.com/blog/instawp-gatographql', 'gatographql'),
                'post_content' => serialize_blocks($this->addInnerContentToBlockAtts([
                    [
                        'blockName' => $persistedQueryEndpointGraphiQLBlock->getBlockFullName(),
                        'attrs' => [
                            AbstractGraphiQLBlock::ATTRIBUTE_NAME_QUERY => $this->readSetupGraphQLPersistedQueryAndEncodeForOutput(
                                'webhook/register-a-newsletter-subscriber-from-instawp-to-mailchimp',
                                TutorialLessons::AUTOMATICALLY_SENDING_NEWSLETTER_SUBSCRIBERS_FROM_INSTAWP_TO_MAILCHIMP,
                            ),
                        ],
                    ],
                    ...$defaultSchemaConfigurationPersistedQueryBlocks,
                ])),
            ]
        ));
    }

    /**
     * @return array<string,mixed>
     */
    protected function getAdminEndpointTaxInputData(): array
    {
        $instanceManager = InstanceManagerFacade::getInstance();

        /** @var GraphQLEndpointCategoryTaxonomy */
        $graphQLEndpointCategoryTaxonomy = $instanceManager->getInstance(GraphQLEndpointCategoryTaxonomy::class);

        $endpointCategoryTaxonomy = $graphQLEndpointCategoryTaxonomy->getTaxonomy();

        $adminEndpointTaxInputData = [
            $endpointCategoryTaxonomy => [],
        ];
        $adminEndpointCategoryID = $this->getAdminEndpointCategoryID();
        if ($adminEndpointCategoryID !== null) {
            $adminEndpointTaxInputData[$endpointCategoryTaxonomy][] = $adminEndpointCategoryID;
        }

        return $adminEndpointTaxInputData;
    }

    /**
     * @return array<string,mixed>
     */
    protected function getWebhookEndpointTaxInputData(): array
    {
        $instanceManager = InstanceManagerFacade::getInstance();

        /** @var GraphQLEndpointCategoryTaxonomy */
        $graphQLEndpointCategoryTaxonomy = $instanceManager->getInstance(GraphQLEndpointCategoryTaxonomy::class);

        $endpointCategoryTaxonomy = $graphQLEndpointCategoryTaxonomy->getTaxonomy();

        $webhookEndpointTaxInputData = [
            $endpointCategoryTaxonomy => [],
        ];
        $webhookEndpointCategoryID = $this->getWebhookEndpointCategoryID();
        if ($webhookEndpointCategoryID !== null) {
            $webhookEndpointTaxInputData[$endpointCategoryTaxonomy][] = $webhookEndpointCategoryID;
        }

        return $webhookEndpointTaxInputData;
    }

    /**
     * @return array<string,mixed>
     */
    protected function getAdminPersistedQueryOptions(): array
    {
        $instanceManager = InstanceManagerFacade::getInstance();

        $adminEndpointTaxInputData = $this->getAdminEndpointTaxInputData();

        /** @var GraphQLPersistedQueryEndpointCustomPostType */
        $graphQLPersistedQueryEndpointCustomPostType = $instanceManager->getInstance(GraphQLPersistedQueryEndpointCustomPostType::class);
        return [
            'post_status' => 'private',
            'post_type' => $graphQLPersistedQueryEndpointCustomPostType->getCustomPostType(),
            'tax_input' => $adminEndpointTaxInputData,
        ];
    }

    /**
     * @return array<string,mixed>
     */
    protected function getWebhookPersistedQueryOptions(): array
    {
        $instanceManager = InstanceManagerFacade::getInstance();

        $webhookEndpointTaxInputData = $this->getWebhookEndpointTaxInputData();

        /** @var GraphQLPersistedQueryEndpointCustomPostType */
        $graphQLPersistedQueryEndpointCustomPostType = $instanceManager->getInstance(GraphQLPersistedQueryEndpointCustomPostType::class);
        return [
            'post_status' => 'draft', // They are public => don't publish them!
            'post_type' => $graphQLPersistedQueryEndpointCustomPostType->getCustomPostType(),
            'tax_input' => $webhookEndpointTaxInputData,
        ];
    }

    /**
     * @return array<string,mixed>
     */
    protected function getAdminCustomEndpointOptions(): array
    {
        $instanceManager = InstanceManagerFacade::getInstance();

        $adminEndpointTaxInputData = $this->getAdminEndpointTaxInputData();

        /** @var GraphQLCustomEndpointCustomPostType */
        $graphQLCustomEndpointCustomPostType = $instanceManager->getInstance(GraphQLCustomEndpointCustomPostType::class);
        return [
            'post_status' => 'private',
            'post_type' => $graphQLCustomEndpointCustomPostType->getCustomPostType(),
            'tax_input' => $adminEndpointTaxInputData,
        ];
    }

    /**
     * @return array<array<string,mixed>>
     */
    protected function getDefaultCustomEndpointBlocks(): array
    {
        $instanceManager = InstanceManagerFacade::getInstance();

        /** @var CustomEndpointOptionsBlock */
        $customEndpointOptionsBlock = $instanceManager->getInstance(CustomEndpointOptionsBlock::class);
        /** @var EndpointGraphiQLBlock */
        $endpointGraphiQLBlock = $instanceManager->getInstance(EndpointGraphiQLBlock::class);
        /** @var EndpointVoyagerBlock */
        $endpointVoyagerBlock = $instanceManager->getInstance(EndpointVoyagerBlock::class);

        return [
            [
                'blockName' => $customEndpointOptionsBlock->getBlockFullName(),
            ],
            [
                'blockName' => $endpointGraphiQLBlock->getBlockFullName(),
            ],
            [
                'blockName' => $endpointVoyagerBlock->getBlockFullName(),
            ]
        ];
    }

    /**
     * @return array<array<string,mixed>>
     */
    protected function getDefaultSchemaConfigurationPersistedQueryBlocks(): array
    {
        $instanceManager = InstanceManagerFacade::getInstance();

        /** @var EndpointSchemaConfigurationBlock */
        $endpointSchemaConfigurationBlock = $instanceManager->getInstance(EndpointSchemaConfigurationBlock::class);

        /** @var PersistedQueryEndpointOptionsBlock */
        $persistedQueryEndpointOptionsBlock = $instanceManager->getInstance(PersistedQueryEndpointOptionsBlock::class);
        /** @var PersistedQueryEndpointAPIHierarchyBlock */
        $persistedQueryEndpointAPIHierarchyBlock = $instanceManager->getInstance(PersistedQueryEndpointAPIHierarchyBlock::class);

        return [
            [
                'blockName' => $endpointSchemaConfigurationBlock->getBlockFullName(),
            ],
            [
                'blockName' => $persistedQueryEndpointOptionsBlock->getBlockFullName(),
            ],
            [
                'blockName' => $persistedQueryEndpointAPIHierarchyBlock->getBlockFullName(),
            ]
        ];
    }

    /**
     * @return array<array<string,mixed>>
     */
    protected function getNestedMutationsSchemaConfigurationPersistedQueryBlocks(): array
    {
        $instanceManager = InstanceManagerFacade::getInstance();

        /** @var EndpointSchemaConfigurationBlock */
        $endpointSchemaConfigurationBlock = $instanceManager->getInstance(EndpointSchemaConfigurationBlock::class);
        /** @var PersistedQueryEndpointOptionsBlock */
        $persistedQueryEndpointOptionsBlock = $instanceManager->getInstance(PersistedQueryEndpointOptionsBlock::class);
        /** @var PersistedQueryEndpointAPIHierarchyBlock */
        $persistedQueryEndpointAPIHierarchyBlock = $instanceManager->getInstance(PersistedQueryEndpointAPIHierarchyBlock::class);

        $nestedMutationsSchemaConfigurationCustomPostID = $this->getNestedMutationsSchemaConfigurationCustomPostID();
        return [
            [
                'blockName' => $endpointSchemaConfigurationBlock->getBlockFullName(),
                'attrs' => [
                    EndpointSchemaConfigurationBlock::ATTRIBUTE_NAME_SCHEMA_CONFIGURATION => $nestedMutationsSchemaConfigurationCustomPostID ?? EndpointSchemaConfigurationBlock::ATTRIBUTE_VALUE_SCHEMA_CONFIGURATION_DEFAULT,
                ],
            ],
            [
                'blockName' => $persistedQueryEndpointOptionsBlock->getBlockFullName(),
            ],
            [
                'blockName' => $persistedQueryEndpointAPIHierarchyBlock->getBlockFullName(),
            ]
        ];
    }

    /**
     * @return array<array<string,mixed>>
     */
    protected function getNestedMutationsPlusEntityAsPayloadTypeSchemaConfigurationPersistedQueryBlocks(): array
    {
        $instanceManager = InstanceManagerFacade::getInstance();

        /** @var EndpointSchemaConfigurationBlock */
        $endpointSchemaConfigurationBlock = $instanceManager->getInstance(EndpointSchemaConfigurationBlock::class);
        /** @var PersistedQueryEndpointOptionsBlock */
        $persistedQueryEndpointOptionsBlock = $instanceManager->getInstance(PersistedQueryEndpointOptionsBlock::class);
        /** @var PersistedQueryEndpointAPIHierarchyBlock */
        $persistedQueryEndpointAPIHierarchyBlock = $instanceManager->getInstance(PersistedQueryEndpointAPIHierarchyBlock::class);

        $nestedMutationsPlusEntityAsPayloadTypeSchemaConfigurationCustomPostID = $this->getNestedMutationsPlusEntityAsPayloadTypeSchemaConfigurationCustomPostID();

        return [
            [
                'blockName' => $endpointSchemaConfigurationBlock->getBlockFullName(),
                'attrs' => [
                    EndpointSchemaConfigurationBlock::ATTRIBUTE_NAME_SCHEMA_CONFIGURATION => $nestedMutationsPlusEntityAsPayloadTypeSchemaConfigurationCustomPostID ?? EndpointSchemaConfigurationBlock::ATTRIBUTE_VALUE_SCHEMA_CONFIGURATION_DEFAULT,
                ],
            ],
            [
                'blockName' => $persistedQueryEndpointOptionsBlock->getBlockFullName(),
            ],
            [
                'blockName' => $persistedQueryEndpointAPIHierarchyBlock->getBlockFullName(),
            ]
        ];
    }

    protected function installPluginSetupDataForVersion1Dot2(): void
    {
        $instanceManager = InstanceManagerFacade::getInstance();

        /** @var PersistedQueryEndpointGraphiQLBlock */
        $persistedQueryEndpointGraphiQLBlock = $instanceManager->getInstance(PersistedQueryEndpointGraphiQLBlock::class);

        $adminPersistedQueryOptions = $this->getAdminPersistedQueryOptions();
        $defaultSchemaConfigurationPersistedQueryBlocks = $this->getDefaultSchemaConfigurationPersistedQueryBlocks();
        \wp_insert_post(array_merge(
            $adminPersistedQueryOptions,
            [
                'post_title' => \__('Translate content from URL', 'gatographql'),
                'post_content' => serialize_blocks($this->addInnerContentToBlockAtts([
                    [
                        'blockName' => $persistedQueryEndpointGraphiQLBlock->getBlockFullName(),
                        'attrs' => [
                            AbstractGraphiQLBlock::ATTRIBUTE_NAME_QUERY => $this->readSetupGraphQLPersistedQueryAndEncodeForOutput(
                                'admin/transform/translate-content-from-url',
                                TutorialLessons::TRANSLATING_CONTENT_FROM_URL,
                            ),
                            AbstractGraphiQLBlock::ATTRIBUTE_NAME_VARIABLES => $this->readSetupGraphQLVariablesJSONAndEncodeForOutput(
                                'admin/transform/translate-content-from-url',
                            ),
                        ],
                    ],
                    ...$defaultSchemaConfigurationPersistedQueryBlocks,
                ])),
            ]
        ));
        \wp_insert_post(array_merge(
            $adminPersistedQueryOptions,
            [
                'post_title' => \__('Import post from WordPress RSS feed', 'gatographql'),
                'post_content' => serialize_blocks($this->addInnerContentToBlockAtts([
                    [
                        'blockName' => $persistedQueryEndpointGraphiQLBlock->getBlockFullName(),
                        'attrs' => [
                            AbstractGraphiQLBlock::ATTRIBUTE_NAME_QUERY => $this->readSetupGraphQLPersistedQueryAndEncodeForOutput(
                                'admin/sync/import-post-from-wp-rss-feed',
                                VirtualTutorialLessons::IMPORTING_A_POST_FROM_WORDPRESS_RSS_FEED,
                            ),
                            AbstractGraphiQLBlock::ATTRIBUTE_NAME_VARIABLES => $this->readSetupGraphQLVariablesJSONAndEncodeForOutput(
                                'admin/sync/import-post-from-wp-rss-feed',
                            ),
                        ],
                    ],
                    ...$defaultSchemaConfigurationPersistedQueryBlocks,
                ])),
            ]
        ));
        $nestedMutationsPlusEntityAsPayloadTypeSchemaConfigurationPersistedQueryBlocks = $this->getNestedMutationsPlusEntityAsPayloadTypeSchemaConfigurationPersistedQueryBlocks();
        \wp_insert_post(array_merge(
            $adminPersistedQueryOptions,
            [
                'post_title' => \__('Import posts from CSV', 'gatographql'),
                'post_content' => serialize_blocks($this->addInnerContentToBlockAtts([
                    [
                        'blockName' => $persistedQueryEndpointGraphiQLBlock->getBlockFullName(),
                        'attrs' => [
                            AbstractGraphiQLBlock::ATTRIBUTE_NAME_QUERY => $this->readSetupGraphQLPersistedQueryAndEncodeForOutput(
                                'admin/sync/import-posts-from-csv',
                                VirtualTutorialLessons::IMPORTING_POSTS_FROM_A_CSV,
                            ),
                            AbstractGraphiQLBlock::ATTRIBUTE_NAME_VARIABLES => $this->readSetupGraphQLVariablesJSONAndEncodeForOutput(
                                'admin/sync/import-posts-from-csv',
                            ),
                        ],
                    ],
                    ...$nestedMutationsPlusEntityAsPayloadTypeSchemaConfigurationPersistedQueryBlocks,
                ])),
            ]
        ));
        \wp_insert_post(array_merge(
            $adminPersistedQueryOptions,
            [
                'post_title' => \__('Fetch post links', 'gatographql'),
                'post_content' => serialize_blocks($this->addInnerContentToBlockAtts([
                    [
                        'blockName' => $persistedQueryEndpointGraphiQLBlock->getBlockFullName(),
                        'attrs' => [
                            AbstractGraphiQLBlock::ATTRIBUTE_NAME_QUERY => $this->readSetupGraphQLPersistedQueryAndEncodeForOutput(
                                'admin/report/post-links',
                                VirtualTutorialLessons::FETCH_POST_LINKS,
                            ),
                        ],
                    ],
                    ...$defaultSchemaConfigurationPersistedQueryBlocks,
                ])),
            ]
        ));
        \wp_insert_post(array_merge(
            $adminPersistedQueryOptions,
            [
                'post_title' => \__('Translate post (Classic editor)', 'gatographql'),
                'post_content' => serialize_blocks($this->addInnerContentToBlockAtts([
                    [
                        'blockName' => $persistedQueryEndpointGraphiQLBlock->getBlockFullName(),
                        'attrs' => [
                            AbstractGraphiQLBlock::ATTRIBUTE_NAME_QUERY => $this->readSetupGraphQLPersistedQueryAndEncodeForOutput(
                                'admin/transform/translate-post-classic-editor',
                                VirtualTutorialLessons::TRANSLATING_CLASSIC_EDITOR_POST_TO_A_DIFFERENT_LANGUAGE,
                            ),
                        ],
                    ],
                    ...$defaultSchemaConfigurationPersistedQueryBlocks,
                ])),
            ]
        ));
        $nestedMutationsSchemaConfigurationPersistedQueryBlocks = $this->getNestedMutationsSchemaConfigurationPersistedQueryBlocks();
        \wp_insert_post(array_merge(
            $adminPersistedQueryOptions,
            [
                'post_title' => \__('Translate posts (Classic editor)', 'gatographql'),
                'post_content' => serialize_blocks($this->addInnerContentToBlockAtts([
                    [
                        'blockName' => $persistedQueryEndpointGraphiQLBlock->getBlockFullName(),
                        'attrs' => [
                            AbstractGraphiQLBlock::ATTRIBUTE_NAME_QUERY => $this->readSetupGraphQLPersistedQueryAndEncodeForOutput(
                                'admin/transform/translate-posts-classic-editor',
                                VirtualTutorialLessons::BULK_TRANSLATING_CLASSIC_EDITOR_POSTS_TO_A_DIFFERENT_LANGUAGE,
                            ),
                        ],
                    ],
                    ...$nestedMutationsSchemaConfigurationPersistedQueryBlocks,
                ])),
            ]
        ));
    }

    protected function getNestedMutationsSchemaConfigurationCustomPostID(): ?int
    {
        // @gatographql-note: Do not rename this slug, as it's referenced when installing the testing webservers
        $slug = 'nested-mutations';
        $schemaConfigurationID = $this->getSchemaConfigurationID($slug);
        if ($schemaConfigurationID !== null) {
            return $schemaConfigurationID;
        }

        $nestedMutationsBlockDataItem = $this->getNestedMutationsBlockDataItem();
        return $this->createSchemaConfigurationID(
            $slug,
            \__('Nested mutations', 'gatographql'),
            [
                $nestedMutationsBlockDataItem,
            ]
        );
    }

    /**
     * @return array<string,mixed>
     */
    protected function getNestedMutationsBlockDataItem(): array
    {
        $instanceManager = InstanceManagerFacade::getInstance();
        /** @var SchemaConfigMutationSchemeBlock */
        $schemaConfigMutationSchemeBlock = $instanceManager->getInstance(SchemaConfigMutationSchemeBlock::class);

        return [
            'blockName' => $schemaConfigMutationSchemeBlock->getBlockFullName(),
            'attrs' => [
                SchemaConfigMutationSchemeBlock::ATTRIBUTE_NAME_MUTATION_SCHEME => MutationSchemes::NESTED_WITH_REDUNDANT_ROOT_FIELDS,
            ]
        ];
    }

    protected function getSchemaConfigurationID(string $slug): ?int
    {
        $instanceManager = InstanceManagerFacade::getInstance();
        /** @var GraphQLSchemaConfigurationCustomPostType */
        $graphQLSchemaConfigurationCustomPostType = $instanceManager->getInstance(GraphQLSchemaConfigurationCustomPostType::class);

        /** @var array<string|int> */
        $schemaConfigurations = \get_posts([
            'name' => $slug,
            'post_type' => $graphQLSchemaConfigurationCustomPostType->getCustomPostType(),
            'post_status' => 'publish',
            'numberposts' => 1,
            'fields' => 'ids',
        ]);
        if (isset($schemaConfigurations[0])) {
            return (int) $schemaConfigurations[0];
        }

        return null;
    }

    /**
     * @param array<array<string,mixed>> $blockDataItems
     */
    protected function createSchemaConfigurationID(string $slug, string $title, array $blockDataItems): ?int
    {
        $instanceManager = InstanceManagerFacade::getInstance();
        /** @var GraphQLSchemaConfigurationCustomPostType */
        $graphQLSchemaConfigurationCustomPostType = $instanceManager->getInstance(GraphQLSchemaConfigurationCustomPostType::class);

        $schemaConfigurationCustomPostID = \wp_insert_post([
            'post_status' => 'publish',
            'post_name' => $slug,
            'post_type' => $graphQLSchemaConfigurationCustomPostType->getCustomPostType(),
            'post_title' => $title,
            'post_content' => serialize_blocks($this->addInnerContentToBlockAtts($blockDataItems))
        ]);
        if ($schemaConfigurationCustomPostID === 0) {
            return null;
        }
        return $schemaConfigurationCustomPostID;
    }

    protected function getNestedMutationsPlusEntityAsPayloadTypeSchemaConfigurationCustomPostID(): ?int
    {
        // @gatographql-note: Do not rename this slug, as it's referenced when installing the testing webservers
        $slug = 'nested-mutations-entity-as-mutation-payload-type';
        $schemaConfigurationID = $this->getSchemaConfigurationID($slug);
        if ($schemaConfigurationID !== null) {
            return $schemaConfigurationID;
        }

        $nestedMutationsBlockDataItem = $this->getNestedMutationsBlockDataItem();
        $entityAsPayloadTypeBlockDataItem = $this->getEntityAsPayloadTypeBlockDataItem();
        return $this->createSchemaConfigurationID(
            $slug,
            \__('Nested mutations + Entity as mutation payload type', 'gatographql'),
            [
                $nestedMutationsBlockDataItem,
                $entityAsPayloadTypeBlockDataItem,
            ]
        );
    }

    /**
     * @return array<string,mixed>
     */
    protected function getEntityAsPayloadTypeBlockDataItem(): array
    {
        $instanceManager = InstanceManagerFacade::getInstance();
        /** @var SchemaConfigPayloadTypesForMutationsBlock */
        $schemaConfigPayloadTypesForMutationsBlock = $instanceManager->getInstance(SchemaConfigPayloadTypesForMutationsBlock::class);

        return [
            'blockName' => $schemaConfigPayloadTypesForMutationsBlock->getBlockFullName(),
            'attrs' => [
                BlockAttributeNames::ENABLED_CONST => BlockAttributeValues::DISABLED,
            ]
        ];
    }

    protected function getAdminEndpointCategoryID(): ?int
    {
        $slug = 'admin';
        $endpointCategoryID = $this->getEndpointCategoryID($slug);
        if ($endpointCategoryID !== null) {
            return $endpointCategoryID;
        }

        return $this->createEndpointCategoryID(
            $slug,
            \__('Admin', 'gatographql'),
            \__('Internal admin tasks', 'gatographql'),
        );
    }

    protected function getEndpointCategoryID(string $slug): ?int
    {
        $instanceManager = InstanceManagerFacade::getInstance();
        /** @var GraphQLEndpointCategoryTaxonomy */
        $graphQLEndpointCategoryTaxonomy = $instanceManager->getInstance(GraphQLEndpointCategoryTaxonomy::class);

        /** @var WP_Term|false */
        $endpointCategoryTerm = \get_term_by('slug', $slug, $graphQLEndpointCategoryTaxonomy->getTaxonomy());
        if ($endpointCategoryTerm instanceof WP_Term) {
            return $endpointCategoryTerm->term_id;
        }

        return null;
    }

    protected function createEndpointCategoryID(string $slug, string $name, string $description): ?int
    {
        $instanceManager = InstanceManagerFacade::getInstance();
        /** @var GraphQLEndpointCategoryTaxonomy */
        $graphQLEndpointCategoryTaxonomy = $instanceManager->getInstance(GraphQLEndpointCategoryTaxonomy::class);

        $endpointCategoryTerm = \wp_insert_term(
            $name,
            $graphQLEndpointCategoryTaxonomy->getTaxonomy(),
            [
                'slug' => $slug,
                $description
            ]
        );
        if ($endpointCategoryTerm instanceof WP_Error) {
            return null;
        }
        return $endpointCategoryTerm['term_id'];
    }

    protected function getWebhookEndpointCategoryID(): ?int
    {
        $slug = 'webhook';
        $endpointCategoryID = $this->getEndpointCategoryID($slug);
        if ($endpointCategoryID !== null) {
            return $endpointCategoryID;
        }

        return $this->createEndpointCategoryID(
            $slug,
            \__('Webhook', 'gatographql'),
            \__('Process data from external services', 'gatographql'),
        );
    }

    /**
     * @param string|null $tutorialLessonSlug The slug of the tutorial lesson's .md file, same as in TutorialLessonDataProvider
     * @param string[]|null $skipExtensionModules Extensions that must not be added to the Persisted Query (which are associated to the tutorial lesson)
     */
    protected function readSetupGraphQLPersistedQueryAndEncodeForOutput(
        string $relativeFilePath,
        ?string $tutorialLessonSlug = null,
        ?array $skipExtensionModules = null
    ): string {
        $instanceManager = InstanceManagerFacade::getInstance();
        /** @var GraphQLDocumentDataComposer */
        $graphQLDocumentDataComposer = $instanceManager->getInstance(GraphQLDocumentDataComposer::class);

        $graphQLPersistedQuery = $this->readSetupGraphQLPersistedQuery($relativeFilePath);
        if ($tutorialLessonSlug !== null) {
            $graphQLPersistedQuery = $graphQLDocumentDataComposer->addRequiredBundlesAndExtensionsToGraphQLDocumentHeader(
                $graphQLPersistedQuery,
                $tutorialLessonSlug,
                $skipExtensionModules,
            );
        }
        $graphQLPersistedQuery = $graphQLDocumentDataComposer->encodeGraphQLDocumentForOutput($graphQLPersistedQuery);
        return $graphQLPersistedQuery;
    }

    protected function readSetupGraphQLPersistedQuery(string $relativeFilePath): string
    {
        $persistedQueryFile = $this->getSetupGraphQLPersistedQueryFilePath($relativeFilePath, 'gql');
        return $this->readFile($persistedQueryFile);
    }

    protected function getSetupGraphQLPersistedQueryFilePath(
        string $relativeFilePath,
        string $extension,
    ): string {
        $rootFolder = dirname(__DIR__, 2);
        $persistedQueriesFolder = $rootFolder . '/setup/persisted-queries';
        return $persistedQueriesFolder . '/' . $relativeFilePath . '.' . $extension;
    }

    protected function readSetupGraphQLVariablesJSONAndEncodeForOutput(string $relativeFilePath): string
    {
        $instanceManager = InstanceManagerFacade::getInstance();
        /** @var GraphQLDocumentDataComposer */
        $graphQLDocumentDataComposer = $instanceManager->getInstance(GraphQLDocumentDataComposer::class);

        $graphQLVariablesJSON = $this->readSetupGraphQLVariablesJSON($relativeFilePath);
        $graphQLVariablesJSON = $graphQLDocumentDataComposer->encodeGraphQLVariablesJSONForOutput($graphQLVariablesJSON);
        return $graphQLVariablesJSON;
    }

    protected function readSetupGraphQLVariablesJSON(string $relativeFilePath): string
    {
        $persistedQueryFile = $this->getSetupGraphQLPersistedQueryFilePath($relativeFilePath, 'var.json');
        return $this->readFile($persistedQueryFile);
    }

    protected function readFile(string $filePath): string
    {
        $file = file_get_contents($filePath);
        if ($file === false) {
            throw new RuntimeException(
                sprintf('Loading file \'%s\' failed', $filePath)
            );
        }
        return $file;
    }

    /**
     * @param array<array<string,mixed>> $blockDataItems
     * @return array<array<string,mixed>>
     */
    protected function addInnerContentToBlockAtts(array $blockDataItems): array
    {
        return array_map(
            fn (array $blockDataItem) => [...$blockDataItem, 'innerContent' => []],
            $blockDataItems
        );
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

    protected function installPluginSetupDataForVersion1Dot4(): void
    {
        $instanceManager = InstanceManagerFacade::getInstance();

        /**
         * Create custom endpoint
         */
        /** @var EndpointSchemaConfigurationBlock */
        $endpointSchemaConfigurationBlock = $instanceManager->getInstance(EndpointSchemaConfigurationBlock::class);

        $nestedMutationsPlusEntityAsPayloadTypeSchemaConfigurationCustomPostID = $this->getNestedMutationsPlusEntityAsPayloadTypeSchemaConfigurationCustomPostID();
        $defaultCustomEndpointBlocks = $this->getDefaultCustomEndpointBlocks();
        $adminCustomEndpointOptions = $this->getAdminCustomEndpointOptions();
        \wp_insert_post(array_merge(
            $adminCustomEndpointOptions,
            [
                'post_title' => \__('Nested mutations + Entity as mutation payload type', 'gatographql'),
                'post_excerpt' => \__('Private client to execute queries that create resources in bulk', 'gatographql'),
                'post_content' => serialize_blocks($this->addInnerContentToBlockAtts([
                    [
                        'blockName' => $endpointSchemaConfigurationBlock->getBlockFullName(),
                        'attrs' => [
                            EndpointSchemaConfigurationBlock::ATTRIBUTE_NAME_SCHEMA_CONFIGURATION => $nestedMutationsPlusEntityAsPayloadTypeSchemaConfigurationCustomPostID ?? EndpointSchemaConfigurationBlock::ATTRIBUTE_VALUE_SCHEMA_CONFIGURATION_DEFAULT,
                        ],
                    ],
                    ...$defaultCustomEndpointBlocks
                ])),
            ]
        ));
    }

    protected function installPluginSetupDataForVersion1Dot5(): void
    {
        $instanceManager = InstanceManagerFacade::getInstance();

        /** @var PersistedQueryEndpointGraphiQLBlock */
        $persistedQueryEndpointGraphiQLBlock = $instanceManager->getInstance(PersistedQueryEndpointGraphiQLBlock::class);

        $adminPersistedQueryOptions = $this->getAdminPersistedQueryOptions();
        $defaultSchemaConfigurationPersistedQueryBlocks = $this->getDefaultSchemaConfigurationPersistedQueryBlocks();
        \wp_insert_post(array_merge(
            $adminPersistedQueryOptions,
            [
                'post_title' => \__('Send email to admin about post', 'gatographql'),
                'post_content' => serialize_blocks($this->addInnerContentToBlockAtts([
                    [
                        'blockName' => $persistedQueryEndpointGraphiQLBlock->getBlockFullName(),
                        'attrs' => [
                            AbstractGraphiQLBlock::ATTRIBUTE_NAME_QUERY => $this->readSetupGraphQLPersistedQueryAndEncodeForOutput(
                                'admin/notify/send-email-to-admin-about-post',
                                VirtualTutorialLessons::SEND_EMAIL_TO_ADMIN_ABOUT_POST,
                            ),
                        ],
                    ],
                    ...$defaultSchemaConfigurationPersistedQueryBlocks,
                ])),
            ]
        ));
        $nestedMutationsSchemaConfigurationPersistedQueryBlocks = $this->getNestedMutationsSchemaConfigurationPersistedQueryBlocks();
        \wp_insert_post(array_merge(
            $adminPersistedQueryOptions,
            [
                'post_title' => \__('Add comments block to post', 'gatographql'),
                'post_content' => serialize_blocks($this->addInnerContentToBlockAtts([
                    [
                        'blockName' => $persistedQueryEndpointGraphiQLBlock->getBlockFullName(),
                        'attrs' => [
                            AbstractGraphiQLBlock::ATTRIBUTE_NAME_QUERY => $this->readSetupGraphQLPersistedQueryAndEncodeForOutput(
                                'admin/transform/add-comments-block-to-post',
                                VirtualTutorialLessons::ADD_COMMENTS_BLOCK_TO_POST,
                            ),
                        ],
                    ],
                    ...$nestedMutationsSchemaConfigurationPersistedQueryBlocks,
                ])),
            ]
        ));
    }
}
