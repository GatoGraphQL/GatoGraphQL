<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\MenuPages;

use GatoGraphQL\GatoGraphQL\Container\ContainerManagerInterface;
use GatoGraphQL\GatoGraphQL\Marketplace\LicenseValidationServiceInterface;
use GatoGraphQL\GatoGraphQL\ModuleResolvers\EndpointFunctionalityModuleResolver;
use GatoGraphQL\GatoGraphQL\ModuleResolvers\PluginManagementFunctionalityModuleResolver;
use GatoGraphQL\GatoGraphQL\Registries\ModuleRegistryInterface;
use GatoGraphQL\GatoGraphQL\SettingsCategoryResolvers\SettingsCategoryResolver;
use GatoGraphQL\GatoGraphQL\Settings\OptionNamespacerInterface;
use GatoGraphQL\GatoGraphQL\Settings\Options;

use function __;
use function add_action;
use function update_option;

/**
 * Settings menu page
 */
class SettingsMenuPage extends AbstractSettingsMenuPage
{
    public final const RESET_SETTINGS_BUTTON_ID = 'submit-reset-settings';
    public final const ACTIVATE_EXTENSIONS_BUTTON_ID = 'submit-activate-extensions';

    private ?ModuleRegistryInterface $moduleRegistry = null;
    private ?LicenseValidationServiceInterface $licenseValidationService = null;
    private ?ContainerManagerInterface $containerManager = null;
    private ?OptionNamespacerInterface $optionNamespacer = null;

    final protected function getModuleRegistry(): ModuleRegistryInterface
    {
        if ($this->moduleRegistry === null) {
            /** @var ModuleRegistryInterface */
            $moduleRegistry = $this->instanceManager->getInstance(ModuleRegistryInterface::class);
            $this->moduleRegistry = $moduleRegistry;
        }
        return $this->moduleRegistry;
    }
    final protected function getLicenseValidationService(): LicenseValidationServiceInterface
    {
        if ($this->licenseValidationService === null) {
            /** @var LicenseValidationServiceInterface */
            $licenseValidationService = $this->instanceManager->getInstance(LicenseValidationServiceInterface::class);
            $this->licenseValidationService = $licenseValidationService;
        }
        return $this->licenseValidationService;
    }
    final protected function getContainerManager(): ContainerManagerInterface
    {
        if ($this->containerManager === null) {
            /** @var ContainerManagerInterface */
            $containerManager = $this->instanceManager->getInstance(ContainerManagerInterface::class);
            $this->containerManager = $containerManager;
        }
        return $this->containerManager;
    }
    final protected function getOptionNamespacer(): OptionNamespacerInterface
    {
        if ($this->optionNamespacer === null) {
            /** @var OptionNamespacerInterface */
            $optionNamespacer = $this->instanceManager->getInstance(OptionNamespacerInterface::class);
            $this->optionNamespacer = $optionNamespacer;
        }
        return $this->optionNamespacer;
    }

    public function getScreenID(): string
    {
        $isPrivateEndpointDisabled = !$this->getModuleRegistry()->isModuleEnabled(EndpointFunctionalityModuleResolver::PRIVATE_ENDPOINT);
        if ($isPrivateEndpointDisabled) {
            /**
             * Override, because this is the default page, so it is invoked
             * with the menu slug wp-admin/admin.php?page=gatographql,
             * and not the menu page slug wp-admin/admin.php?page=gatographql_settings
             */
            return $this->getMenuName();
        }
        return parent::getScreenID();
    }

    public function getMenuPageSlug(): string
    {
        return 'settings';
    }

    public function getMenuPageTitle(): string
    {
        return __('Settings', 'gatographql');
    }

    protected function getOptionsFormNamePrefix(): string
    {
        return '';
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
        add_action(
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
                    $this->getLicenseValidationService()->activateDeactivateValidateGatoGraphQLCommercialExtensions(
                        $oldValue[$settingOptionName] ?? [],
                        $values[$settingOptionName] ?? [],
                        PluginManagementFunctionalityModuleResolver::ACTIVATE_EXTENSIONS,
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
            add_action(
                "update_option_{$option}",
                fn () => $this->getContainerManager()->flushContainer(
                    $flushRewriteRules,
                    $regenerateContainer,
                )
            );
        }
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
        $resetOptions = array_map(
            $this->getOptionNamespacer()->namespaceOption(...),
            [
                Options::ENDPOINT_CONFIGURATION,
                Options::SCHEMA_CONFIGURATION,
                Options::SCHEMA_TYPE_CONFIGURATION,
                Options::SERVER_CONFIGURATION,
                Options::PLUGIN_CONFIGURATION,
            ]
        );
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
        $this->getContainerManager()->flushContainer(true, $regenerateContainer);
    }

    protected function addPluginNameToPageTitle(): bool
    {
        return true;
    }
}
