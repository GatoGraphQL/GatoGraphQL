<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\MenuPages;

use GraphQLAPI\GraphQLAPI\App;
use GraphQLAPI\GraphQLAPI\Constants\RequestParams;
use GraphQLAPI\GraphQLAPI\Facades\UserSettingsManagerFacade;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\PluginGeneralSettingsFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\ModuleSettings\Properties;
use GraphQLAPI\GraphQLAPI\PluginApp;
use GraphQLAPI\GraphQLAPI\Registries\SettingsCategoryRegistryInterface;
use GraphQLAPI\GraphQLAPI\SettingsCategoryResolvers\SettingsCategoryResolver;
use GraphQLAPI\GraphQLAPI\Settings\Options;
use GraphQLAPI\GraphQLAPI\Settings\SettingsNormalizerInterface;
use GraphQLAPI\GraphQLAPI\Settings\UserSettingsManagerInterface;
use PoP\ComponentModel\Configuration\RequestHelpers;
use PoP\ComponentModel\Constants\FrameworkParams;
use PoP\ComponentModel\Module as ComponentModelModule;
use PoP\ComponentModel\ModuleConfiguration as ComponentModelModuleConfiguration;

/**
 * Settings menu page
 */
class SettingsMenuPage extends AbstractPluginMenuPage
{
    use UseTabpanelMenuPageTrait;
    use UseDocsMenuPageTrait;

    public final const FORM_ORIGIN = 'form-origin';
    public final const RESET_SETTINGS_BUTTON_ID = 'submit-reset-settings';

    private ?UserSettingsManagerInterface $userSettingsManager = null;
    private ?SettingsNormalizerInterface $settingsNormalizer = null;
    private ?PluginGeneralSettingsFunctionalityModuleResolver $PluginGeneralSettingsFunctionalityModuleResolver = null;
    private ?SettingsCategoryRegistryInterface $settingsCategoryRegistry = null;

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
        /** @var SettingsNormalizerInterface */
        return $this->settingsNormalizer ??= $this->instanceManager->getInstance(SettingsNormalizerInterface::class);
    }
    final public function setPluginGeneralSettingsFunctionalityModuleResolver(PluginGeneralSettingsFunctionalityModuleResolver $PluginGeneralSettingsFunctionalityModuleResolver): void
    {
        $this->PluginGeneralSettingsFunctionalityModuleResolver = $PluginGeneralSettingsFunctionalityModuleResolver;
    }
    final protected function getPluginGeneralSettingsFunctionalityModuleResolver(): PluginGeneralSettingsFunctionalityModuleResolver
    {
        /** @var PluginGeneralSettingsFunctionalityModuleResolver */
        return $this->PluginGeneralSettingsFunctionalityModuleResolver ??= $this->instanceManager->getInstance(PluginGeneralSettingsFunctionalityModuleResolver::class);
    }
    final public function setSettingsCategoryRegistry(SettingsCategoryRegistryInterface $settingsCategoryRegistry): void
    {
        $this->settingsCategoryRegistry = $settingsCategoryRegistry;
    }
    final protected function getSettingsCategoryRegistry(): SettingsCategoryRegistryInterface
    {
        /** @var SettingsCategoryRegistryInterface */
        return $this->settingsCategoryRegistry ??= $this->instanceManager->getInstance(SettingsCategoryRegistryInterface::class);
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

        $option = $settingsCategoryRegistry->getSettingsCategoryResolver(SettingsCategoryResolver::PLUGIN_MANAGEMENT)->getOptionsFormName(SettingsCategoryResolver::PLUGIN_MANAGEMENT);
        \add_action(
            "update_option_{$option}",
            /**
             * @param array<string,mixed> $values
             * @param array<string,mixed> $previousValues
             * @return array<string,mixed>
             */
            function (array $values): void {
                /**
                 * Check that pressed on the "Reset Settings" button
                 */
                if (!isset($values[self::RESET_SETTINGS_BUTTON_ID])) {
                    return;
                }
                $this->resetSettings();
            },
            10,
            1
        );

        /**
         * Keep this variable for if "Plugin Configuration" eventually
         * needs to regenerate the container once again.
         */
        $doesPluginConfigurationSettingsAffectTheServiceContainer = false;
        $regenerateConfigSettingsCategories = [
            'schema' => SettingsCategoryResolver::SCHEMA_CONFIGURATION,
            'endpoint' => SettingsCategoryResolver::ENDPOINT_CONFIGURATION,
            'plugin' => SettingsCategoryResolver::PLUGIN_CONFIGURATION,
        ];
        $regenerateConfigFormOptions = array_map(
            fn (string $settingsCategory) => $settingsCategoryRegistry->getSettingsCategoryResolver($settingsCategory)->getOptionsFormName($settingsCategory),
            $regenerateConfigSettingsCategories
        );
        foreach ($regenerateConfigFormOptions as $option) {
            $regenerateContainer = null;
            if ($doesPluginConfigurationSettingsAffectTheServiceContainer // @phpstan-ignore-line
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
                                        <div id="section-<?php echo $itemSetting[Properties::NAME] ?>" class="graphql-api-settings-item" <?php if (!empty($cssStyle)) :
                                            ?>style="<?php echo $cssStyle ?>"<?php
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
     * Delete the Settings and flush
     */
    protected function resetSettings(): void
    {
        $userSettingsManager = $this->getUserSettingsManager();
        $resetOptions = [
            Options::SCHEMA_CONFIGURATION,
            Options::ENDPOINT_CONFIGURATION,
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
            _e('There are no items to be configured', 'graphql-api');
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
            $class .= ' graphql-api-tabpanel vertical-tabs';
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
                id="graphql-api-primary-settings"
                class="wrap graphql-api-tabpanel"
                data-tab-content-target="#graphql-api-primary-settings-nav-tab-content > .tab-content"
            >
                <h1><?php \_e('GraphQL API — Settings', 'graphql-api'); ?></h1>
                <?php \settings_errors(); ?>
                <div class="nav-tab-container">
                    <!-- Tabs -->
                    <h2 class="nav-tab-wrapper">
                        <?php
                        foreach ($primarySettingsCategorySettingsCategoryResolvers as $settingsCategory => $settingsCategoryResolver) {
                            $settingsCategoryID = $settingsCategoryResolver->getID($settingsCategory);
                            printf(
                                '<a href="#%s" class="nav-tab %s">%s</a>',
                                $settingsCategoryID,
                                $settingsCategoryID === $activeCategoryID ? 'nav-tab-active' : '',
                                $settingsCategoryResolver->getName($settingsCategory)
                            );
                        }
                        ?>
                    </h2>
                    <div id="graphql-api-primary-settings-nav-tab-content" class="nav-tab-content">
                        <?php
                        foreach ($primarySettingsCategorySettingsCategoryResolvers as $settingsCategory => $settingsCategoryResolver) {
                            $settingsCategoryID = $settingsCategoryResolver->getID($settingsCategory);
                            $optionsFormName = $settingsCategoryResolver->getOptionsFormName($settingsCategory);
                            $sectionStyle = sprintf(
                                'display: %s;',
                                $settingsCategoryID === $activeCategoryID ? 'block' : 'none'
                            );
                            ?>
                            <div id="<?php echo $settingsCategoryID ?>" class="tab-content" style="<?php echo $sectionStyle ?>">
                            <?php
                                /**
                                 * Filter all the category settings that must be printed
                                 * under the current section
                                 */
                                $categorySettingsItems = array_values(array_filter(
                                    $settingsItems,
                                    /** @param array<string,mixed> $item */
                                    fn (array $settingsItem) => $settingsItem['settings-category'] === $settingsCategory
                                ));
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
                                <div class="<?php echo $class ?>">
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
                                                    printf(
                                                        '<a data-tab-target="%s" href="%s" class="nav-tab %s">%s</a>',
                                                        '#' . $item['id'],
                                                        $settingsURL,
                                                        $item['id'] === $activeModuleID ? 'nav-tab-active' : '',
                                                        $item['name']
                                                    );
                                                }
                                                ?>
                                            </h2>
                                            <div class="nav-tab-content">
                                    <?php endif; ?>
                                                <form method="post" action="options.php">
                                                    <!-- Artificial input as flag that the form belongs to this plugin -->
                                                    <input type="hidden" name="<?php echo self::FORM_ORIGIN ?>" value="<?php echo $optionsFormName ?>" />
                                                    <!--
                                                        Artificial input to trigger the update of the form always, as to always purge the container/operational cache
                                                        (eg: to include 3rd party extensions in the service container, or new Gutenberg blocks)
                                                        This is needed because "If the new and old values are the same, no need to update."
                                                        which makes "update_option_{$option}" not be triggered when there are no changes
                                                        @see wp-includes/option.php
                                                    -->
                                                    <input type="hidden" name="<?php echo $optionsFormName?>[last_saved_timestamp]" value="<?php echo $time ?>">
                                                    <?php if (RequestHelpers::isRequestingXDebug()): ?>
                                                        <input type="hidden" name="<?php echo FrameworkParams::XDEBUG_TRIGGER ?>" value="1">
                                                        <input type="hidden" name="<?php echo FrameworkParams::XDEBUG_SESSION_STOP ?>" value="1">
                                                    <?php endif; ?>
                                                    <!-- Panels -->
                                                    <?php
                                                    $sectionClass = $printModuleSettingsWithTabs ? 'tab-content' : '';
                                                    \settings_fields($optionsFormName);
                                                    foreach ($categorySettingsItems as $item) {
                                                        $sectionStyle = '';
                                                        $title = $printModuleSettingsWithTabs
                                                            ? sprintf(
                                                                '<h2>%s</h2><hr/>',
                                                                $item['name']
                                                            ) : sprintf(
                                                                '<br/><h2 id="%s">%s</h2>',
                                                                $item['id'],
                                                                $item['name']
                                                            );
                                                        if ($printModuleSettingsWithTabs) {
                                                            $sectionStyle = sprintf(
                                                                'display: %s;',
                                                                $item['id'] === $activeModuleID ? 'block' : 'none'
                                                            );
                                                        }
                                                        ?>
                                                        <div id="<?php echo $item['id'] ?>" class="graphql-api-settings-section <?php echo $sectionClass ?>" style="<?php echo $sectionStyle ?>">
                                                            <?php echo $title ?>
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
                                                            \__('Save Changes (All)', 'graphql-api')
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
     * Enqueue the required assets and initialize the localized scripts
     */
    protected function enqueueAssets(): void
    {
        parent::enqueueAssets();

        $this->enqueueDocsAssets();

        $this->enqueueSettingsAssets();

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
        $mainPluginURL = PluginApp::getMainPlugin()->getPluginURL();
        $mainPluginVersion = PluginApp::getMainPlugin()->getPluginVersion();

        \wp_enqueue_script(
            'graphql-api-settings',
            $mainPluginURL . 'assets/js/settings.js',
            array('jquery'),
            $mainPluginVersion
        );
        \wp_enqueue_style(
            'graphql-api-settings',
            $mainPluginURL . 'assets/css/settings.css',
            array(),
            $mainPluginVersion
        );

        \wp_enqueue_script(
            'graphql-api-collapse',
            $mainPluginURL . 'assets/js/collapse.js',
            array('jquery'),
            $mainPluginVersion
        );
        \wp_enqueue_style(
            'graphql-api-collapse',
            $mainPluginURL . 'assets/css/collapse.css',
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
        ?>
            <label for="<?php echo $name; ?>">
                <input type="checkbox" name="<?php echo $optionsFormName . '[' . $name . ']'; ?>" id="<?php echo $name; ?>" value="1" <?php checked(1, $value); ?> />
                <?php echo $itemSetting[Properties::DESCRIPTION] ?? ''; ?>
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
        ?>
            <?php echo $itemSetting[Properties::DESCRIPTION] ?? ''; ?>
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
        $label = isset($itemSetting[Properties::DESCRIPTION]) ? '<br/>' . $itemSetting[Properties::DESCRIPTION] : '';
        $isNumber = isset($itemSetting[Properties::TYPE]) && $itemSetting[Properties::TYPE] === Properties::TYPE_INT;
        $minNumber = null;
        if ($isNumber) {
            $minNumber = $itemSetting[Properties::MIN_NUMBER] ?? null;
        }
        ?>
            <label for="<?php echo $name; ?>">
                <input name="<?php echo $optionsFormName . '[' . $name . ']'; ?>" id="<?php echo $name; ?>" value="<?php echo $value; ?>" <?php echo $isNumber ? ('type="number" step="1"' . (!is_null($minNumber) ? ' min="' . $minNumber . '"' : '')) : 'type="text"' ?>/>
                <?php echo $label; ?>
            </label>
        <?php
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
            $value = is_null($value) ? [] : [$value];
        }
        $label = isset($itemSetting[Properties::DESCRIPTION]) ? '<br/>' . $itemSetting[Properties::DESCRIPTION] : '';
        $isMultiple = $itemSetting[Properties::IS_MULTIPLE] ?? false;
        $possibleValues = $itemSetting[Properties::POSSIBLE_VALUES] ?? [];
        ?>
            <label for="<?php echo $name; ?>">
                <select name="<?php echo $optionsFormName . '[' . $name . ']' . ($isMultiple ? '[]' : ''); ?>" id="<?php echo $name; ?>" <?php echo $isMultiple ? 'multiple="multiple" size="10"' : ''; ?>>
                <?php foreach ($possibleValues as $optionValue => $optionLabel) : ?>
                    <?php $maybeSelected = in_array($optionValue, $value) ? 'selected="selected"' : ''; ?>
                    <option value="<?php echo $optionValue ?>" <?php echo $maybeSelected ?>>
                        <?php echo $optionLabel ?>
                    </option>
                <?php endforeach ?>
                </select>
                <?php echo $label; ?>
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
        $label = isset($itemSetting[Properties::DESCRIPTION]) ? '<br/>' . $itemSetting[Properties::DESCRIPTION] : '';
        ?>
            <label for="<?php echo $name; ?>">
                <textarea name="<?php echo $optionsFormName . '[' . $name . ']'; ?>" id="<?php echo $name; ?>" rows="10" cols="40"><?php echo implode("\n", $value) ?></textarea>
                <?php echo $label; ?>
            </label>
        <?php
    }
}
