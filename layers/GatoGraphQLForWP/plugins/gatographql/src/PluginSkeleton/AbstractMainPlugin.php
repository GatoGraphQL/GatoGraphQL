<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\PluginSkeleton;

use Exception;
use GatoGraphQL\ExternalDependencyWrappers\Symfony\Component\Exception\IOException;
use GatoGraphQL\ExternalDependencyWrappers\Symfony\Component\Filesystem\FilesystemWrapper;
use GatoGraphQL\GatoGraphQL\App;
use GatoGraphQL\GatoGraphQL\AppThread;
use GatoGraphQL\GatoGraphQL\Constants\HTMLCodes;
use GatoGraphQL\GatoGraphQL\Container\InternalGraphQLServerContainerBuilderFactory;
use GatoGraphQL\GatoGraphQL\Container\InternalGraphQLServerSystemContainerBuilderFactory;
use GatoGraphQL\GatoGraphQL\Facades\UserSettingsManagerFacade;
use GatoGraphQL\GatoGraphQL\Marketplace\Constants\LicenseProperties;
use GatoGraphQL\GatoGraphQL\Marketplace\Constants\LicenseStatus;
use GatoGraphQL\GatoGraphQL\Marketplace\LicenseValidationServiceInterface;
use GatoGraphQL\GatoGraphQL\Module;
use GatoGraphQL\GatoGraphQL\ModuleConfiguration;
use GatoGraphQL\GatoGraphQL\PluginApp;
use GatoGraphQL\GatoGraphQL\PluginAppGraphQLServerNames;
use GatoGraphQL\GatoGraphQL\PluginAppHooks;
use GatoGraphQL\GatoGraphQL\PluginStaticModuleConfiguration;
use GatoGraphQL\GatoGraphQL\Services\DataComposers\GraphQLDocumentDataComposer;
use GatoGraphQL\GatoGraphQL\Settings\Options;
use GatoGraphQL\GatoGraphQL\StateManagers\AppThreadHookManagerWrapper;
use GatoGraphQL\GatoGraphQL\StaticHelpers\SettingsHelpers;
use GraphQLByPoP\GraphQLServer\AppStateProviderServices\GraphQLServerAppStateProviderServiceInterface;
use PoP\RootWP\AppLoader as WPDeferredAppLoader;
use PoP\RootWP\StateManagers\HookManager;
use PoP\Root\AppLoader as ImmediateAppLoader;
use PoP\Root\Environment as RootEnvironment;
use PoP\Root\Facades\Instances\InstanceManagerFacade;

use PoP\Root\Helpers\ClassHelpers;
use PoP\Root\Module\ModuleInterface;
use RuntimeException;
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
     * @param string[] $actions
     * @return string[]
     */
    public function getPluginActionLinks(array $actions): array
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        return [
            sprintf(
                '<a href="%s" target="_blank">%s%s</a>',
                $moduleConfiguration->getGatoGraphQLWebsiteURL(),
                __('Go PRO', 'gatographql'),
                HTMLCodes::OPEN_IN_NEW_WINDOW,
            ),
            ...$actions,
        ];
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

        add_filter( 'plugin_action_links_gatographql/gatographql.php', $this->getPluginActionLinks(...), 10, 1 );

        // Dump the container whenever a new plugin or extension is activated
        $this->handleNewActivations();

        // Initialize the procedure to register/initialize plugin and extensions
        $this->executeSetupProcedure();
    }

    /**
     * Check if the plugin has just been activated or updated,
     * or if an extension has just been activated.
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
         * just been activated or updated.
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
                $isMainPluginJustActivated = !isset($storedPluginVersions[$this->pluginBaseName]);
                $isMainPluginJustUpdated = !$isMainPluginJustActivated && $storedPluginVersions[$this->pluginBaseName] !== $this->getPluginVersionWithCommitHash();

                // Check if any extension has been activated or updated
                $justActivatedExtensions = [];
                $justUpdatedExtensions = [];
                foreach ($registeredExtensionBaseNameInstances as $extensionBaseName => $extensionInstance) {
                    if (!isset($storedPluginVersions[$extensionBaseName])) {
                        $justActivatedExtensions[$extensionBaseName] = $extensionInstance;
                    } elseif ($storedPluginVersions[$extensionBaseName] !== $extensionInstance->getPluginVersionWithCommitHash()) {
                        $justUpdatedExtensions[$extensionBaseName] = $extensionInstance;
                    }
                }

                // If there were no changes, nothing to do
                if (
                    !$isMainPluginJustActivated
                    && !$isMainPluginJustUpdated
                    && $justActivatedExtensions === []
                    && $justUpdatedExtensions === []
                ) {
                    return;
                }

                $previousPluginVersions = $storedPluginVersions;

                // Recalculate the updated entry and update on the DB
                $storedPluginVersions[$this->pluginBaseName] = $this->getPluginVersionWithCommitHash();
                foreach (array_merge($justActivatedExtensions, $justUpdatedExtensions) as $extensionBaseName => $extensionInstance) {
                    $storedPluginVersions[$extensionBaseName] = $extensionInstance->getPluginVersionWithCommitHash();
                }

                // Regenerate the timestamp, to generate the service container
                $this->purgeContainer();

                /**
                 * Enable to implement custom additional functionality (eg: show admin notice with changelog)
                 * Watch out! Execute at the very end, just in case they need to access the service container,
                 * which is not initialized yet (eg: for calling `$userSettingsManager->getSetting`)
                 */
                add_action(
                    PluginAppHooks::INITIALIZE_APP,
                    function (string $pluginAppGraphQLServerName) use (
                        $isMainPluginJustActivated,
                        $isMainPluginJustUpdated,
                        $previousPluginVersions,
                        $storedPluginVersions,
                        $justActivatedExtensions,
                        $justUpdatedExtensions,
                    ): void {
                        if (
                            $pluginAppGraphQLServerName === PluginAppGraphQLServerNames::INTERNAL
                            || $this->inititalizationException !== null
                        ) {
                            return;
                        }
                        /**
                         * Update the versions only now, as to be sure that
                         * compiling the container has ended successfully.
                         *
                         * Otherwise, if it produces a fatal error (because the
                         * script takes too long to execute), we don't want
                         * to reference the cached container (which may contain
                         * garbage)
                         *
                         * @see https://github.com/GatoGraphQL/GatoGraphQL/issues/2631
                         */
                        update_option(PluginOptions::PLUGIN_VERSIONS, $storedPluginVersions);

                        if ($isMainPluginJustActivated) {
                            $this->pluginJustActivated();
                        } elseif ($isMainPluginJustUpdated) {
                            $this->pluginJustUpdated($storedPluginVersions[$this->pluginBaseName], $previousPluginVersions[$this->pluginBaseName]);
                        }
                        foreach ($justActivatedExtensions as $extensionBaseName => $extensionInstance) {
                            $extensionInstance->pluginJustActivated();
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
     * Execute logic after the plugin/extension has just been updated
     */
    public function pluginJustUpdated(string $newVersion, string $previousVersion): void
    {
        parent::pluginJustUpdated($newVersion, $previousVersion);

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
            /**
             * @var array<string,mixed> $pluginAppGraphQLServerContext
             */
            function (string $pluginAppGraphQLServerName, array $pluginAppGraphQLServerContext): void {
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
                App::setAppThread(new AppThread($pluginAppGraphQLServerName, $pluginAppGraphQLServerContext));
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
                        ? new InternalGraphQLServerContainerBuilderFactory($pluginAppGraphQLServerContext)
                        : null,
                    $isInternalGraphQLServer
                        ? new InternalGraphQLServerSystemContainerBuilderFactory($pluginAppGraphQLServerContext)
                        : null
                );
            },
            PluginLifecyclePriorities::INITIALIZE_APP,
            2
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
            /**
             * @var array<string,mixed> $pluginAppGraphQLServerContext
             */
            function (string $pluginAppGraphQLServerName, array $pluginAppGraphQLServerContext): void {
                if ($this->inititalizationException !== null) {
                    return;
                }
                $this->bootSystem($pluginAppGraphQLServerName, $pluginAppGraphQLServerContext);
            },
            PluginLifecyclePriorities::BOOT_SYSTEM,
            2
        );
        add_action(
            PluginAppHooks::INITIALIZE_APP,
            function (): void {
                if ($this->inititalizationException !== null) {
                    return;
                }
                $this->configure();
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
     *
     * @param array<string,mixed> $pluginAppGraphQLServerContext
    */
    public function bootSystem(
        string $pluginAppGraphQLServerName,
        array $pluginAppGraphQLServerContext
    ): void {
        // If the service container has an error, Symfony DI will throw an exception
        try {
            // Boot all PoP components, from this plugin and all extensions
            $appLoader = App::getAppLoader();
            $appLoader->setContainerCacheConfiguration(
                $this->pluginInitializationConfiguration->getContainerCacheConfiguration(
                    $pluginAppGraphQLServerName,
                    $pluginAppGraphQLServerContext
                )
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
        if ($tutorialLessonSlug !== null
            && !PluginStaticModuleConfiguration::offerSinglePROCommercialProduct()
        ) {
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
}
