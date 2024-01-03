<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\MenuPages;

use GatoGraphQL\GatoGraphQL\App;
use GatoGraphQL\GatoGraphQL\Constants\RequestParams;
use GatoGraphQL\GatoGraphQL\Facades\UserSettingsManagerFacade;
use GatoGraphQL\GatoGraphQL\Marketplace\Constants\LicenseProperties;
use GatoGraphQL\GatoGraphQL\Marketplace\Exception\HTTPRequestNotSuccessfulException;
use GatoGraphQL\GatoGraphQL\Marketplace\Exception\LicenseOperationNotSuccessfulException;
use GatoGraphQL\GatoGraphQL\Marketplace\MarketplaceProviderCommercialExtensionActivationServiceInterface;
use GatoGraphQL\GatoGraphQL\Marketplace\ObjectModels\CommercialExtensionActivatedLicenseObjectProperties;
use GatoGraphQL\GatoGraphQL\ModuleResolvers\PluginGeneralSettingsFunctionalityModuleResolver;
use GatoGraphQL\GatoGraphQL\ModuleResolvers\PluginManagementFunctionalityModuleResolver;
use GatoGraphQL\GatoGraphQL\ModuleSettings\Properties;
use GatoGraphQL\GatoGraphQL\PluginApp;
use GatoGraphQL\GatoGraphQL\Registries\ModuleRegistryInterface;
use GatoGraphQL\GatoGraphQL\Registries\SettingsCategoryRegistryInterface;
use GatoGraphQL\GatoGraphQL\SettingsCategoryResolvers\SettingsCategoryResolver;
use GatoGraphQL\GatoGraphQL\Settings\Options;
use GatoGraphQL\GatoGraphQL\Settings\SettingsNormalizerInterface;
use GatoGraphQL\GatoGraphQL\Settings\UserSettingsManagerInterface;
use PoP\ComponentModel\Configuration\RequestHelpers;
use PoP\ComponentModel\Constants\FrameworkParams;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\ComponentModel\Module as ComponentModelModule;
use PoP\ComponentModel\ModuleConfiguration as ComponentModelModuleConfiguration;

use function get_option;
use function home_url;
use function update_option;

/**
 * Settings menu page
 */
class SettingsMenuPage extends AbstractPluginMenuPage
{
    use UseTabpanelMenuPageTrait;
    use UseDocsMenuPageTrait;
    use UseCollapsibleContentMenuPageTrait;

    public final const FORM_ORIGIN = 'form-origin';
    public final const FORM_FIELD_LAST_SAVED_TIMESTAMP = 'last_saved_timestamp';
    public final const RESET_SETTINGS_BUTTON_ID = 'submit-reset-settings';
    public final const ACTIVATE_EXTENSIONS_BUTTON_ID = 'submit-activate-extensions';

    private ?UserSettingsManagerInterface $userSettingsManager = null;
    private ?SettingsNormalizerInterface $settingsNormalizer = null;
    private ?PluginGeneralSettingsFunctionalityModuleResolver $PluginGeneralSettingsFunctionalityModuleResolver = null;
    private ?SettingsCategoryRegistryInterface $settingsCategoryRegistry = null;
    private ?ModuleRegistryInterface $moduleRegistry = null;
    private ?MarketplaceProviderCommercialExtensionActivationServiceInterface $marketplaceProviderCommercialExtensionActivationService = null;

    public function setUserSettingsManager(UserSettingsManagerInterface $userSettingsManager): void
    {
        $this->userSettingsManager = $userSettingsManager;
    }
    protected function getUserSettingsManager(): UserSettingsManagerInterface
    {
        return $this->userSettingsManager ??= UserSettingsManagerFacade::getInstance();
    }
    final public function setSettingsNormalizer(SettingsNormalizerInterface $settingsNormalizer): void
    {
        $this->settingsNormalizer = $settingsNormalizer;
    }
    final protected function getSettingsNormalizer(): SettingsNormalizerInterface
    {
        if ($this->settingsNormalizer === null) {
            /** @var SettingsNormalizerInterface */
            $settingsNormalizer = $this->instanceManager->getInstance(SettingsNormalizerInterface::class);
            $this->settingsNormalizer = $settingsNormalizer;
        }
        return $this->settingsNormalizer;
    }
    final public function setPluginGeneralSettingsFunctionalityModuleResolver(PluginGeneralSettingsFunctionalityModuleResolver $PluginGeneralSettingsFunctionalityModuleResolver): void
    {
        $this->PluginGeneralSettingsFunctionalityModuleResolver = $PluginGeneralSettingsFunctionalityModuleResolver;
    }
    final protected function getPluginGeneralSettingsFunctionalityModuleResolver(): PluginGeneralSettingsFunctionalityModuleResolver
    {
        if ($this->PluginGeneralSettingsFunctionalityModuleResolver === null) {
            /** @var PluginGeneralSettingsFunctionalityModuleResolver */
            $PluginGeneralSettingsFunctionalityModuleResolver = $this->instanceManager->getInstance(PluginGeneralSettingsFunctionalityModuleResolver::class);
            $this->PluginGeneralSettingsFunctionalityModuleResolver = $PluginGeneralSettingsFunctionalityModuleResolver;
        }
        return $this->PluginGeneralSettingsFunctionalityModuleResolver;
    }
    final public function setSettingsCategoryRegistry(SettingsCategoryRegistryInterface $settingsCategoryRegistry): void
    {
        $this->settingsCategoryRegistry = $settingsCategoryRegistry;
    }
    final protected function getSettingsCategoryRegistry(): SettingsCategoryRegistryInterface
    {
        if ($this->settingsCategoryRegistry === null) {
            /** @var SettingsCategoryRegistryInterface */
            $settingsCategoryRegistry = $this->instanceManager->getInstance(SettingsCategoryRegistryInterface::class);
            $this->settingsCategoryRegistry = $settingsCategoryRegistry;
        }
        return $this->settingsCategoryRegistry;
    }
    final public function setModuleRegistry(ModuleRegistryInterface $moduleRegistry): void
    {
        $this->moduleRegistry = $moduleRegistry;
    }
    final protected function getModuleRegistry(): ModuleRegistryInterface
    {
        if ($this->moduleRegistry === null) {
            /** @var ModuleRegistryInterface */
            $moduleRegistry = $this->instanceManager->getInstance(ModuleRegistryInterface::class);
            $this->moduleRegistry = $moduleRegistry;
        }
        return $this->moduleRegistry;
    }
    final public function setMarketplaceProviderCommercialExtensionActivationService(MarketplaceProviderCommercialExtensionActivationServiceInterface $marketplaceProviderCommercialExtensionActivationService): void
    {
        $this->marketplaceProviderCommercialExtensionActivationService = $marketplaceProviderCommercialExtensionActivationService;
    }
    final protected function getMarketplaceProviderCommercialExtensionActivationService(): MarketplaceProviderCommercialExtensionActivationServiceInterface
    {
        if ($this->marketplaceProviderCommercialExtensionActivationService === null) {
            /** @var MarketplaceProviderCommercialExtensionActivationServiceInterface */
            $marketplaceProviderCommercialExtensionActivationService = $this->instanceManager->getInstance(MarketplaceProviderCommercialExtensionActivationServiceInterface::class);
            $this->marketplaceProviderCommercialExtensionActivationService = $marketplaceProviderCommercialExtensionActivationService;
        }
        return $this->marketplaceProviderCommercialExtensionActivationService;
    }

    public function getMenuPageSlug(): string
    {
        return 'settings';
    }

    /**
     * Initialize the class instance
     */
    public function initialize(): void
    {
        parent::initialize();

        $settingsCategoryRegistry = $this->getSettingsCategoryRegistry();

        $settingsCategory = SettingsCategoryResolver::PLUGIN_MANAGEMENT;
        $option = $settingsCategoryRegistry->getSettingsCategoryResolver($settingsCategory)->getOptionsFormName($settingsCategory);
        \add_action(
            "update_option_{$option}",
            /**
             * Based on which button was pressed, do one or another action:
             *
             * - Reset Settings
             * - Activate Extensions
             *
             * Because the form will send all values again, for all sections,
             * restore the other sections. Otherwise, the user might remove
             * the License Key from the input, then switch to Reset Settings,
             * and click there, being completely unaware that the input
             * will be removed in the DB.             *
             *
             * @param array<string,mixed> $oldValue
             * @param array<string,mixed> $values
             * @return array<string,mixed>
             */
            function (mixed $oldValue, array $values) use ($settingsCategory): void {
                // Make sure the user clicked on the corresponding button
                if (
                    !isset($values[self::RESET_SETTINGS_BUTTON_ID])
                    && !isset($values[self::ACTIVATE_EXTENSIONS_BUTTON_ID])
                ) {
                    return;
                }

                if (!is_array($oldValue)) {
                    $oldValue = [];
                }

                // If pressed on the "Reset Settings" button...
                if (isset($values[self::RESET_SETTINGS_BUTTON_ID])) {
                    $this->restoreDBOptionValuesForNonSubmittedFormSections(
                        $settingsCategory,
                        [
                            [
                                PluginManagementFunctionalityModuleResolver::RESET_SETTINGS,
                                PluginManagementFunctionalityModuleResolver::OPTION_USE_RESTRICTIVE_OR_NOT_DEFAULT_BEHAVIOR,
                            ],
                        ],
                        $oldValue,
                        $values,
                    );

                    $this->resetSettings();
                    return;
                }

                // If pressed on the "Activate (Extensions)" button...
                if (isset($values[self::ACTIVATE_EXTENSIONS_BUTTON_ID])) {
                    $this->restoreDBOptionValuesForNonSubmittedFormSections(
                        $settingsCategory,
                        [
                            [
                                PluginManagementFunctionalityModuleResolver::ACTIVATE_EXTENSIONS,
                                PluginManagementFunctionalityModuleResolver::OPTION_COMMERCIAL_EXTENSION_LICENSE_KEYS,
                            ],
                        ],
                        $oldValue,
                        $values,
                    );

                    // Retrieve the previously-stored license keys, and the newly-submitted license keys
                    $settingOptionName = $this->getModuleRegistry()->getModuleResolver(PluginManagementFunctionalityModuleResolver::ACTIVATE_EXTENSIONS)->getSettingOptionName(PluginManagementFunctionalityModuleResolver::ACTIVATE_EXTENSIONS, PluginManagementFunctionalityModuleResolver::OPTION_COMMERCIAL_EXTENSION_LICENSE_KEYS);
                    $this->activateDeactivateValidateGatoGraphQLCommercialExtensions(
                        $oldValue[$settingOptionName] ?? [],
                        $values[$settingOptionName] ?? []
                    );
                    return;
                }
            },
            10,
            2
        );

        /**
         * Keep this variable for if "Plugin Configuration" eventually
         * needs to regenerate the container once again.
         */
        $doesPluginConfigurationSettingsAffectTheServiceContainer = false;
        $regenerateConfigSettingsCategories = [
            'schema' => SettingsCategoryResolver::SCHEMA_CONFIGURATION,
            'endpoint' => SettingsCategoryResolver::ENDPOINT_CONFIGURATION,
            'server' => SettingsCategoryResolver::SERVER_CONFIGURATION,
            'plugin' => SettingsCategoryResolver::PLUGIN_CONFIGURATION,
            'api-keys' => SettingsCategoryResolver::API_KEYS,
        ];
        $regenerateConfigFormOptions = array_map(
            fn (string $settingsCategory) => $settingsCategoryRegistry->getSettingsCategoryResolver($settingsCategory)->getOptionsFormName($settingsCategory),
            $regenerateConfigSettingsCategories
        );
        foreach ($regenerateConfigFormOptions as $option) {
            $regenerateContainer = null;
            if (
                $doesPluginConfigurationSettingsAffectTheServiceContainer // @phpstan-ignore-line
                && $option === $regenerateConfigFormOptions['plugin']
            ) {
                $regenerateContainer = true;
            }

            // "Endpoint Configuration" needs to be flushed as it modifies CPT permalinks
            $flushRewriteRules = $option === $regenerateConfigFormOptions['endpoint'];

            /**
             * After saving the settings in the DB:
             * - Flush the rewrite rules, so different URL slugs take effect
             * - Update the timestamp
             *
             * This hooks is also triggered the first time the user saves the settings
             * (i.e. there's no update) thanks to `maybeStoreEmptySettings`
             */
            \add_action(
                "update_option_{$option}",
                fn () => $this->flushContainer(
                    $flushRewriteRules,
                    $regenerateContainer,
                )
            );
        }

        /**
         * Register the settings
         */
        \add_action(
            'admin_init',
            function () use ($settingsCategoryRegistry): void {
                $settingsItems = $this->getSettingsNormalizer()->getAllSettingsItems();
                foreach ($settingsCategoryRegistry->getSettingsCategorySettingsCategoryResolvers() as $settingsCategory => $settingsCategoryResolver) {
                    $categorySettingsItems = array_values(array_filter(
                        $settingsItems,
                        /** @param array<string,mixed> $item */
                        fn (array $item) => $item['settings-category'] === $settingsCategory
                    ));
                    $optionsFormName = $settingsCategoryResolver->getOptionsFormName($settingsCategory);
                    foreach ($categorySettingsItems as $item) {
                        $optionsFormModuleSectionName = $this->getOptionsFormModuleSectionName($optionsFormName, $item['id']);
                        $module = $item['module'];
                        \add_settings_section(
                            $optionsFormModuleSectionName,
                            // The empty string ensures the render function won't output a h2.
                            '',
                            function (): void {
                            },
                            $optionsFormName
                        );
                        foreach ($item['settings'] as $itemSetting) {
                            \add_settings_field(
                                $itemSetting[Properties::NAME],
                                $itemSetting[Properties::TITLE] ?? '',
                                function () use ($module, $itemSetting, $optionsFormName): void {
                                    $type = $itemSetting[Properties::TYPE] ?? null;
                                    $possibleValues = $itemSetting[Properties::POSSIBLE_VALUES] ?? [];
                                    $cssStyle = $itemSetting[Properties::CSS_STYLE] ?? '';
                                    ?>
                                        <div id="section-<?php echo \esc_attr($itemSetting[Properties::NAME]) ?>" class="gatographql-settings-item" <?php if (!empty($cssStyle)) :
                                            ?>style="<?php echo \esc_attr($cssStyle) ?>"<?php
                                                         endif; ?>>
                                            <?php
                                            if (!empty($possibleValues)) {
                                                $this->printSelectField($optionsFormName, $module, $itemSetting);
                                            } elseif ($type === Properties::TYPE_ARRAY) {
                                                $this->printTextareaField($optionsFormName, $module, $itemSetting);
                                            } elseif ($type === Properties::TYPE_BOOL) {
                                                $this->printCheckboxField($optionsFormName, $module, $itemSetting);
                                            } elseif ($type === Properties::TYPE_NULL) {
                                                $this->printLabelField($optionsFormName, $module, $itemSetting);
                                            } elseif ($type === Properties::TYPE_PROPERTY_ARRAY) {
                                                $this->printMultiInputField($optionsFormName, $module, $itemSetting);
                                            } else {
                                                $this->printInputField($optionsFormName, $module, $itemSetting);
                                            }
                                            ?>
                                        </div>
                                    <?php
                                },
                                $optionsFormName,
                                $optionsFormModuleSectionName,
                                [
                                    'label' => $itemSetting[Properties::DESCRIPTION] ?? '',
                                    'id' => $itemSetting[Properties::NAME],
                                ]
                            );
                        }
                    }

                    /**
                     * Finally register all the settings
                     */
                    \register_setting(
                        $optionsFormName,
                        $settingsCategoryResolver->getDBOptionName($settingsCategory),
                        [
                            'type' => 'array',
                            'description' => $settingsCategoryResolver->getName($settingsCategory),
                            /**
                             * This call is needed to cast the data
                             * before saving to the DB.
                             *
                             * Please notice that this callback may be called twice:
                             * once triggered by `update_option` and once by `add_option`,
                             * (which is called by `update_option`).
                             */
                            'sanitize_callback' => fn (array $values) => $this->sanitizeCallback($values, $settingsCategory),
                            'show_in_rest' => false,
                        ]
                    );
                }
            }
        );
    }

    /**
     * "Plugin Management Settings": Restore the stored values for the
     * contiguous sections in the form (i.e. the other ones to the
     * submitted section where the button was clicked).
     *
     * To restore the values:
     *
     * - Use the old values from the hook
     * - Remove the clicked button from the form, as to avoid infinite looping here
     * - Override the new values, just for the submitted section
     *
     * @param array<array{0:string,1:string}> $formSubmittedModuleOptionItems Form items that must be stored in the DB (everything else will be restored), with item format: [0]: module, [1]: option
     * @param array<string,mixed> $oldValue
     * @param array<string,mixed> $values
     */
    protected function restoreDBOptionValuesForNonSubmittedFormSections(
        string $settingsCategory,
        array $formSubmittedModuleOptionItems,
        array $oldValue,
        array $values,
    ): void {
        $dbOptionName = $this->getSettingsCategoryRegistry()->getSettingsCategoryResolver($settingsCategory)->getDBOptionName($settingsCategory);

        // Always transfer the "last_saved_timestamp" field
        $storeSettingOptionNames = [
            self::FORM_FIELD_LAST_SAVED_TIMESTAMP,
        ];
        foreach ($formSubmittedModuleOptionItems as $formSubmittedModuleOptionItem) {
            $module = $formSubmittedModuleOptionItem[0];
            $option = $formSubmittedModuleOptionItem[1];
            $moduleResolver = $this->getModuleRegistry()->getModuleResolver($module);
            $settingOptionName = $moduleResolver->getSettingOptionName($module, $option);
            $storeSettingOptionNames[] = $settingOptionName;
        }

        $restoredValues = $oldValue;
        foreach ($storeSettingOptionNames as $transferSettingOptionName) {
            $restoredValues[$transferSettingOptionName] = $values[$transferSettingOptionName];
        }

        update_option($dbOptionName, $restoredValues);
    }

    /**
     * Delete the Settings and flush
     */
    protected function resetSettings(): void
    {
        $userSettingsManager = $this->getUserSettingsManager();
        $resetOptions = [
            Options::SCHEMA_CONFIGURATION,
            Options::ENDPOINT_CONFIGURATION,
            Options::SERVER_CONFIGURATION,
            Options::PLUGIN_CONFIGURATION,
        ];
        foreach ($resetOptions as $option) {
            $userSettingsManager->storeEmptySettings($option);
        }

        /**
         * Keep this variable for if "Plugin Configuration" eventually
         * needs to regenerate the container once again.
         */
        $doesPluginConfigurationSettingsAffectTheServiceContainer = false;
        $regenerateContainer = null;
        if ($doesPluginConfigurationSettingsAffectTheServiceContainer) { // @phpstan-ignore-line
            $regenerateContainer = true;
        }
        $this->flushContainer(true, $regenerateContainer);
    }

    /**
     * Activate the Gato GraphQL Extensions against the
     * marketplace provider's API.
     *
     * @param array<string,string> $previousLicenseKeys Key: Extension Slug, Value: License Key
     * @param array<string,string> $submittedLicenseKeys Key: Extension Slug, Value: License Key
     */
    protected function activateDeactivateValidateGatoGraphQLCommercialExtensions(
        array $previousLicenseKeys,
        array $submittedLicenseKeys,
    ): void {
        /** @var array<string,mixed> */
        $commercialExtensionActivatedLicenseEntries = get_option(Options::COMMERCIAL_EXTENSION_ACTIVATED_LICENSE_ENTRIES, []);
        [
            $activateLicenseKeys,
            $deactivateLicenseKeys,
            $validateLicenseKeys,
        ] = $this->calculateLicenseKeysToActivateDeactivateValidate(
            $commercialExtensionActivatedLicenseEntries,
            $previousLicenseKeys,
            $submittedLicenseKeys,
        );

        if (
            $activateLicenseKeys === []
            && $deactivateLicenseKeys === []
            && $validateLicenseKeys === []
        ) {
            return;
        }

        // Keep the original values, to only flush the container if these have changed
        $originalCommercialExtensionActivatedLicenseEntries = $commercialExtensionActivatedLicenseEntries;

        $extensionManager = PluginApp::getExtensionManager();
        $commercialExtensionSlugProductNames = $extensionManager->getCommercialExtensionSlugProductNames();
        $marketplaceProviderCommercialExtensionActivationService = $this->getMarketplaceProviderCommercialExtensionActivationService();
        $commercialExtensionActivatedLicenseObjectProperties = null;

        foreach ($validateLicenseKeys as $extensionSlug => $licenseKey) {
            /**
             * If the extensionName is empty, that means that a previously-activated
             * bundle has been deactivated, with the license still in the DB.
             * In that case, do nothing.
             *
             * @var string
             */
            $extensionName = $commercialExtensionSlugProductNames[$extensionSlug] ?? '';
            if ($extensionName === '') {
                continue;
            }
            /** @var array<string,mixed> */
            $commercialExtensionActivatedLicenseEntry = $commercialExtensionActivatedLicenseEntries[$extensionSlug];
            /** @var string */
            $instanceID = $commercialExtensionActivatedLicenseEntry[LicenseProperties::INSTANCE_ID];
            try {
                $commercialExtensionActivatedLicenseObjectProperties = $marketplaceProviderCommercialExtensionActivationService->validateLicense(
                    $licenseKey,
                    $instanceID,
                );
            } catch (HTTPRequestNotSuccessfulException | LicenseOperationNotSuccessfulException $e) {
                $errorMessage = sprintf(
                    \__('Validating license for "%s" produced error: %s', 'gatographql'),
                    $extensionName,
                    $e->getMessage()
                );
                $commercialExtensionActivatedLicenseEntries = $this->handleLicenseOperationError(
                    $commercialExtensionActivatedLicenseEntries,
                    $extensionSlug,
                    $errorMessage,
                    $e,
                );
                continue;
            }

            $commercialExtensionActivatedLicenseEntries = $this->addCommercialExtensionActivatedLicenseEntry(
                $commercialExtensionActivatedLicenseEntries,
                $extensionSlug,
                $commercialExtensionActivatedLicenseObjectProperties,
            );

            $successMessage = sprintf(
                \__('The license for "%s" has status "%s". You have %s/%s instances activated.', 'gatographql'),
                $extensionName,
                $commercialExtensionActivatedLicenseObjectProperties->status,
                $commercialExtensionActivatedLicenseObjectProperties->activationUsage,
                $commercialExtensionActivatedLicenseObjectProperties->activationLimit,
            );
            $this->showAdminMessagesOnLicenseOperationSuccess(
                $extensionSlug,
                $extensionName,
                $commercialExtensionActivatedLicenseObjectProperties,
                $successMessage,
            );
        }

        /**
         * First deactivate and then activate licenses, because an extension
         * might be deactivated + reactivated (with a different license key)
         */
        foreach ($deactivateLicenseKeys as $extensionSlug => $licenseKey) {
            /**
             * Make sure the bundle is active. If not, do nothing.
             *
             * @var string
             */
            $extensionName = $commercialExtensionSlugProductNames[$extensionSlug] ?? '';
            if ($extensionName === '') {
                continue;
            }
            /** @var array<string,mixed> */
            $commercialExtensionActivatedLicenseEntry = $commercialExtensionActivatedLicenseEntries[$extensionSlug];
            /** @var string */
            $instanceID = $commercialExtensionActivatedLicenseEntry[LicenseProperties::INSTANCE_ID];
            try {
                $commercialExtensionActivatedLicenseObjectProperties = $marketplaceProviderCommercialExtensionActivationService->deactivateLicense(
                    $licenseKey,
                    $instanceID,
                );
            } catch (HTTPRequestNotSuccessfulException | LicenseOperationNotSuccessfulException $e) {
                $errorMessage = sprintf(
                    \__('Deactivating license for "%s" produced error: %s', 'gatographql'),
                    $extensionName,
                    $e->getMessage()
                );
                $commercialExtensionActivatedLicenseEntries = $this->handleLicenseOperationError(
                    $commercialExtensionActivatedLicenseEntries,
                    $extensionSlug,
                    $errorMessage,
                    $e,
                );
                continue;
            }

            // Do not store deactivated instances
            unset($commercialExtensionActivatedLicenseEntries[$extensionSlug]);

            $successMessage = sprintf(
                \__('Deactivating license for "%s" succeeded. You now have %s/%s instances activated.', 'gatographql'),
                $extensionName,
                $commercialExtensionActivatedLicenseObjectProperties->activationUsage,
                $commercialExtensionActivatedLicenseObjectProperties->activationLimit,
            );
            $this->showAdminMessagesOnLicenseOperationSuccess(
                $extensionSlug,
                $extensionName,
                $commercialExtensionActivatedLicenseObjectProperties,
                $successMessage,
            );
        }

        foreach ($activateLicenseKeys as $extensionSlug => $licenseKey) {
            /**
             * Make sure the bundle is active. If not, do nothing.
             *
             * @var string
             */
            $extensionName = $commercialExtensionSlugProductNames[$extensionSlug] ?? '';
            if ($extensionName === '') {
                continue;
            }
            $instanceName = $this->getInstanceName($extensionSlug);
            try {
                $commercialExtensionActivatedLicenseObjectProperties = $marketplaceProviderCommercialExtensionActivationService->activateLicense($licenseKey, $instanceName);
            } catch (HTTPRequestNotSuccessfulException | LicenseOperationNotSuccessfulException $e) {
                $errorMessage = sprintf(
                    \__('Activating license for "%s" produced error: %s', 'gatographql'),
                    $extensionName,
                    $e->getMessage()
                );
                $commercialExtensionActivatedLicenseEntries = $this->handleLicenseOperationError(
                    $commercialExtensionActivatedLicenseEntries,
                    $extensionSlug,
                    $errorMessage,
                    $e,
                );
                continue;
            }

            $commercialExtensionActivatedLicenseEntries = $this->addCommercialExtensionActivatedLicenseEntry(
                $commercialExtensionActivatedLicenseEntries,
                $extensionSlug,
                $commercialExtensionActivatedLicenseObjectProperties,
            );

            $successMessage = sprintf(
                \__('Activating license for "%s" succeeded. You have %s/%s instances activated.', 'gatographql'),
                $extensionName,
                $commercialExtensionActivatedLicenseObjectProperties->activationUsage,
                $commercialExtensionActivatedLicenseObjectProperties->activationLimit,
            );
            $this->showAdminMessagesOnLicenseOperationSuccess(
                $extensionSlug,
                $extensionName,
                $commercialExtensionActivatedLicenseObjectProperties,
                $successMessage,
            );
        }

        // Do not flush container or update DB if there are no changes
        if ($originalCommercialExtensionActivatedLicenseEntries === $commercialExtensionActivatedLicenseEntries) {
            return;
        }

        // Store the payloads to the DB
        update_option(
            Options::COMMERCIAL_EXTENSION_ACTIVATED_LICENSE_ENTRIES,
            $commercialExtensionActivatedLicenseEntries
        );

        // Because extensions will be activated/deactivated, flush the service container
        $this->flushContainer(true, true);
    }

    /**
     * If there was an HTTPRequest error, then do not unset the stored
     * payload in the DB.
     *
     * If there was a LicenseOperation error, that means the instance
     * does not exist, or some other problem, and hence deactivate the
     * instance.
     *
     * Show an error message to the admin.
     *
     * @param array<string,mixed> $commercialExtensionActivatedLicenseEntries
     * @return array<string,mixed>
     */
    protected function handleLicenseOperationError(
        array $commercialExtensionActivatedLicenseEntries,
        string $extensionSlug,
        string $errorMessage,
        HTTPRequestNotSuccessfulException | LicenseOperationNotSuccessfulException $e,
    ): array {
        if ($e instanceof LicenseOperationNotSuccessfulException) {
            unset($commercialExtensionActivatedLicenseEntries[$extensionSlug]);
            $type = 'error';
        } else {
            $type = 'warning';
        }

        // Show the error message to the admin
        add_settings_error(
            PluginManagementFunctionalityModuleResolver::ACTIVATE_EXTENSIONS,
            'license_activation_' . $extensionSlug,
            $errorMessage,
            $type
        );

        return $commercialExtensionActivatedLicenseEntries;
    }

    /**
     * Add the payload entry to be stored in the DB.
     *
     * @param array<string,mixed> $commercialExtensionActivatedLicenseEntries
     * @return array<string,mixed>
     */
    protected function addCommercialExtensionActivatedLicenseEntry(
        array $commercialExtensionActivatedLicenseEntries,
        string $extensionSlug,
        CommercialExtensionActivatedLicenseObjectProperties $commercialExtensionActivatedLicenseObjectProperties,
    ): array {
        /** @var string */
        $instanceID = $commercialExtensionActivatedLicenseObjectProperties->instanceID;
        /** @var string */
        $instanceName = $commercialExtensionActivatedLicenseObjectProperties->instanceName;
        $commercialExtensionActivatedLicenseEntries[$extensionSlug] = [
            LicenseProperties::LICENSE_KEY => $commercialExtensionActivatedLicenseObjectProperties->licenseKey,
            LicenseProperties::API_RESPONSE_PAYLOAD => $commercialExtensionActivatedLicenseObjectProperties->apiResponsePayload,
            LicenseProperties::STATUS => $commercialExtensionActivatedLicenseObjectProperties->status,
            LicenseProperties::INSTANCE_ID => $instanceID,
            /**
             * The instance name is generated by the plugin, hence there is would be
             * no need to store it in the DB. However, it is stored as to pre-populate
             * the "Support" form, to help the Gato GraphQL team understand what
             * extensions are being used.
             */
            LicenseProperties::INSTANCE_NAME => $instanceName,
            LicenseProperties::ACTIVATION_USAGE => $commercialExtensionActivatedLicenseObjectProperties->activationUsage,
            LicenseProperties::ACTIVATION_LIMIT => $commercialExtensionActivatedLicenseObjectProperties->activationLimit,
            /**
             * The product name is stored as to validate that the license key
             * provided in the Settings belongs to the right extension.
             *
             * @see `assertCommercialLicenseHasBeenActivated` in class `ExtensionManager`
             */
            LicenseProperties::PRODUCT_NAME => $commercialExtensionActivatedLicenseObjectProperties->productName,
            /**
             * The customer name and email are stored as to pre-populate
             * the "Support" form
             */
            LicenseProperties::CUSTOMER_NAME => $commercialExtensionActivatedLicenseObjectProperties->customerName,
            LicenseProperties::CUSTOMER_EMAIL => $commercialExtensionActivatedLicenseObjectProperties->customerEmail,
        ];

        return $commercialExtensionActivatedLicenseEntries;
    }

    protected function showAdminMessagesOnLicenseOperationSuccess(
        string $extensionSlug,
        string $extensionName,
        CommercialExtensionActivatedLicenseObjectProperties $commercialExtensionActivatedLicenseObjectProperties,
        string $successMessage,
    ): void {
        // Show the success message to the admin
        add_settings_error(
            PluginManagementFunctionalityModuleResolver::ACTIVATE_EXTENSIONS,
            'license_activation_' . $extensionSlug,
            $successMessage,
            'success'
        );

        if ($commercialExtensionActivatedLicenseObjectProperties->productName === $extensionName) {
            return;
        }

        // Show the error message to the admin
        add_settings_error(
            PluginManagementFunctionalityModuleResolver::ACTIVATE_EXTENSIONS,
            'license_activation_' . $extensionSlug,
            sprintf(
                \__('The license key provided for "%1$s" is meant to be used with "%2$s". As such, "%1$s" has not been enabled. Please use the right license key to enable it.<br/>If you need help, send an email to support@gatographql.com (providing the purchased license keys).'),
                $extensionName,
                $commercialExtensionActivatedLicenseObjectProperties->productName,
            ),
            'error'
        );
    }

    /**
     * Calculate which extensions must be activated, which must be deactivated,
     * and which must be both.
     *
     * Every entry in $submittedLicenseKeys is compared against $previousLicenseKeys and,
     * depending on both values, either there is nothing to do, or the license is
     * activated/deactivated/both:
     *
     * - If the license key is empty in both, then nothing to do
     * - If the license key has not been updated, then validate it
     * - If the license key is new (i.e. it was empty before), then activate it
     * - If the license key is removed (i.e. it is empty now, non-empty before), then deactivate it
     * - If the license key has been updated, then deactivate the previous one, and activate the new one
     *
     * @param array<string,mixed> $commercialExtensionActivatedLicenseEntries
     * @param array<string,string> $previousLicenseKeys Key: Extension Slug, Value: License Key
     * @param array<string,string> $submittedLicenseKeys Key: Extension Slug, Value: License Key
     * @return array{0:array<string,string>,1:array<string,string>,2:array<string,string>} [0]: $activateLicenseKeys, [1]: $deactivateLicenseKeys, [2]: $validateLicenseKeys], with array items as: Key: Extension Slug, Value: License Key
     */
    protected function calculateLicenseKeysToActivateDeactivateValidate(
        array $commercialExtensionActivatedLicenseEntries,
        array $previousLicenseKeys,
        array $submittedLicenseKeys,
    ): array {
        $deactivateLicenseKeys = [];
        $activateLicenseKeys = [];
        $validateLicenseKeys = [];

        // Iterate all submitted entries to activate extensions
        foreach ($submittedLicenseKeys as $extensionSlug => $submittedLicenseKey) {
            $submittedLicenseKey = trim($submittedLicenseKey);
            if ($submittedLicenseKey === '') {
                // License key not set => Skip
                continue;
            }
            $hasExtensionBeenActivated = isset($commercialExtensionActivatedLicenseEntries[$extensionSlug]);
            $previousLicenseKey = trim($previousLicenseKeys[$extensionSlug] ?? '');
            if ($previousLicenseKey === $submittedLicenseKey) {
                if ($hasExtensionBeenActivated) {
                    // License key not updated => Validate
                    $validateLicenseKeys[$extensionSlug] = $submittedLicenseKey;
                } else {
                    // The previous license had (for some reason) not be activated => Activate
                    $activateLicenseKeys[$extensionSlug] = $submittedLicenseKey;
                }
                continue;
            }
            if ($previousLicenseKey === '') {
                // License key newly added => Activate
                $activateLicenseKeys[$extensionSlug] = $submittedLicenseKey;
                continue;
            }
            // License key updated => Deactivate + Activate
            if ($hasExtensionBeenActivated) {
                $deactivateLicenseKeys[$extensionSlug] = $previousLicenseKey;
            }
            $activateLicenseKeys[$extensionSlug] = $submittedLicenseKey;
        }

        // Iterate all previous entries to deactivate extensions
        foreach ($previousLicenseKeys as $extensionSlug => $previousLicenseKey) {
            $previousLicenseKey = trim($previousLicenseKey);
            if ($previousLicenseKey === '') {
                // License key not previously set => Skip
                continue;
            }
            $submittedLicenseKey = trim($submittedLicenseKeys[$extensionSlug] ?? '');
            if ($previousLicenseKey === $submittedLicenseKey) {
                // License key not updated => Do nothing (Validate: already queued above)
                continue;
            }
            if ($submittedLicenseKey === '') {
                $hasExtensionBeenActivated = isset($commercialExtensionActivatedLicenseEntries[$extensionSlug]);
                if ($hasExtensionBeenActivated) {
                    // License key newly removed => Deactivate
                    $deactivateLicenseKeys[$extensionSlug] = $previousLicenseKey;
                }
                continue;
            }
            // License key updated => Do nothing (Deactivate + Activate: already queued above)
        }

        return [
            $activateLicenseKeys,
            $deactivateLicenseKeys,
            $validateLicenseKeys,
        ];
    }

    /**
     * Use as the instance name:
     *
     * - The site's domain: to understand on what domain it was installed
     * - Extension slug: to make sure the right license key was provided
     */
    protected function getInstanceName(string $extensionSlug): string
    {
        return sprintf(
            '%s (%s)',
            GeneralUtils::getHost(home_url()),
            $extensionSlug
        );
    }

    /**
     * @param array<string,mixed> $values
     * @return array<string,mixed>
     */
    protected function sanitizeCallback(
        array $values,
        string $settingsCategory,
    ): array {
        return $this->getSettingsNormalizer()->normalizeSettingsByCategory($values, $settingsCategory);
    }

    protected function flushContainer(
        bool $flushRewriteRules,
        ?bool $regenerateContainer,
    ): void {
        if ($flushRewriteRules) {
            \flush_rewrite_rules();
        }

        /**
         * Update the timestamp, and maybe regenerate
         * the service container.
         */
        if ($regenerateContainer === null) {
            /**
             * The System/Application Service Containers need to be regenerated
             * when updating the plugin Settings only if Services can be added
             * or not to the Container based on the context.
             *
             * @var ComponentModelModuleConfiguration
             */
            $moduleConfiguration = App::getModule(ComponentModelModule::class)->getConfiguration();
            $regenerateContainer = $moduleConfiguration->supportDefiningServicesInTheContainerBasedOnTheContext();
        }
        if ($regenerateContainer) {
            $this->getUserSettingsManager()->storeContainerTimestamp();
        } else {
            $this->getUserSettingsManager()->storeOperationalTimestamp();
        }
    }

    protected function getOptionsFormModuleSectionName(string $optionsFormName, string $moduleID): string
    {
        return $optionsFormName . '-' . $moduleID;
    }

    /**
     * The user can define this behavior through the Settings.
     *
     * - If `true`, print the module sections using tabs
     * - If `false`, print the module sections one below the other
     *
     * The outer sections, i.e. settings category, always uses tabs
     */
    protected function printModuleSettingsWithTabs(): bool
    {
        return $this->getUserSettingsManager()->getSetting(
            PluginGeneralSettingsFunctionalityModuleResolver::GENERAL,
            PluginGeneralSettingsFunctionalityModuleResolver::OPTION_PRINT_SETTINGS_WITH_TABS
        );
    }

    /**
     * Print the settings form
     */
    public function print(): void
    {
        $settingsItems = $this->getSettingsNormalizer()->getAllSettingsItems();
        if (!$settingsItems) {
            esc_html_e('There are no items to be configured', 'gatographql');
            return;
        }

        $printModuleSettingsWithTabs = $this->printModuleSettingsWithTabs();

        $settingsCategoryRegistry = $this->getSettingsCategoryRegistry();
        $primarySettingsCategorySettingsCategoryResolvers = $settingsCategoryRegistry->getSettingsCategorySettingsCategoryResolvers();

        /**
         * Find out which primary tab will be selected:
         * Either the one whose ID is passed by ?category=...,
         * or the 1st one otherwise.
         */
        $activeCategoryID = null;
        $activeCategory = App::query(RequestParams::CATEGORY);
        if ($activeCategory !== null) {
            foreach ($primarySettingsCategorySettingsCategoryResolvers as $settingsCategory => $settingsCategoryResolver) {
                $settingsCategoryID = $settingsCategoryResolver->getID($settingsCategory);
                if ($settingsCategoryID !== $activeCategory) {
                    continue;
                }
                $activeCategoryID = $settingsCategoryID;
                break;
            }
        }
        if ($activeCategoryID === null) {
            /** @var string */
            $firstSettingsCategory = key($primarySettingsCategorySettingsCategoryResolvers);
            $activeCategoryID = $primarySettingsCategorySettingsCategoryResolvers[$firstSettingsCategory]->getID($firstSettingsCategory);
        }

        $activeModule = App::query(RequestParams::MODULE);
        $class = 'wrap';
        if ($printModuleSettingsWithTabs) {
            $class .= ' gatographql-tabpanel vertical-tabs';
        }

        // This page URL
        $url = admin_url(sprintf(
            'admin.php?page=%s',
            esc_attr(App::request('page') ?? App::query('page', ''))
        ));

        $time = time();

        // Specify to only toggle the outer .tab-content divs (skip the inner ones)
        ?>
            <div
                id="gatographql-primary-settings"
                class="wrap gatographql-tabpanel"
                data-tab-content-target="#gatographql-primary-settings-nav-tab-content > .tab-content"
            >
                <h1><?php \esc_html_e('Gato GraphQL — Settings', 'gatographql'); ?></h1>
                <?php \settings_errors(); ?>
                <div class="nav-tab-container">
                    <!-- Tabs -->
                    <h2 class="nav-tab-wrapper">
                        <?php
                        foreach ($primarySettingsCategorySettingsCategoryResolvers as $settingsCategory => $settingsCategoryResolver) {
                            // Make sure the category has items, otherwise skip
                            $categorySettingsItems = $this->getCategorySettingsItems(
                                $settingsCategory,
                                $settingsItems,
                            );
                            if ($categorySettingsItems === []) {
                                continue;
                            }
                            $settingsCategoryID = $settingsCategoryResolver->getID($settingsCategory);
                            ?>
                                <a
                                    href="#<?php echo \esc_attr($settingsCategoryID) ?>"
                                    class="nav-tab <?php echo \esc_attr($settingsCategoryID === $activeCategoryID ? 'nav-tab-active' : '') ?>"
                                >
                                    <?php echo \esc_html($settingsCategoryResolver->getName($settingsCategory)) ?>
                                </a>
                            <?php
                        }
                        ?>
                    </h2>
                    <div id="gatographql-primary-settings-nav-tab-content" class="nav-tab-content">
                        <?php
                        foreach ($primarySettingsCategorySettingsCategoryResolvers as $settingsCategory => $settingsCategoryResolver) {
                            $settingsCategoryID = $settingsCategoryResolver->getID($settingsCategory);
                            $optionsFormName = $settingsCategoryResolver->getOptionsFormName($settingsCategory);
                            $sectionStyle = sprintf(
                                'display: %s;',
                                $settingsCategoryID === $activeCategoryID ? 'block' : 'none'
                            );
                            $categorySettingsItems = $this->getCategorySettingsItems(
                                $settingsCategory,
                                $settingsItems,
                            );
                            if ($categorySettingsItems === []) {
                                continue;
                            }
                            ?>
                            <div id="<?php echo \esc_attr($settingsCategoryID) ?>" class="tab-content" style="<?php echo \esc_attr($sectionStyle) ?>">
                            <?php
                                // By default, focus on the first module
                                $activeModuleID = $categorySettingsItems[0]['id'];
                                // If passing a tab, focus on that one, if the module exists
                            if ($activeModule !== null) {
                                $moduleIDs = array_map(
                                    fn ($item) => $item['id'],
                                    $categorySettingsItems
                                );
                                if (in_array($activeModule, $moduleIDs)) {
                                    $activeModuleID = $activeModule;
                                }
                            }
                            ?>
                                <div class="<?php echo \esc_attr($class) ?>">
                                    <?php if ($printModuleSettingsWithTabs) : ?>
                                        <div class="nav-tab-container">
                                            <!-- Tabs -->
                                            <h2 class="nav-tab-wrapper">
                                                <?php
                                                foreach ($categorySettingsItems as $item) {
                                                    /**
                                                     * Also add the tab to the URL, not because it is needed,
                                                     * but because we can then "Open in new tab" and it will
                                                     * be focused already on that item.
                                                     */
                                                    $settingsURL = sprintf(
                                                        '%1$s&%2$s=%3$s&%4$s=%5$s',
                                                        $url,
                                                        RequestParams::CATEGORY,
                                                        $settingsCategoryID,
                                                        RequestParams::MODULE,
                                                        $item['id']
                                                    );
                                                    ?>
                                                        <a
                                                            data-tab-target="#<?php echo \esc_attr($item['id']) ?>"
                                                            href="<?php echo \esc_url($settingsURL) ?>"
                                                            class="nav-tab <?php echo esc_attr($item['id'] === $activeModuleID ? 'nav-tab-active' : '') ?>"
                                                        >
                                                            <?php echo \esc_html($item['name']) ?>
                                                        </a>
                                                    <?php
                                                }
                                                ?>
                                            </h2>
                                            <div class="nav-tab-content">
                                    <?php endif; ?>
                                                <form method="post" action="options.php">
                                                    <!-- Artificial input as flag that the form belongs to this plugin -->
                                                    <input type="hidden" name="<?php echo \esc_attr(self::FORM_ORIGIN) ?>" value="<?php echo \esc_attr($optionsFormName) ?>" />
                                                    <!--
                                                        Artificial input to trigger the update of the form always, as to always purge the container/operational cache
                                                        (eg: to include 3rd party extensions in the service container, or new Gutenberg blocks)
                                                        This is needed because "If the new and old values are the same, no need to update."
                                                        which makes "update_option_{$option}" not be triggered when there are no changes
                                                        @see wp-includes/option.php
                                                    -->
                                                    <input type="hidden" name="<?php echo \esc_attr($optionsFormName)?>[<?php echo \esc_attr(self::FORM_FIELD_LAST_SAVED_TIMESTAMP) ?>]" value="<?php echo \esc_attr((string)$time) ?>">
                                                    <?php if (RequestHelpers::isRequestingXDebug()) : ?>
                                                        <input type="hidden" name="<?php echo \esc_attr(FrameworkParams::XDEBUG_TRIGGER) ?>" value="1">
                                                        <input type="hidden" name="<?php echo \esc_attr(FrameworkParams::XDEBUG_SESSION_STOP) ?>" value="1">
                                                    <?php endif; ?>
                                                    <!-- Panels -->
                                                    <?php
                                                    $sectionClass = $printModuleSettingsWithTabs ? 'tab-content' : '';
                                                    \settings_fields($optionsFormName);
                                                    foreach ($categorySettingsItems as $item) {
                                                        $sectionStyle = '';
                                                        if ($printModuleSettingsWithTabs) {
                                                            $sectionStyle = sprintf(
                                                                'display: %s;',
                                                                $item['id'] === $activeModuleID ? 'block' : 'none'
                                                            );
                                                        }
                                                        ?>
                                                        <div id="<?php echo \esc_attr($item['id']) ?>" class="gatographql-settings-section <?php echo \esc_attr($sectionClass) ?>" style="<?php echo \esc_attr($sectionStyle) ?>">
                                                            <?php if ($printModuleSettingsWithTabs) { ?>
                                                                <h2><?php echo \esc_html($item['name']) ?></h2><hr/>
                                                            <?php } else { ?>
                                                                <br/><h2 id="<?php echo \esc_attr($item['id']) ?>"><?php echo \esc_html($item['name']) ?></h2>
                                                            <?php } ?>
                                                            <table class="form-table">
                                                                <?php \do_settings_fields($optionsFormName, $this->getOptionsFormModuleSectionName($optionsFormName, $item['id'])) ?>
                                                            </table>
                                                            <br/>
                                                            <hr/>
                                                        </div>
                                                        <?php
                                                    }
                                                    if ($settingsCategoryResolver->addOptionsFormSubmitButton($settingsCategory)) {
                                                        \submit_button(
                                                            \__('Save Changes (All)', 'gatographql')
                                                        );
                                                    }
                                                    ?>
                                                </form>
                                    <?php if ($printModuleSettingsWithTabs) : ?>
                                            </div> <!-- class="nav-tab-content" -->
                                        </div> <!-- class="nav-tab-container" -->
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
        <?php
    }

    /**
     * Filter all the category settings that must be printed
     * under the current section
     *
     * @param array<array<string,mixed>> $settingsItems
     * @return array<array<string,mixed>>
     */
    protected function getCategorySettingsItems(
        string $settingsCategory,
        array $settingsItems,
    ): array {
        return array_values(array_filter(
            $settingsItems,
            /** @param array<string,mixed> $settingsItem */
            fn (array $settingsItem) => $settingsItem['settings-category'] === $settingsCategory
        ));
    }

    /**
     * Enqueue the required assets and initialize the localized scripts
     */
    protected function enqueueAssets(): void
    {
        parent::enqueueAssets();

        $this->enqueueDocsAssets();

        $this->enqueueSettingsAssets();

        $this->enqueueCollapsibleContentAssets();

        /**
         * Always enqueue (even if printModuleSettingsWithTabs() is false) as the
         * outer level (for settings category) uses tabs
         */
        $this->enqueueTabpanelAssets();
    }

    /**
     * Enqueue the required assets
     */
    protected function enqueueSettingsAssets(): void
    {
        $mainPlugin = PluginApp::getMainPlugin();
        $mainPluginURL = $mainPlugin->getPluginURL();
        $mainPluginVersion = $mainPlugin->getPluginVersion();

        \wp_enqueue_script(
            'gatographql-settings',
            $mainPluginURL . 'assets/js/settings.js',
            array('jquery'),
            $mainPluginVersion
        );
        \wp_enqueue_style(
            'gatographql-settings',
            $mainPluginURL . 'assets/css/settings.css',
            array(),
            $mainPluginVersion
        );
    }

    /**
     * Get the option value
     */
    protected function getOptionValue(string $module, string $option): mixed
    {
        return $this->getUserSettingsManager()->getSetting($module, $option);
    }

    /**
     * Display a checkbox field.
     *
     * @param array<string,mixed> $itemSetting
     */
    protected function printCheckboxField(string $optionsFormName, string $module, array $itemSetting): void
    {
        $name = $itemSetting[Properties::NAME];
        $input = $itemSetting[Properties::INPUT];
        $value = $this->getOptionValue($module, $input);
        $description_safe = $itemSetting[Properties::DESCRIPTION] ?? '';
        ?>
            <label for="<?php echo \esc_attr($name); ?>">
                <input type="checkbox" name="<?php echo \esc_attr($optionsFormName . '[' . $name . ']'); ?>" id="<?php echo \esc_attr($name); ?>" value="1" <?php checked(1, $value); ?> />
                <?php echo $description_safe; ?>
            </label>
        <?php
    }

    /**
     * Display a label
     *
     * @param array<string,mixed> $itemSetting
     */
    protected function printLabelField(string $optionsFormName, string $module, array $itemSetting): void
    {
        $description_safe = $itemSetting[Properties::DESCRIPTION] ?? '';
        ?>
            <?php echo $description_safe; ?>
        <?php
    }

    /**
     * Display an input field.
     *
     * @param array<string,mixed> $itemSetting
     */
    protected function printInputField(string $optionsFormName, string $module, array $itemSetting): void
    {
        $name = $itemSetting[Properties::NAME];
        $input = $itemSetting[Properties::INPUT];
        $value = $this->getOptionValue($module, $input);
        $label_safe = isset($itemSetting[Properties::DESCRIPTION]) ? '<br/>' . $itemSetting[Properties::DESCRIPTION] : '';
        $isNumber = isset($itemSetting[Properties::TYPE]) && $itemSetting[Properties::TYPE] === Properties::TYPE_INT;
        $minNumber = null;
        if ($isNumber) {
            $minNumber = $itemSetting[Properties::MIN_NUMBER] ?? null;
        }
        ?>
            <label for="<?php echo \esc_attr($name); ?>">
                <input
                    name="<?php echo \esc_attr($optionsFormName . '[' . $name . ']'); ?>"
                    id="<?php echo \esc_attr($name); ?>"
                    value="<?php echo \esc_attr($value); ?>"
                    <?php if ($isNumber) { ?>
                        type="number"
                        step="1"
                        <?php if ($minNumber !== null) { ?>
                            min="<?php echo \esc_attr($minNumber) ?>"
                        <?php } ?>
                    <?php } else { ?>
                        type="text"
                    <?php } ?>
                />
                <?php echo $label_safe; ?>
            </label>
        <?php
    }

    /**
     * Display a "Property Array" field as a collection of inputs
     *
     * @param array<string,mixed> $itemSetting
     */
    protected function printMultiInputField(string $optionsFormName, string $module, array $itemSetting): void
    {
        $name = $itemSetting[Properties::NAME];
        $input = $itemSetting[Properties::INPUT];
        $value = $this->getOptionValue($module, $input);
        // If it is multiple, $value is an array.
        // To simplify, deal always with arrays
        if (!is_array($value)) {
            $value = $value === null ? [] : [$value];
        }
        $addSpacing = false;
        if (isset($itemSetting[Properties::DESCRIPTION])) {
            $addSpacing = true;
            $description_safe = $itemSetting[Properties::DESCRIPTION];
            echo $description_safe;
        }
        $keyLabels = $itemSetting[Properties::KEY_LABELS] ?? [];
        foreach ($keyLabels as $key => $label) {
            $id = $name . '_' . $key;
            if ($addSpacing) {
                ?>
                <br/><br/>
                <?php
            }
            ?>
            <label for="<?php echo \esc_attr($id) ?>">
                <strong><?php echo \esc_html($label); ?></strong>
                <br/>
                <input name="<?php echo \esc_attr($optionsFormName . '[' . $name . '][' . $key . ']'); ?>" id="<?php echo \esc_attr($id) ?>" value="<?php echo \esc_html($value[$key] ?? '') ?>" type="text">
            </label>
            <?php
            $addSpacing = true;
        }
    }

    /**
     * Display a select field.
     *
     * @param array<string,mixed> $itemSetting
     */
    protected function printSelectField(string $optionsFormName, string $module, array $itemSetting): void
    {
        $name = $itemSetting[Properties::NAME];
        $input = $itemSetting[Properties::INPUT];
        $value = $this->getOptionValue($module, $input);
        // If it is multiple, $value is an array.
        // To simplify, deal always with arrays
        if (!is_array($value)) {
            $value = $value === null ? [] : [$value];
        }
        $label_safe = isset($itemSetting[Properties::DESCRIPTION]) ? '<br/>' . $itemSetting[Properties::DESCRIPTION] : '';
        $isMultiple = $itemSetting[Properties::IS_MULTIPLE] ?? false;
        $possibleValues = $itemSetting[Properties::POSSIBLE_VALUES] ?? [];
        ?>
            <label for="<?php echo \esc_attr($name); ?>">
                <select
                    name="<?php echo \esc_attr($optionsFormName . '[' . $name . ']' . ($isMultiple ? '[]' : '')); ?>"
                    id="<?php echo \esc_attr($name); ?>"
                    <?php if ($isMultiple) : ?>
                        multiple="multiple"
                        size="10"
                    <?php endif; ?>
                >
                <?php foreach ($possibleValues as $optionValue => $optionLabel) : ?>
                    <option
                        value="<?php echo \esc_attr($optionValue) ?>"
                        <?php if (in_array($optionValue, $value)) : ?>
                            selected="selected"
                        <?php endif; ?>
                    >
                        <?php echo \esc_html($optionLabel) ?>
                    </option>
                <?php endforeach ?>
                </select>
                <?php echo $label_safe; ?>
            </label>
        <?php
    }

    /**
     * Display a textarea field.
     *
     * @param array<string,mixed> $itemSetting
     */
    protected function printTextareaField(string $optionsFormName, string $module, array $itemSetting): void
    {
        $name = $itemSetting[Properties::NAME];
        $input = $itemSetting[Properties::INPUT];
        // This must be an array
        $value = $this->getOptionValue($module, $input);
        $label_safe = isset($itemSetting[Properties::DESCRIPTION]) ? '<br/>' . $itemSetting[Properties::DESCRIPTION] : '';
        ?>
            <label for="<?php echo \esc_attr($name); ?>">
                <textarea name="<?php echo \esc_attr($optionsFormName . '[' . $name . ']'); ?>" id="<?php echo \esc_attr($name); ?>" rows="10" cols="50"><?php echo \esc_html(implode("\n", $value)) ?></textarea>
                <?php echo $label_safe; ?>
            </label>
        <?php
    }
}
