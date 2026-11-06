<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\MenuPages;

use Exception;
use GatoGraphQL\GatoGraphQL\App;
use GatoGraphQL\GatoGraphQL\Constants\HookNames;
use GatoGraphQL\GatoGraphQL\Constants\RequestParams;
use GatoGraphQL\GatoGraphQL\Facades\UserSettingsManagerFacade;
use GatoGraphQL\GatoGraphQL\ModuleResolvers\PluginGeneralSettingsFunctionalityModuleResolver;
use GatoGraphQL\GatoGraphQL\ModuleSettings\Properties;
use GatoGraphQL\GatoGraphQL\PluginApp;
use GatoGraphQL\GatoGraphQL\Registries\ModuleRegistryInterface;
use GatoGraphQL\GatoGraphQL\Registries\SettingsCategoryRegistryInterface;
use GatoGraphQL\GatoGraphQL\SettingsCategoryResolvers\SettingsCategoryResolverInterface;
use GatoGraphQL\GatoGraphQL\Settings\SettingsNormalizerInterface;
use GatoGraphQL\GatoGraphQL\Settings\UserSettingsManagerInterface;
use GatoGraphQL\GatoGraphQL\StaticHelpers\SettingsControlHelpers;
use PoP\ComponentModel\Configuration\RequestHelpers;

use function __;
use function add_action;
use function add_query_arg;
use function add_settings_error;
use function admin_url;
use function apply_filters;
use function array_filter;
use function array_map;
use function array_merge;
use function array_values;
use function checked;
use function count;
use function current_user_can;
use function delete_transient;
use function error_log;
use function esc_attr;
use function esc_attr_e;
use function esc_html;
use function esc_html__;
use function esc_html_e;
use function esc_textarea;
use function esc_url;
use function get_current_user_id;
use function get_option;
use function get_plugin_page_hook;
use function get_settings_errors;
use function get_transient;
use function implode;
use function in_array;
use function ini_get;
use function is_array;
use function is_bool;
use function is_scalar;
use function is_string;
use function json_encode;
use function ksort;
use function preg_replace;
use function register_setting;
use function rtrim;
use function set_transient;
use function settings_errors;
use function settings_fields;
use function sprintf;
use function strtolower;
use function submit_button;
use function time;
use function trim;
use function update_option;
use function wp_die;
use function wp_localize_script;
use function wp_safe_redirect;
use function wp_send_json;
use function wp_strip_all_tags;
use function wp_unslash;
use function wp_validate_redirect;
use function wp_verify_nonce;

/**
 * Settings menu page
 */
abstract class AbstractSettingsMenuPage extends AbstractPluginMenuPage
{
    use UseTabpanelMenuPageTrait;
    use UseDocsMenuPageTrait;
    use UseCollapsibleContentMenuPageTrait;

    public final const FORM_ORIGIN = 'form-origin';
    public final const FORM_FIELD_LAST_SAVED_TIMESTAMP = 'last_saved_timestamp';

    /**
     * Saving the changes of several tabs at once posts them all,
     * under this param, to admin-post.php
     */
    public final const SAVE_ALL = 'gato-save-all';
    public final const SAVE_ALL_TABS = 'tabs';
    public final const SAVE_ALL_NONCES = 'nonces';
    public final const SAVE_ALL_FIELD_COUNT = 'fieldCount';
    public final const SAVE_ALL_REDIRECT = 'redirect';
    public final const SAVE_ALL_IS_PARTIAL = 'isPartial';
    public final const SAVE_ALL_IS_FIRST = 'isFirst';

    /**
     * A grid with more entries than this can be filtered
     */
    public final const GRID_FILTER_MIN_ENTRIES = 12;

    /**
     * @var array<array<string,mixed>>
     */
    protected ?array $settingsItems = null;

    private ?UserSettingsManagerInterface $userSettingsManager = null;
    private ?SettingsNormalizerInterface $settingsNormalizer = null;
    private ?SettingsCategoryRegistryInterface $settingsCategoryRegistry = null;
    private ?ModuleRegistryInterface $moduleRegistry = null;
    private ?PluginGeneralSettingsFunctionalityModuleResolver $PluginGeneralSettingsFunctionalityModuleResolver = null;

    final protected function getUserSettingsManager(): UserSettingsManagerInterface
    {
        return $this->userSettingsManager ??= UserSettingsManagerFacade::getInstance();
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
    final protected function getSettingsCategoryRegistry(): SettingsCategoryRegistryInterface
    {
        if ($this->settingsCategoryRegistry === null) {
            /** @var SettingsCategoryRegistryInterface */
            $settingsCategoryRegistry = $this->instanceManager->getInstance(SettingsCategoryRegistryInterface::class);
            $this->settingsCategoryRegistry = $settingsCategoryRegistry;
        }
        return $this->settingsCategoryRegistry;
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
    final protected function getPluginGeneralSettingsFunctionalityModuleResolver(): PluginGeneralSettingsFunctionalityModuleResolver
    {
        if ($this->PluginGeneralSettingsFunctionalityModuleResolver === null) {
            /** @var PluginGeneralSettingsFunctionalityModuleResolver */
            $PluginGeneralSettingsFunctionalityModuleResolver = $this->instanceManager->getInstance(PluginGeneralSettingsFunctionalityModuleResolver::class);
            $this->PluginGeneralSettingsFunctionalityModuleResolver = $PluginGeneralSettingsFunctionalityModuleResolver;
        }
        return $this->PluginGeneralSettingsFunctionalityModuleResolver;
    }

    /**
     * Get the settings items
     *
     * @return array<array<string,mixed>>
     */
    protected function getSettingsItems(): array
    {
        if ($this->settingsItems === null) {
            $this->settingsItems = $this->doGetSettingsItems();
        }
        return $this->settingsItems;
    }

    /**
     * Get the settings items which have this target
     *
     * @return array<array<string,mixed>>
     */
    protected function doGetSettingsItems(): array
    {
        return $this->getSettingsNormalizer()->getAllSettingsItems();
    }

    /**
     * Initialize the class instance
     */
    public function initialize(): void
    {
        parent::initialize();

        $settingsCategoryRegistry = $this->getSettingsCategoryRegistry();

        /**
         * Register the settings
         */
        add_action(
            'admin_init',
            function () use ($settingsCategoryRegistry): void {
                /**
                 * If for some reason SymfonyDI throws a ServiceNotFoundException,
                 * then catch it and do nothing (i.e. don't let the app explode)
                 */
                try {
                    foreach ($settingsCategoryRegistry->getSettingsCategorySettingsCategoryResolvers() as $settingsCategory => $settingsCategoryResolver) {
                        $optionsFormOrigin = $this->getOptionsFormOrigin($settingsCategoryResolver, $settingsCategory);
                        register_setting(
                            $optionsFormOrigin,
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
                } catch (Exception $exception) {
                    // Log the error, but otherwise do nothing
                    error_log($exception->__toString());
                }

                /**
                 * The changes of several tabs, saved with one click
                 */
                if (!$this->printsSingleFormForAllCategories()) {
                    add_action(
                        'admin_post_' . $this->getSaveAllActionName(),
                        $this->saveAllChanges(...)
                    );
                }

                /**
                 * Allow to execute further functionality on the Settings page
                 * (eg: to fetch the models data from the AI service)
                 */
                $pageHook = get_plugin_page_hook($this->getScreenID(), 'admin.php');
                add_action(
                    "load-{$pageHook}",
                    function (): void {
                        if (!$this->isCurrentScreen()) {
                            return;
                        }
                        App::doAction(
                            HookNames::EXECUTE_SETTINGS_PAGE_FUNCTIONALITY,
                            $this
                        );
                    }
                );
            }
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
        $values = $this->getSettingsNormalizer()->normalizeSettingsByCategory($values, $settingsCategory);
        return $this->keepStoredJSONTexts($values, $settingsCategory);
    }

    /**
     * The screen shows a JSON value indented, to read it. Saving it
     * keeps the text stored as it was, while it holds the same JSON.
     *
     * @param array<string,mixed> $values
     * @return array<string,mixed>
     */
    protected function keepStoredJSONTexts(
        array $values,
        string $settingsCategory,
    ): array {
        $storedValues = get_option($this->getSettingsCategoryRegistry()->getSettingsCategoryResolver($settingsCategory)->getDBOptionName($settingsCategory), []);
        if (!is_array($storedValues)) {
            return $values;
        }
        foreach ($this->getSettingsNormalizer()->getAllSettingsItems($settingsCategory) as $item) {
            /** @var array<array<string,mixed>> */
            $itemSettings = $item['settings'];
            foreach ($itemSettings as $itemSetting) {
                if (!($itemSetting[Properties::JSON] ?? false)) {
                    continue;
                }
                /** @var string */
                $name = $itemSetting[Properties::NAME];
                $value = $values[$name] ?? null;
                $storedValue = $storedValues[$name] ?? null;
                if (
                    is_string($value)
                    && is_string($storedValue)
                    && SettingsControlHelpers::isSameJSON($value, $storedValue)
                ) {
                    $values[$name] = $storedValue;
                }
            }
        }
        return $values;
    }

    protected function getOptionsFormModuleSectionName(string $optionsFormName, string $moduleID): string
    {
        return $optionsFormName . '-' . $moduleID;
    }

    /**
     * The same form can be used for several targets:
     *
     * - Settings
     * - Execute Action with custom Settings
     *
     * This function ensures the form name is unique for each target.
     */
    protected function getOptionsFormOrigin(SettingsCategoryResolverInterface $settingsCategoryResolver, string $settingsCategory): string
    {
        $prefix = $this->getOptionsFormNamePrefix();
        return (empty($prefix) ? '' : $prefix . '-') . $settingsCategoryResolver->getOptionsFormName($settingsCategory);
    }

    /**
     * Allow to register a different form to
     * Execute Action with custom Settings
     */
    protected function getOptionsFormNamePrefix(): string
    {
        return $this->getMenuPageSlug();
    }

    /**
     * The settings of each category are saved via their own form, and
     * the changes of several of them can be saved at once.
     *
     * A page that submits the settings of all categories together
     * (eg: to execute an action with them) prints a single form
     * around them all instead, and nothing to save them.
     */
    protected function printsSingleFormForAllCategories(): bool
    {
        return false;
    }

    public function getSaveAllActionName(): string
    {
        return $this->getScreenID() . '_save_all';
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
        $settingsItems = $this->getSettingsItems();
        if (!$settingsItems) {
            esc_html_e('There are no items to be configured', 'gatographql');
            return;
        }

        $printModuleSettingsWithTabs = $this->printModuleSettingsWithTabs();
        $printsSingleFormForAllCategories = $this->printsSingleFormForAllCategories();

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

        $canSaveAnyCategoryWithOthers = false;
        foreach ($primarySettingsCategorySettingsCategoryResolvers as $settingsCategory => $settingsCategoryResolver) {
            if (!$settingsCategoryResolver->canSaveWithOtherCategories($settingsCategory)) {
                continue;
            }
            $canSaveAnyCategoryWithOthers = true;
            break;
        }
        $printSaveAllBar = !$printsSingleFormForAllCategories && $canSaveAnyCategoryWithOthers;

        // Specify to only toggle the outer .tab-content divs (skip the inner ones)
        ?>
            <div
                id="gatographql-primary-settings"
                class="wrap gatographql-tabpanel gato-settings <?php echo esc_attr($this->getAdminUIThemeClass()) ?>"
                data-tab-content-target="#gatographql-primary-settings-nav-tab-content > .tab-content"
                <?php if ($printSaveAllBar) : ?>
                    data-save-all-url="<?php echo esc_url(admin_url('admin-post.php')) ?>"
                    data-save-all-action="<?php echo esc_attr($this->getSaveAllActionName()) ?>"
                    data-max-input-vars="<?php echo esc_attr((string) $this->getMaxInputVars()) ?>"
                <?php endif; ?>
            >
                <div class="gato-settings__top">
                    <h1><?php print(esc_html($this->getPageTitle())); ?></h1>
                    <div class="gato-search" data-js-only hidden>
                        <label class="gato-search__field">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="11" cy="11" r="7"></circle><path d="M20 20l-3.5-3.5"></path></svg>
                            <span class="screen-reader-text"><?php esc_html_e('Search all settings', 'gatographql') ?></span>
                            <input type="search" class="gato-search__input" placeholder="<?php esc_attr_e('Search all settings', 'gatographql') ?>" autocomplete="off" aria-controls="gato-search-results" />
                        </label>
                        <div id="gato-search-results" class="gato-search__results" role="listbox" hidden></div>
                    </div>
                </div>
                <hr class="wp-header-end">
                <?php settings_errors(); ?>
                <div class="nav-tab-container">
                    <!-- Tabs -->
                    <h2 class="nav-tab-wrapper gato-tabs">
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
                            /**
                             * Check this inside the foreach, so that if not all items are shown
                             * (eg: disabled modules in Standalone plugins), then the 1st item
                             * from the filtered elements will be used
                             */
                            if ($activeCategoryID === null) {
                                $activeCategoryID = $settingsCategoryID;
                            }
                            ?>
                                <a
                                    href="#<?php echo esc_attr($settingsCategoryID) ?>"
                                    class="nav-tab <?php echo esc_attr($settingsCategoryID === $activeCategoryID ? 'nav-tab-active' : '') ?>"
                                ><?php echo esc_html($settingsCategoryResolver->getName($settingsCategory)) ?><span class="gato-dot" hidden></span></a>
                            <?php
                        }
                        ?>
                        <span class="gato-tabs__more" data-js-only hidden>
                            <button type="button" class="gato-tabs__more-button" aria-haspopup="true" aria-expanded="false"><span class="gato-tabs__more-label"></span><span class="gato-dot" hidden></span><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="M6 9l6 6 6-6"></path></svg></button>
                            <span class="gato-tabs__menu" role="menu" hidden></span>
                        </span>
                    </h2>
                    <div id="gatographql-primary-settings-nav-tab-content" class="nav-tab-content">
                        <?php
                        foreach ($primarySettingsCategorySettingsCategoryResolvers as $settingsCategory => $settingsCategoryResolver) {
                            $settingsCategoryID = $settingsCategoryResolver->getID($settingsCategory);
                            $optionsFormName = $settingsCategoryResolver->getOptionsFormName($settingsCategory);
                            $optionsFormOrigin = $this->getOptionsFormOrigin($settingsCategoryResolver, $settingsCategory);
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
                            <div id="<?php echo esc_attr($settingsCategoryID) ?>" class="tab-content" style="<?php echo esc_attr($sectionStyle) ?>">
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
                                <div class="<?php echo esc_attr($class) ?>">
                                    <?php if ($printModuleSettingsWithTabs) : ?>
                                        <div class="nav-tab-container">
                                            <!-- Tabs -->
                                            <h2 class="nav-tab-wrapper gato-sidenav">
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
                                                            data-tab-target="#<?php echo esc_attr($item['id']) ?>"
                                                            href="<?php echo esc_url($settingsURL) ?>"
                                                            class="nav-tab <?php echo esc_attr($item['id'] === $activeModuleID ? 'nav-tab-active' : '') ?>"
                                                        ><span class="gato-sidenav__label"><?php echo esc_html($item['name']) ?></span><span class="gato-dot" hidden></span></a>
                                                    <?php
                                                }
                                                ?>
                                            </h2>
                                            <div class="nav-tab-content">
                                    <?php endif; ?>
                                                <?php
                                                if (!$printsSingleFormForAllCategories) {
                                                    $this->printOptionsFormStart(
                                                        $settingsCategoryResolver,
                                                        $settingsCategory,
                                                        $optionsFormName,
                                                        $optionsFormOrigin,
                                                        $time,
                                                    );
                                                } else {
                                                    /**
                                                     * The category is submitted also when none of its
                                                     * fields is (eg: all its switches turned off), so its
                                                     * values are not taken as missing, and replaced with
                                                     * the defaults
                                                     */
                                                    ?>
                                                    <input type="hidden" name="<?php echo esc_attr($optionsFormName)?>[<?php echo esc_attr(self::FORM_FIELD_LAST_SAVED_TIMESTAMP) ?>]" value="<?php echo esc_attr((string)$time) ?>">
                                                    <?php
                                                }
                                                ?>
                                                    <!-- Panels -->
                                                    <?php
                                                    $intro = $settingsCategoryResolver->getIntro($settingsCategory);
                                                    if ($intro !== null) {
                                                        $this->printCategoryIntro($settingsCategoryID, $intro);
                                                    }
                                                    /**
                                                     * All the sections are printed one below the other, and the
                                                     * tabs down the side scroll to them (see settings.js)
                                                     */
                                                    foreach ($categorySettingsItems as $item) {
                                                        /** @var array<array<string,mixed>> */
                                                        $moduleItemSettings = $item['settings'];
                                                        $sectionHelp = SettingsControlHelpers::getSectionHelp($moduleItemSettings);
                                                        $sectionHelpID = 'help-' . $item['id'];
                                                        ?>
                                                        <div id="<?php echo esc_attr($item['id']) ?>" class="gatographql-settings-section gato-module" data-module-name="<?php echo esc_attr($item['name']) ?>"<?php echo $item['id'] === $activeModule ? ' data-module-requested="1"' : '' ?>>
                                                            <h2 class="gato-module__title">
                                                                <span><?php echo esc_html($item['name']) ?></span>
                                                                <?php if ($sectionHelp !== '') : ?>
                                                                    <?php $this->printHelpToggle($sectionHelpID) ?>
                                                                <?php endif; ?>
                                                            </h2>
                                                            <?php if ($sectionHelp !== '') : ?>
                                                                <div class="gato-module__help">
                                                                    <?php $this->printHelpPanel($sectionHelpID, $sectionHelp, null) ?>
                                                                </div>
                                                            <?php endif; ?>
                                                            <?php $this->printModuleSettings($optionsFormName, $item) ?>
                                                        </div>
                                                        <?php
                                                    }
                                                    if (!$printsSingleFormForAllCategories && $settingsCategoryResolver->addOptionsFormSubmitButton($settingsCategory)) {
                                                        submit_button(
                                                            $this->getSubmitButtonLabel($settingsCategoryResolver, $settingsCategory),
                                                            'primary gato-tab-submit',
                                                            $this->getSubmitButtonName($settingsCategoryResolver, $settingsCategory)
                                                        );
                                                    }
                                                    ?>
                                                <?php if (!$printsSingleFormForAllCategories) : ?>
                                                    </form>
                                                <?php endif; ?>
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
                <?php
                if ($printSaveAllBar) {
                    $this->printSaveAllBar();
                }
                $this->printPageFooter();
                ?>
            </div>
        <?php
    }

    /**
     * The form posted to options.php to save the settings of a single
     * category: what a page without JavaScript uses, and the categories
     * that are not saved with the others (eg: Plugin Management)
     */
    protected function printOptionsFormStart(
        SettingsCategoryResolverInterface $settingsCategoryResolver,
        string $settingsCategory,
        string $optionsFormName,
        string $optionsFormOrigin,
        int $time,
    ): void {
        ?>
        <form
            method="post"
            action="options.php"
            class="gato-settings-form"
            data-options-form-origin="<?php echo esc_attr($optionsFormOrigin) ?>"
            data-options-form-name="<?php echo esc_attr($optionsFormName) ?>"
            data-category-id="<?php echo esc_attr($settingsCategoryResolver->getID($settingsCategory)) ?>"
            data-category-name="<?php echo esc_attr($settingsCategoryResolver->getName($settingsCategory)) ?>"
            data-save-with-others="<?php echo $settingsCategoryResolver->canSaveWithOtherCategories($settingsCategory) ? '1' : '0' ?>"
        >
            <!-- Artificial input as flag that the form belongs to this plugin -->
            <input type="hidden" name="<?php echo esc_attr(self::FORM_ORIGIN) ?>" value="<?php echo esc_attr($optionsFormOrigin) ?>" />
            <!--
                Artificial input to trigger the update of the form always, as to always purge the container/operational cache
                (eg: to include 3rd party extensions in the service container, or new Gutenberg blocks)
                This is needed because "If the new and old values are the same, no need to update."
                which makes "update_option_{$option}" not be triggered when there are no changes
                @see wp-includes/option.php
            -->
            <input type="hidden" name="<?php echo esc_attr($optionsFormName)?>[<?php echo esc_attr(self::FORM_FIELD_LAST_SAVED_TIMESTAMP) ?>]" value="<?php echo esc_attr((string)$time) ?>">

            <?php /** Support for XDebug */ ?>
            <?php RequestHelpers::maybePrintXDebugInputsInForm() ?>

            <?php settings_fields($optionsFormOrigin); ?>
        <?php
    }

    /**
     * The bar to save the changes of all tabs at once. It is shown by
     * JavaScript, which hides the per-tab Save buttons instead.
     */
    protected function printSaveAllBar(): void
    {
        ?>
        <div class="gato-savebar" data-js-only hidden>
            <span class="gato-savebar__status" aria-live="polite"><?php esc_html_e('No unsaved changes', 'gatographql') ?></span>
            <span class="gato-savebar__actions">
                <button type="button" class="button gato-savebar__discard" disabled><?php esc_html_e('Discard', 'gatographql') ?></button>
                <button type="button" class="button button-primary gato-savebar__save" disabled><?php esc_html_e('Save all changes', 'gatographql') ?></button>
            </span>
        </div>
        <?php
    }

    /**
     * Content at the bottom of the page
     */
    protected function printPageFooter(): void
    {
    }

    /**
     * PHP drops the fields past this number, without an error
     */
    protected function getMaxInputVars(): int
    {
        $maxInputVars = (int) ini_get('max_input_vars');
        return $maxInputVars > 0 ? $maxInputVars : 1000;
    }

    /**
     * Print the settings of the module, in groups
     *
     * @param array<string,mixed> $item
     */
    protected function printModuleSettings(string $optionsFormName, array $item): void
    {
        /** @var string */
        $module = $item['module'];
        /** @var array<array<string,mixed>> */
        $itemSettings = $item['settings'];
        foreach (SettingsControlHelpers::getGroups($itemSettings) as $group) {
            $heading = $group['heading'];
            ?>
            <section class="gato-card">
                <?php if ($heading !== null) : ?>
                    <?php
                    /** @var string */
                    $headingTitle = $heading[Properties::TITLE] ?? '';
                    /** @var string */
                    $headingDescription = $heading[Properties::DESCRIPTION] ?? '';
                    $headingDescriptionHelp = SettingsControlHelpers::splitDescriptionHelp($headingDescription);
                    $headingDescription = $headingDescriptionHelp['description'];
                    /** @var string */
                    $headingHelp = $heading[Properties::HELP] ?? '';
                    $headingHelp = trim(implode('<br/><br/>', array_filter([$headingHelp, $headingDescriptionHelp['help']])));
                    /** @var string */
                    $headingName = $heading[Properties::NAME] ?? '';
                    $headingHelpID = 'help-' . $headingName;
                    ?>
                    <div class="gato-card__head">
                        <span class="gato-card__heading">
                            <span class="gato-card__title"><?php echo esc_html($this->getGroupTitle($headingTitle)) ?></span>
                            <?php if ($headingHelp !== '') : ?>
                                <?php $this->printHelpToggle($headingHelpID) ?>
                            <?php endif; ?>
                        </span>
                    </div>
                    <?php if (trim($headingDescription) !== '') : ?>
                        <div class="gato-card__description"><?php echo $headingDescription; ?></div>
                    <?php endif; ?>
                    <?php if ($headingHelp !== '') : ?>
                        <div class="gato-card__help">
                            <?php $this->printHelpPanel($headingHelpID, $headingHelp, null) ?>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
                <?php
                foreach ($group['settings'] as $itemSetting) {
                    $this->printSetting($optionsFormName, $module, $itemSetting, $itemSettings);
                }
                ?>
            </section>
            <?php
        }
    }

    /**
     * Headings used to be underlined, and end with a colon
     */
    protected function getGroupTitle(string $title): string
    {
        $title = trim(wp_strip_all_tags($title));
        return rtrim($title, ': ');
    }

    /**
     * Print a setting: its title, description and help on one side,
     * and its control on the other
     *
     * @param array<string,mixed> $itemSetting
     * @param array<array<string,mixed>> $moduleItemSettings All settings of the module, to find those this one depends on
     */
    protected function printSetting(string $optionsFormName, string $module, array $itemSetting, array $moduleItemSettings): void
    {
        $control = SettingsControlHelpers::getControl($itemSetting);
        /** @var string */
        $name = $itemSetting[Properties::NAME];
        /** @var string */
        $title = SettingsControlHelpers::getTitleWithoutUnit($itemSetting[Properties::TITLE] ?? '', $itemSetting[Properties::UNIT] ?? null);
        /** @var string */
        $description = $itemSetting[Properties::DESCRIPTION] ?? '';
        /** @var string */
        $help = $itemSetting[Properties::HELP] ?? '';
        if ($control !== Properties::CONTROL_CONTENT) {
            $descriptionHelp = SettingsControlHelpers::splitDescriptionHelp($description);
            $description = $descriptionHelp['description'];
            $help = trim(implode('<br/><br/>', array_filter([$help, $descriptionHelp['help']])));
        }

        /**
         * Content that shows nothing (eg: only a script) gets no row
         */
        if (
            $control === Properties::CONTROL_CONTENT
            && trim($title) === ''
            && trim(wp_strip_all_tags($description)) === ''
            && preg_match('#<(input|button|select|textarea|table|img|a)\b#i', $description) !== 1
        ) {
            echo $description;
            return;
        }
        /** @var string|null */
        $docsURL = $itemSetting[Properties::DOCS_URL] ?? null;
        /** @var string */
        $cssStyle = $itemSetting[Properties::CSS_STYLE] ?? '';
        /** @var string[] */
        $searchKeywords = $itemSetting[Properties::SEARCH_KEYWORDS] ?? [];
        /** @var string|null */
        $option = $itemSetting[Properties::INPUT] ?? null;

        $dependsOn = $this->getDependsOn($optionsFormName, $module, $itemSetting, $moduleItemSettings);

        $isWide = in_array($control, [
            Properties::CONTROL_CONTENT,
            Properties::CONTROL_GRID,
            Properties::CONTROL_CHECKLIST,
            Properties::CONTROL_TAGS,
            Properties::CONTROL_TEXTAREA,
            Properties::CONTROL_CHIPS,
        ]) || SettingsControlHelpers::isFullWidthInput($itemSetting);
        $searchText = $option === null ? '' : $this->getSearchText([$title, $description, $help, implode(' ', $searchKeywords)]);
        $settingClasses = [
            'gatographql-settings-item',
            'gato-setting',
            'gato-setting--' . $control,
        ];
        if ($isWide) {
            $settingClasses[] = 'gato-setting--wide';
        }
        $hasTitle = trim($title) !== '';
        $titleID = 'title-' . $name;
        $helpID = 'help-' . $name;
        $hasHelp = trim(wp_strip_all_tags($help)) !== '';
        ?>
        <div
            id="section-<?php echo esc_attr($name) ?>"
            class="<?php echo esc_attr(implode(' ', $settingClasses)) ?>"
            <?php if ($searchText !== '') : ?>
                data-search="<?php echo esc_attr($searchText) ?>"
                data-title="<?php echo esc_attr(trim(wp_strip_all_tags($title))) ?>"
            <?php endif; ?>
            <?php if ($dependsOn !== null) : ?>
                data-depends-on="<?php echo esc_attr((string) json_encode($dependsOn['values'])) ?>"
            <?php endif; ?>
            <?php if (!empty($cssStyle)) : ?>
                style="<?php echo esc_attr($cssStyle) ?>"
            <?php endif; ?>
        >
            <?php if ($hasTitle || trim($description) !== '' || $help !== '' || $dependsOn !== null) : ?>
                <div class="gato-setting__main">
                    <?php if ($hasTitle || $hasHelp || $docsURL !== null) : ?>
                        <div class="gato-setting__title">
                            <?php if ($hasTitle) : ?>
                                <?php if ($this->isLabelableControl($control)) : ?>
                                    <label id="<?php echo esc_attr($titleID) ?>" for="<?php echo esc_attr($name) ?>"><?php echo $title ?></label>
                                <?php else : ?>
                                    <span id="<?php echo esc_attr($titleID) ?>"><?php echo $title ?></span>
                                <?php endif; ?>
                            <?php endif; ?>
                            <?php if ($hasHelp) : ?>
                                <?php $this->printHelpToggle($helpID) ?>
                            <?php elseif ($docsURL !== null) : ?>
                                <?php $this->printDocsLink($docsURL) ?>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    <?php if (trim($description) !== '' && $control !== Properties::CONTROL_CONTENT) : ?>
                        <div class="gato-setting__description"><?php echo $description ?></div>
                    <?php endif; ?>
                    <?php if ($dependsOn !== null) : ?>
                        <div class="gato-setting__dependency"><?php echo esc_html($dependsOn['message']) ?></div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            <div class="gato-setting__control">
                <?php
                $this->printControl($control, $optionsFormName, $module, $itemSetting, $hasTitle ? $titleID : null);
                ?>
            </div>
            <?php if ($hasHelp) : ?>
                <?php
                /**
                 * On a line of its own, across the row, so that opening
                 * it does not move the control beside the title
                 */
                $this->printHelpPanel($helpID, $help, $docsURL);
                ?>
            <?php endif; ?>
        </div>
        <?php
    }

    /**
     * What applies to all the sections of a tab, once above them
     *
     * @param array{title:string,description:string,help?:string} $intro
     */
    protected function printCategoryIntro(string $settingsCategoryID, array $intro): void
    {
        $help = $intro['help'] ?? '';
        $helpID = 'help-' . $settingsCategoryID;
        ?>
        <section class="gato-card gato-tab-intro">
            <div class="gato-setting">
                <div class="gato-setting__main">
                    <div class="gato-setting__title">
                        <span><?php echo esc_html($intro['title']) ?></span>
                        <?php if ($help !== '') : ?>
                            <?php $this->printHelpToggle($helpID) ?>
                        <?php endif; ?>
                    </div>
                    <div class="gato-setting__description"><?php echo $intro['description'] ?></div>
                    <?php if ($help !== '') : ?>
                        <?php $this->printHelpPanel($helpID, $help, null) ?>
                    <?php endif; ?>
                </div>
            </div>
        </section>
        <?php
    }

    /**
     * The "i" button beside a title, which shows or hides its help
     * (see gato-ui.js)
     */
    protected function printHelpToggle(string $helpID): void
    {
        ?>
        <button type="button" class="gato-help-toggle" aria-expanded="false" aria-controls="<?php echo esc_attr($helpID) ?>" data-js-only hidden>
            <span class="screen-reader-text"><?php esc_html_e('Help', 'gatographql') ?></span>
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="10"></circle><path d="M12 16v-5"></path><circle cx="12" cy="8" r=".6" fill="currentColor"></circle></svg>
        </button>
        <?php
    }

    /**
     * The help of a setting, shown by its "i" button. Without
     * JavaScript, it is always shown.
     */
    protected function printHelpPanel(string $helpID, string $help, ?string $docsURL): void
    {
        ?>
        <div id="<?php echo esc_attr($helpID) ?>" class="gato-help" data-help-panel>
            <div class="gato-help__content"><?php echo $help ?></div>
            <?php if ($docsURL !== null) : ?>
                <?php $this->printDocsLink($docsURL) ?>
            <?php endif; ?>
        </div>
        <?php
    }

    protected function printDocsLink(string $docsURL): void
    {
        ?>
        <a href="<?php echo esc_url($docsURL) ?>" class="gato-setting__docs" target="_blank" rel="noopener noreferrer"><?php esc_html_e('Docs', 'gatographql') ?><span aria-hidden="true"> ↗</span><span class="screen-reader-text"><?php esc_html_e('(opens in a new tab)', 'gatographql') ?></span></a>
        <?php
    }

    /**
     * Controls with a single field, which the title can label
     */
    protected function isLabelableControl(string $control): bool
    {
        return in_array($control, [
            Properties::CONTROL_SWITCH,
            Properties::CONTROL_SELECT,
            Properties::CONTROL_INPUT,
            Properties::CONTROL_TEXTAREA,
            Properties::CONTROL_TAGS,
        ]);
    }

    /**
     * @param string[] $texts
     */
    protected function getSearchText(array $texts): string
    {
        $text = implode(' ', array_map(
            fn (string $text) => wp_strip_all_tags($text),
            $texts
        ));
        $text = (string) preg_replace('/\s+/', ' ', $text);
        return strtolower(trim($text));
    }

    /**
     * @param array<string,mixed> $itemSetting
     */
    protected function printControl(string $control, string $optionsFormName, string $module, array $itemSetting, ?string $titleID): void
    {
        match ($control) {
            Properties::CONTROL_SWITCH => $this->printCheckboxField($optionsFormName, $module, $itemSetting),
            Properties::CONTROL_SEGMENTED => $this->printSegmentedField($optionsFormName, $module, $itemSetting, $titleID),
            Properties::CONTROL_SELECT => $this->printSelectField($optionsFormName, $module, $itemSetting),
            Properties::CONTROL_CHECKLIST => $this->printChecklistField($optionsFormName, $module, $itemSetting, $titleID),
            Properties::CONTROL_TAGS => $this->printTextareaField($optionsFormName, $module, $itemSetting),
            Properties::CONTROL_TEXTAREA => ($itemSetting[Properties::TYPE] ?? null) === Properties::TYPE_ARRAY
                ? $this->printTextareaField($optionsFormName, $module, $itemSetting)
                : $this->printInputField($optionsFormName, $module, $itemSetting),
            Properties::CONTROL_GRID => $this->printMultiInputField($optionsFormName, $module, $itemSetting),
            Properties::CONTROL_CHIPS => $this->printMultiInputField($optionsFormName, $module, $itemSetting),
            Properties::CONTROL_CONTENT => $this->printLabelField($optionsFormName, $module, $itemSetting),
            default => $this->printInputField($optionsFormName, $module, $itemSetting),
        };
    }

    /**
     * The value for a key of a Property Array, or the default
     * value for an entry when it has none
     *
     * The default can differ per entry, given as [key => value]:
     * an entry added after the setting was saved (eg: the post type of
     * a plugin activated later) then shows the value the plugin uses
     * for it, and not the one shared by all entries.
     *
     * @param array<string,mixed> $itemSetting
     */
    protected function getPropertyArrayEntryValue(mixed $value, string|int $key, array $itemSetting): mixed
    {
        if (is_array($value) && isset($value[$key])) {
            return $value[$key];
        }
        if (($itemSetting[Properties::SUBTYPE] ?? null) === Properties::TYPE_BOOL && ($itemSetting[Properties::POSSIBLE_VALUES] ?? []) === []) {
            return false;
        }
        $defaultValue = $itemSetting[Properties::DEFAULT_VALUE] ?? null;
        if (is_array($defaultValue)) {
            return $defaultValue[$key] ?? null;
        }
        return $defaultValue;
    }

    /**
     * The settings this one depends on: the names of their fields, with
     * the values for which this one applies, and the message to show
     * otherwise.
     *
     * @param array<string,mixed> $itemSetting
     * @param array<array<string,mixed>> $moduleItemSettings
     * @return array{values:array<string,string[]>,message:string}|null
     */
    protected function getDependsOn(string $optionsFormName, string $module, array $itemSetting, array $moduleItemSettings): ?array
    {
        /** @var array<string,mixed> */
        $dependsOn = $itemSetting[Properties::DEPENDS_ON] ?? [];
        if ($dependsOn === []) {
            return null;
        }
        $moduleResolver = $this->getModuleRegistry()->getModuleResolver($module);
        $values = [];
        $messages = [];
        foreach ($dependsOn as $dependsOnOption => $dependsOnValue) {
            $dependsOnValues = is_array($dependsOnValue) ? array_values($dependsOnValue) : [$dependsOnValue];
            $fieldName = sprintf(
                '%s[%s]',
                $optionsFormName,
                $moduleResolver->getSettingOptionName($module, $dependsOnOption)
            );
            $values[$fieldName] = array_map(
                fn (mixed $value) => is_bool($value) ? ($value ? '1' : '') : (is_scalar($value) ? (string) $value : ''),
                $dependsOnValues
            );

            $dependsOnItemSetting = null;
            foreach ($moduleItemSettings as $moduleItemSetting) {
                if (($moduleItemSetting[Properties::INPUT] ?? null) === $dependsOnOption) {
                    $dependsOnItemSetting = $moduleItemSetting;
                    break;
                }
            }
            /** @var string */
            $dependsOnTitle = $dependsOnItemSetting[Properties::TITLE] ?? $dependsOnOption;
            $dependsOnTitle = trim(wp_strip_all_tags($dependsOnTitle));
            if (($dependsOnItemSetting[Properties::TYPE] ?? null) === Properties::TYPE_BOOL) {
                $messages[] = $dependsOnValues === [false]
                    ? sprintf(__('Turn off “%s” to use this.', 'gatographql'), $dependsOnTitle)
                    : sprintf(__('Turn on “%s” to use this.', 'gatographql'), $dependsOnTitle);
                continue;
            }
            /** @var array<string|int,string> */
            $possibleValues = $dependsOnItemSetting[Properties::POSSIBLE_VALUES] ?? [];
            $messages[] = sprintf(
                __('Set “%1$s” to “%2$s” to use this.', 'gatographql'),
                $dependsOnTitle,
                implode(
                    __('” or “', 'gatographql'),
                    array_map(
                        fn (mixed $value) => trim(wp_strip_all_tags((string) ($possibleValues[is_bool($value) ? (int) $value : (string) $value] ?? $value))),
                        $dependsOnValues
                    )
                )
            );
        }
        return [
            'values' => $values,
            'message' => implode(' ', $messages),
        ];
    }

    protected function getPageTitle(): string
    {
        $pageTitle = $this->getMenuPageTitle();
        if ($this->addPluginNameToPageTitle()) {
            $pageTitle = sprintf(
                __('%s — %s', 'gatographql'),
                PluginApp::getMainPlugin()->getPluginName(),
                $pageTitle
            );
        }
        return $pageTitle;
    }

    protected function addPluginNameToPageTitle(): bool
    {
        return false;
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
        $uiHandle = $this->enqueueAdminUIAssets();
        $this->enqueueMainPluginAssetScript(
            'gatographql-settings',
            'assets/js/settings.js',
            array('jquery', 'gatographql-tabpanel', $uiHandle)
        );
        wp_localize_script(
            'gatographql-settings',
            'gatoSettingsL10n',
            [
                'more' => __('More (%s)', 'gatographql'),
                'noChanges' => __('No unsaved changes', 'gatographql'),
                'oneChange' => __('1 unsaved change:', 'gatographql'),
                'manyChanges' => __('%s unsaved changes:', 'gatographql'),
                'saving' => __('Saving…', 'gatographql'),
                'tooManyFields' => __('The "%s" tab has more fields than the server accepts in one request, so it can\'t be saved. To save it, raise the PHP setting max_input_vars.', 'gatographql'),
                'saveFailed' => __('The changes could not be saved: %s', 'gatographql'),
                'invalidJSON' => __('This is not valid JSON: %s', 'gatographql'),
                'oneInvalidJSON' => __('Fix the JSON in 1 field to save the changes:', 'gatographql'),
                'manyInvalidJSON' => __('Fix the JSON in %s fields to save the changes:', 'gatographql'),
                'filter' => __('Filter', 'gatographql'),
                'selectedCount' => __('%1$s of %2$s selected', 'gatographql'),
                'setAllTo' => __('Set all to:', 'gatographql'),
                'addEntry' => __('Add an entry, then press Enter', 'gatographql'),
                'removeEntry' => __('Remove %s', 'gatographql'),
                'noResults' => __('No settings found', 'gatographql'),
                'resultsCount' => __('%s settings found', 'gatographql'),
                'unsavedChanges' => __('Unsaved changes', 'gatographql'),
            ]
        );
        $this->enqueueMainPluginAssetStyle(
            'gatographql-settings',
            'assets/css/settings.css',
            [$uiHandle]
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
     * Display a checkbox field, as a switch
     *
     * @param array<string,mixed> $itemSetting
     */
    protected function printCheckboxField(string $optionsFormName, string $module, array $itemSetting): void
    {
        /** @var string */
        $name = $itemSetting[Properties::NAME];
        /** @var string */
        $input = $itemSetting[Properties::INPUT];
        $value = $this->getOptionValue($module, $input);
        ?>
            <label class="gato-switch">
                <input type="checkbox" class="gato-switch__input" name="<?php echo esc_attr($optionsFormName . '[' . $name . ']'); ?>" id="<?php echo esc_attr($name); ?>" value="1" <?php checked(1, $value); ?> />
                <span class="gato-switch__track" aria-hidden="true"><span class="gato-switch__knob"></span></span>
                <span class="gato-switch__state" aria-hidden="true"><span class="gato-switch__on"><?php esc_html_e('On', 'gatographql') ?></span><span class="gato-switch__off"><?php esc_html_e('Off', 'gatographql') ?></span></span>
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
        /** @var string */
        $name = $itemSetting[Properties::NAME];
        /** @var string */
        $input = $itemSetting[Properties::INPUT];
        $value = $this->getOptionValue($module, $input);
        $isNumber = isset($itemSetting[Properties::TYPE]) && $itemSetting[Properties::TYPE] === Properties::TYPE_INT;
        $minNumber = null;
        $maxNumber = null;
        if ($isNumber) {
            $minNumber = $itemSetting[Properties::MIN_NUMBER] ?? null;
            $maxNumber = $itemSetting[Properties::MAX_NUMBER] ?? null;
        }
        $isPassword = ($itemSetting[Properties::TYPE] ?? null) === Properties::TYPE_STRING && ($itemSetting[Properties::SUBTYPE] ?? null) === Properties::TYPE_PASSWORD;
        $useTextarea = ($itemSetting[Properties::USE_TEXTAREA] ?? false) || ($itemSetting[Properties::CONTROL] ?? null) === Properties::CONTROL_TEXTAREA;
        /** @var string|null */
        $placeholder = $itemSetting[Properties::PLACEHOLDER] ?? null;
        /** @var string|null */
        $unit = $itemSetting[Properties::UNIT] ?? null;
        $fieldName = $optionsFormName . '[' . $name . ']';
        if ($useTextarea) {
            $text = is_scalar($value) ? (string) $value : '';
            $isJSON = $itemSetting[Properties::JSON] ?? false;
            if ($isJSON) {
                $text = SettingsControlHelpers::formatJSON($text);
            }
            ?>
            <textarea
                name="<?php echo esc_attr($fieldName); ?>"
                id="<?php echo esc_attr($name); ?>"
                class="gato-textarea<?php echo $isJSON ? ' gato-textarea--json' : '' ?>"
                rows="<?php echo esc_attr((string) ($isJSON ? SettingsControlHelpers::getJSONTextareaRows($text) : 10)) ?>"
                cols="50"
                <?php if ($isJSON) : ?>
                    data-json
                    spellcheck="false"
                    aria-describedby="<?php echo esc_attr($name . '-json-error') ?>"
                <?php endif; ?>
                <?php if ($placeholder !== null) : ?>
                    placeholder="<?php echo esc_attr($placeholder) ?>"
                <?php endif; ?>
            ><?php echo esc_textarea($text) ?></textarea>
            <?php if ($isJSON) : ?>
                <span id="<?php echo esc_attr($name . '-json-error') ?>" class="gato-field-error" role="status" hidden></span>
            <?php endif; ?>
            <?php
            return;
        }
        ?>
            <span class="gato-input-group">
                <?php if ($unit !== null) : ?>
                    <span class="gato-affix">
                <?php endif; ?>
                <?php if ($isPassword) : ?>
                    <span class="gato-password">
                <?php endif; ?>
                <input
                    name="<?php echo esc_attr($fieldName); ?>"
                    id="<?php echo esc_attr($name); ?>"
                    value="<?php echo esc_attr(is_scalar($value) ? (string) $value : ''); ?>"
                    class="regular-text gato-input<?php echo $isNumber ? ' gato-input--number' : '' ?>"
                    <?php if ($placeholder !== null) : ?>
                        placeholder="<?php echo esc_attr($placeholder) ?>"
                    <?php endif; ?>
                    <?php if ($isNumber) { ?>
                        type="number"
                        step="1"
                        <?php if ($minNumber !== null) { ?>
                            min="<?php echo esc_attr((string) $minNumber) ?>"
                        <?php } ?>
                        <?php if ($maxNumber !== null) { ?>
                            max="<?php echo esc_attr((string) $maxNumber) ?>"
                        <?php } ?>
                    <?php } elseif ($isPassword) { ?>
                        type="password"
                        autocomplete="off"
                    <?php } else { ?>
                        type="text"
                    <?php } ?>
                   
                />
                <?php if ($isPassword) : ?>
                        <?php $this->printPasswordToggle($name); ?>
                    </span>
                <?php endif; ?>
                <?php if ($unit !== null) : ?>
                        <span class="gato-affix__unit"><?php echo esc_html($unit) ?></span>
                    </span>
                <?php endif; ?>
            </span>
        <?php
    }

    /**
     * Display a "Property Array" field: a grid with an entry per key
     *
     * @param array<string,mixed> $itemSetting
     */
    protected function printMultiInputField(string $optionsFormName, string $module, array $itemSetting): void
    {
        /** @var string */
        $name = $itemSetting[Properties::NAME];
        /** @var string */
        $input = $itemSetting[Properties::INPUT];
        $value = $this->getOptionValue($module, $input);
        // If it is multiple, $value is an array.
        // To simplify, deal always with arrays
        if (!is_array($value)) {
            $value = $value === null ? [] : [$value];
        }
        /** @var array<string|int,string> */
        $keyLabels = $itemSetting[Properties::KEY_LABELS] ?? [];
        /** @var array<string|int,string> */
        $possibleValues = $itemSetting[Properties::POSSIBLE_VALUES] ?? [];
        $isMultiple = $itemSetting[Properties::IS_MULTIPLE] ?? false;
        $subtype = $itemSetting[Properties::SUBTYPE] ?? null;
        $isMultipleBool = empty($possibleValues) && $subtype === Properties::TYPE_BOOL;
        $isPassword = $subtype === Properties::TYPE_PASSWORD;
        $entryControl = $isMultipleBool ? Properties::CONTROL_CHIPS : SettingsControlHelpers::getGridEntryControl($itemSetting);

        if ($isMultipleBool) {
            ?>
            <div class="gato-chips" role="group">
                <?php foreach ($keyLabels as $key => $label) : ?>
                    <?php $id = $name . '_' . $key; ?>
                    <label class="gato-chip" for="<?php echo esc_attr($id) ?>">
                        <input type="checkbox" name="<?php echo esc_attr($optionsFormName . '[' . $name . '][' . $key . ']'); ?>" id="<?php echo esc_attr($id); ?>" value="1" <?php checked(true, (bool) $this->getPropertyArrayEntryValue($value, $key, $itemSetting)); ?> />
                        <span><?php echo esc_html($label); ?></span>
                    </label>
                <?php endforeach; ?>
            </div>
            <?php
            return;
        }

        $isBig = count($keyLabels) > self::GRID_FILTER_MIN_ENTRIES;
        $canSetAll = $possibleValues !== [] && !$isMultiple;
        ?>
        <div class="gato-grid-field" data-grid>
            <?php if ($isBig) : ?>
                <div class="gato-grid-field__bar" data-js-only hidden>
                    <input type="search" class="gato-filter" data-grid-filter />
                </div>
            <?php endif; ?>
            <?php if ($canSetAll) : ?>
                <div class="gato-setall" data-js-only hidden>
                    <span class="gato-setall__label"><?php esc_html_e('Set all to:', 'gatographql') ?></span>
                    <?php
                    $setAllLinks = [];
                    foreach ($possibleValues as $optionValue => $optionLabel) {
                        $setAllLinks[] = sprintf(
                            '<a href="#" data-set-all="%s">%s</a>',
                            esc_attr((string) $optionValue),
                            esc_html($optionLabel)
                        );
                    }
                    echo implode('<span class="gato-setall__separator" aria-hidden="true">|</span>', $setAllLinks);
                    ?>
                </div>
            <?php endif; ?>
            <div class="gato-grid<?php echo $entryControl === Properties::CONTROL_INPUT ? ' gato-grid--single' : '' ?>">
                <?php
                /** @var array<string|int,array{label:string,tone?:string,detail?:string,detailTone?:string}> */
                $keyStatuses = $itemSetting[Properties::KEY_STATUSES] ?? [];
                /** @var array<string|int,array<string,string>> */
                $keyActions = $itemSetting[Properties::KEY_ACTIONS] ?? [];
                /** @var string|null */
                $keyActionsField = $itemSetting[Properties::KEY_ACTIONS_FIELD] ?? null;
                /** @var array<string|int,array<string,array<string,string>>> */
                $keyEditActions = $itemSetting[Properties::KEY_EDIT_ACTIONS] ?? [];
                foreach ($keyLabels as $key => $label) {
                    $keyStatus = $keyStatuses[$key] ?? null;
                    $entryActions = $keyActionsField === null ? [] : ($keyActions[$key] ?? []);
                    $entryEditActions = $keyActionsField === null ? [] : ($keyEditActions[$key] ?? []);
                    $id = $name . '_' . $key;
                    $entryName = $optionsFormName . '[' . $name . '][' . $key . ']';
                    $entryValue = $this->getPropertyArrayEntryValue($value, $key, $itemSetting);
                    $showKey = strtolower(trim((string) $key)) !== strtolower(trim($label));
                    $labelID = 'label-' . $id;
                    ?>
                    <div class="gato-grid__row" data-search="<?php echo esc_attr(strtolower($label . ' ' . $key)) ?>">
                        <span class="gato-grid__label">
                            <?php if ($entryControl === Properties::CONTROL_SEGMENTED) : ?>
                                <span id="<?php echo esc_attr($labelID) ?>" class="gato-grid__label-text"><?php echo esc_html($label); ?></span>
                            <?php else : ?>
                                <label id="<?php echo esc_attr($labelID) ?>" for="<?php echo esc_attr($id) ?>" class="gato-grid__label-text"><?php echo esc_html($label); ?></label>
                            <?php endif; ?>
                            <?php if ($showKey) : ?>
                                <code class="gato-grid__key"><?php echo esc_html((string) $key) ?></code>
                            <?php endif; ?>
                            <?php if ($keyStatus !== null) : ?>
                                <span class="gato-badge gato-badge--<?php echo esc_attr($keyStatus['tone'] ?? Properties::KEY_STATUS_TONE_NEUTRAL) ?> gato-grid__status"><?php echo esc_html($keyStatus['label']) ?></span>
                            <?php endif; ?>
                        </span>
                        <?php
                        if ($entryControl === Properties::CONTROL_INPUT) {
                            ?>
                            <?php if ($entryActions !== []) : ?>
                                <span class="gato-input-group">
                            <?php endif; ?>
                            <?php if ($isPassword) : ?>
                                <span class="gato-password">
                            <?php endif; ?>
                            <input
                                name="<?php echo esc_attr($entryName); ?>"
                                id="<?php echo esc_attr($id) ?>"
                                class="regular-text gato-input"
                                value="<?php echo esc_attr(is_scalar($entryValue) ? (string) $entryValue : '') ?>"
                                <?php if ($isPassword) : ?>
                                    type="password"
                                    autocomplete="off"
                                <?php else : ?>
                                    type="text"
                                <?php endif; ?>
                               
                            >
                            <?php if ($isPassword) : ?>
                                    <?php $this->printPasswordToggle($id); ?>
                                </span>
                            <?php endif; ?>
                            <?php if ($entryActions !== []) : ?>
                                <?php $this->printEntryActions((string) $keyActionsField, $entryActions, $label, $entryEditActions); ?>
                                </span>
                            <?php endif; ?>
                            <?php
                        } elseif ($entryControl === Properties::CONTROL_SEGMENTED) {
                            $this->printSegmentedRadios(
                                $entryName,
                                $id,
                                $possibleValues,
                                [$entryValue],
                                $labelID,
                                true
                            );
                        } else {
                            $currentValues = is_array($entryValue) ? $entryValue : [$entryValue];
                            ?>
                            <select
                                name="<?php echo esc_attr($entryName . ($isMultiple ? '[]' : '')); ?>"
                                id="<?php echo esc_attr($id) ?>"
                                class="gato-select gato-select--sm"
                                <?php if ($isMultiple) : ?>
                                    multiple="multiple"
                                    size="10"
                                <?php endif; ?>
                               
                            >
                            <?php $this->printSelectOptions($possibleValues, $currentValues, !$isMultiple); ?>
                            </select>
                            <?php
                        }
                        ?>
                        <?php if (($keyStatus['detail'] ?? '') !== '') : ?>
                            <span class="gato-grid__detail gato-grid__detail--<?php echo esc_attr($keyStatus['detailTone'] ?? $keyStatus['tone'] ?? Properties::KEY_STATUS_TONE_NEUTRAL) ?>"><?php echo esc_html($keyStatus['detail']) ?></span>
                        <?php endif; ?>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
        <?php
    }

    /**
     * Print the "eye" button, within a password field, that shows
     * or hides its value
     */
    protected function printPasswordToggle(string $inputID): void
    {
        ?>
        <button
            type="button"
            class="gato-password-toggle"
            aria-controls="<?php echo esc_attr($inputID) ?>"
            aria-pressed="false"
            aria-label="<?php esc_attr_e('Show', 'gatographql') ?>"
            title="<?php esc_attr_e('Show', 'gatographql') ?>"
            data-label-show="<?php esc_attr_e('Show', 'gatographql') ?>"
            data-label-hide="<?php esc_attr_e('Hide', 'gatographql') ?>"
            data-js-only
            hidden
        ><span class="dashicons dashicons-visibility" aria-hidden="true"></span></button>
        <?php
    }

    /**
     * The buttons beside an entry, which submit the form with their value
     * under that field: one shows as a primary button (it is the step to
     * take, eg: "Activate"), more go in a "Manage" menu. Once the entry's
     * value is changed or cleared, the script swaps them for the button
     * of that edit, as the step to take.
     *
     * @param array<string,string> $actions value => label
     * @param array<string,array<string,string>> $editActions KEY_EDIT_CHANGED|KEY_EDIT_CLEARED => [value => label]
     */
    protected function printEntryActions(string $fieldName, array $actions, string $entryLabel, array $editActions = []): void
    {
        foreach ($editActions as $edit => $editAction) {
            foreach ($editAction as $actionValue => $actionLabel) {
                ?>
                <button type="submit" class="button button-primary gato-entry-action" name="<?php echo esc_attr($fieldName) ?>" value="<?php echo esc_attr($actionValue) ?>" data-entry-edit="<?php echo esc_attr($edit) ?>" hidden><?php echo esc_html($actionLabel) ?></button>
                <?php
            }
        }
        if (count($actions) === 1) {
            foreach ($actions as $actionValue => $actionLabel) {
                ?>
                <button type="submit" class="button button-primary gato-entry-action" name="<?php echo esc_attr($fieldName) ?>" value="<?php echo esc_attr($actionValue) ?>" data-entry-step><?php echo esc_html($actionLabel) ?></button>
                <?php
            }
            return;
        }
        ?>
        <details class="gato-entry-menu">
            <summary class="button gato-entry-action"><?php esc_html_e('Manage', 'gatographql') ?><span class="screen-reader-text"> <?php echo esc_html($entryLabel) ?></span><span class="gato-entry-menu__chevron" aria-hidden="true"></span></summary>
            <span class="gato-entry-menu__items">
                <?php foreach ($actions as $actionValue => $actionLabel) : ?>
                    <button type="submit" class="gato-entry-menu__item" name="<?php echo esc_attr($fieldName) ?>" value="<?php echo esc_attr($actionValue) ?>"><?php echo esc_html($actionLabel) ?></button>
                <?php endforeach; ?>
            </span>
        </details>
        <?php
    }

    /**
     * Print the options of a select, the current value selected. When
     * the value is none of them, the first option is selected, as the
     * browser does (and so submits it).
     *
     * @param array<string|int,string> $possibleValues
     * @param mixed[] $currentValues
     */
    protected function printSelectOptions(array $possibleValues, array $currentValues, bool $selectFirstIfNone): void
    {
        $hasSelected = false;
        foreach ($possibleValues as $optionValue => $optionLabel) {
            if (!SettingsControlHelpers::isSelectedValue($optionValue, $currentValues)) {
                continue;
            }
            $hasSelected = true;
            break;
        }
        $isFirst = true;
        foreach ($possibleValues as $optionValue => $optionLabel) {
            $isSelected = SettingsControlHelpers::isSelectedValue($optionValue, $currentValues)
                || ($isFirst && $selectFirstIfNone && !$hasSelected);
            $isFirst = false;
            ?>
            <option
                value="<?php echo esc_attr((string) $optionValue) ?>"
                <?php if ($isSelected) : ?>
                    selected="selected"
                <?php endif; ?>
            ><?php echo esc_html($optionLabel) ?></option>
            <?php
        }
    }

    /**
     * Radio buttons with the same name, drawn as a segmented control.
     * When the value is none of the options, the first one is checked,
     * so a value is always submitted, as with a select.
     *
     * @param array<string|int,string> $possibleValues
     * @param mixed[] $currentValues
     */
    protected function printSegmentedRadios(
        string $fieldName,
        string $id,
        array $possibleValues,
        array $currentValues,
        ?string $labelledByID,
        bool $isSmall,
    ): void {
        $hasChecked = false;
        foreach ($possibleValues as $optionValue => $optionLabel) {
            if (!SettingsControlHelpers::isSelectedValue($optionValue, $currentValues)) {
                continue;
            }
            $hasChecked = true;
            break;
        }
        ?>
        <span
            class="gato-seg<?php echo $isSmall ? ' gato-seg--sm' : '' ?>"
            role="radiogroup"
            id="<?php echo esc_attr($id) ?>"
            <?php if ($labelledByID !== null) : ?>
                aria-labelledby="<?php echo esc_attr($labelledByID) ?>"
            <?php endif; ?>
        >
            <?php
            $index = 0;
            foreach ($possibleValues as $optionValue => $optionLabel) {
                $isChecked = SettingsControlHelpers::isSelectedValue($optionValue, $currentValues)
                    || ($index === 0 && !$hasChecked);
                $radioID = $id . '_' . $index;
                $index++;
                ?>
                <label class="gato-seg__option" for="<?php echo esc_attr($radioID) ?>">
                    <input
                        type="radio"
                        name="<?php echo esc_attr($fieldName) ?>"
                        id="<?php echo esc_attr($radioID) ?>"
                        value="<?php echo esc_attr((string) $optionValue) ?>"
                        <?php checked(true, $isChecked) ?>
                    />
                    <span><?php echo esc_html($optionLabel) ?></span>
                </label>
                <?php
            }
            ?>
        </span>
        <?php
    }

    /**
     * Display the options as a segmented control
     *
     * @param array<string,mixed> $itemSetting
     */
    protected function printSegmentedField(string $optionsFormName, string $module, array $itemSetting, ?string $titleID): void
    {
        /** @var string */
        $name = $itemSetting[Properties::NAME];
        /** @var string */
        $input = $itemSetting[Properties::INPUT];
        $value = $this->getOptionValue($module, $input);
        /** @var array<string|int,string> */
        $possibleValues = $itemSetting[Properties::POSSIBLE_VALUES] ?? [];
        if ($itemSetting[Properties::SORT_VALUES] ?? false) {
            ksort($possibleValues);
        }
        $this->printSegmentedRadios(
            $optionsFormName . '[' . $name . ']',
            $name,
            $possibleValues,
            is_array($value) ? $value : [$value],
            $titleID,
            false
        );
    }

    /**
     * Display the options as a list of checkboxes, to select many
     *
     * @param array<string,mixed> $itemSetting
     */
    protected function printChecklistField(string $optionsFormName, string $module, array $itemSetting, ?string $titleID): void
    {
        /** @var string */
        $name = $itemSetting[Properties::NAME];
        /** @var string */
        $input = $itemSetting[Properties::INPUT];
        $value = $this->getOptionValue($module, $input);
        if (!is_array($value)) {
            $value = $value === null ? [] : [$value];
        }
        /** @var array<string|int,string> */
        $possibleValues = $itemSetting[Properties::POSSIBLE_VALUES] ?? [];
        if ($itemSetting[Properties::APPEND_VALUE_IF_NON_EXISTING] ?? false) {
            foreach ($value as $entry) {
                if (!is_string($entry) || isset($possibleValues[$entry])) {
                    continue;
                }
                $possibleValues[$entry] = $entry;
            }
        }
        if ($itemSetting[Properties::SORT_VALUES] ?? false) {
            ksort($possibleValues);
        }
        ?>
        <div
            class="gato-checklist"
            role="group"
            id="<?php echo esc_attr($name) ?>"
            <?php if ($titleID !== null) : ?>
                aria-labelledby="<?php echo esc_attr($titleID) ?>"
            <?php endif; ?>
        >
            <div class="gato-checklist__bar" data-js-only hidden>
                <?php if (count($possibleValues) > 8) : ?>
                    <input type="search" class="gato-filter" data-checklist-filter />
                <?php endif; ?>
                <span class="gato-checklist__count" aria-live="polite"></span>
            </div>
            <div class="gato-checklist__items">
                <?php
                $index = 0;
                foreach ($possibleValues as $optionValue => $optionLabel) {
                    $checkboxID = $name . '_' . $index;
                    $index++;
                    ?>
                    <label class="gato-checklist__item" for="<?php echo esc_attr($checkboxID) ?>" data-search="<?php echo esc_attr(strtolower($optionLabel . ' ' . $optionValue)) ?>">
                        <input
                            type="checkbox"
                            name="<?php echo esc_attr($optionsFormName . '[' . $name . '][]') ?>"
                            id="<?php echo esc_attr($checkboxID) ?>"
                            value="<?php echo esc_attr((string) $optionValue) ?>"
                            <?php checked(true, SettingsControlHelpers::isSelectedValue($optionValue, $value)) ?>
                           
                        />
                        <span><?php echo esc_html($optionLabel) ?></span>
                    </label>
                    <?php
                }
                ?>
            </div>
        </div>
        <?php
    }

    /**
     * Display a select field.
     *
     * @param array<string,mixed> $itemSetting
     */
    protected function printSelectField(string $optionsFormName, string $module, array $itemSetting): void
    {
        /** @var string */
        $name = $itemSetting[Properties::NAME];
        /** @var string */
        $input = $itemSetting[Properties::INPUT];
        $value = $this->getOptionValue($module, $input);
        // If it is multiple, $value is an array.
        // To simplify, deal always with arrays
        if (!is_array($value)) {
            $value = $value === null ? [] : [$value];
        }
        $isMultiple = $itemSetting[Properties::IS_MULTIPLE] ?? false;
        /** @var array<string|int,string> */
        $possibleValues = $itemSetting[Properties::POSSIBLE_VALUES] ?? [];
        $appendValueIfNonExisting = $itemSetting[Properties::APPEND_VALUE_IF_NON_EXISTING] ?? false;
        if ($appendValueIfNonExisting) {
            foreach ($value as $item) {
                if (!is_string($item) || isset($possibleValues[$item])) {
                    continue;
                }
                $possibleValues[$item] = $item;
            }
        }
        $sortValues = $itemSetting[Properties::SORT_VALUES] ?? false;
        if ($sortValues) {
            ksort($possibleValues);
        }
        ?>
            <select
                name="<?php echo esc_attr($optionsFormName . '[' . $name . ']' . ($isMultiple ? '[]' : '')); ?>"
                id="<?php echo esc_attr($name); ?>"
                class="regular-text gato-select"
                <?php if ($isMultiple) : ?>
                    multiple="multiple"
                    size="10"
                <?php endif; ?>
               
            >
            <?php $this->printSelectOptions($possibleValues, $value, !$isMultiple); ?>
            </select>
        <?php
    }

    /**
     * Display a textarea field, with an entry per line, which
     * JavaScript turns into a tag input
     *
     * @param array<string,mixed> $itemSetting
     */
    protected function printTextareaField(string $optionsFormName, string $module, array $itemSetting): void
    {
        /** @var string */
        $name = $itemSetting[Properties::NAME];
        /** @var string */
        $input = $itemSetting[Properties::INPUT];
        // This must be an array
        $value = $this->getOptionValue($module, $input);
        if (!is_array($value)) {
            $value = $value === null || $value === '' ? [] : [$value];
        }
        /** @var string|null */
        $placeholder = $itemSetting[Properties::PLACEHOLDER] ?? null;
        $isTags = SettingsControlHelpers::getControl($itemSetting) === Properties::CONTROL_TAGS;
        ?>
            <textarea
                name="<?php echo esc_attr($optionsFormName . '[' . $name . ']'); ?>"
                id="<?php echo esc_attr($name); ?>"
                class="gato-textarea<?php echo $isTags ? ' gato-tags__source' : '' ?>"
                rows="10"
                cols="50"
                <?php if ($isTags) : ?>
                    data-tags
                <?php endif; ?>
                <?php if ($placeholder !== null) : ?>
                    placeholder="<?php echo esc_attr($placeholder) ?>"
                <?php endif; ?>
            ><?php echo esc_textarea(implode("\n", array_map(fn (mixed $entry) => is_scalar($entry) ? (string) $entry : '', $value))) ?></textarea>
        <?php
    }

    /**
     * Get the name of the submit button for a settings category.
     *
     * WordPress names it `submit` unless told otherwise, which is a name
     * the screen the form posts to may well give a meaning of its own. A
     * page whose button does something other than save these settings can
     * give it a name that says so.
     */
    protected function getSubmitButtonName(SettingsCategoryResolverInterface $settingsCategoryResolver, string $settingsCategory): string
    {
        return 'submit';
    }

    /**
     * Get the submit button label for a settings category
     */
    protected function getSubmitButtonLabel(SettingsCategoryResolverInterface $settingsCategoryResolver, string $settingsCategory): string
    {
        return sprintf(
            __('Save All Changes (\'%s\' tab)', 'gatographql'),
            $settingsCategoryResolver->getName($settingsCategory)
        );
    }

    /**
     * Save the changes of several tabs in one request, as options.php
     * does for one: each tab is checked against its own nonce and
     * capability, then stored with `update_option`, which runs the
     * sanitizer and the hooks of the setting (to purge the container,
     * flush the rewrite rules...). The notices are kept for the page
     * the user is sent back to.
     *
     * A request that lost fields on its way (PHP drops those past
     * `max_input_vars`) stores nothing: saving a tab stores its whole
     * set of values, so a missing field would be stored as empty.
     *
     * Too many fields for one request are sent in several: all but the
     * last are partial, their notices kept until the last one.
     */
    public function saveAllChanges(): void
    {
        /**
         * As options.php, read the values from $_POST, slashed by WordPress
         */
        // phpcs:disable SlevomatCodingStandard.Variables.DisallowSuperGlobalVariable.DisallowedSuperGlobalVariable
        /** @var array<string,mixed> */
        $post = $_POST; // phpcs:ignore WordPress.Security.NonceVerification.Missing -- Each tab's nonce is verified in saveAllChangesInTabs()
        // phpcs:enable SlevomatCodingStandard.Variables.DisallowSuperGlobalVariable.DisallowedSuperGlobalVariable
        /** @var array<string,mixed> */
        $saveAll = $post[self::SAVE_ALL] ?? [];
        $isPartial = !empty($saveAll[self::SAVE_ALL_IS_PARTIAL]);
        $isFirst = !empty($saveAll[self::SAVE_ALL_IS_FIRST]);

        if (!current_user_can('manage_options')) {
            wp_die(esc_html__('Sorry, you are not allowed to manage options for this site.', 'gatographql'), 403);
        }

        $savedTabNames = [];
        $expectedFieldCount = (int) ($saveAll[self::SAVE_ALL_FIELD_COUNT] ?? 0);
        $receivedFieldCount = SettingsControlHelpers::countLeafValues($post);
        if ($expectedFieldCount !== $receivedFieldCount) {
            add_settings_error(
                'general',
                'gato_save_all_fields_missing',
                sprintf(
                    __('Nothing was saved: the server received %1$s of the %2$s fields sent. Try again, or save each tab on its own.', 'gatographql'),
                    $receivedFieldCount,
                    $expectedFieldCount
                ),
                'error'
            );
        } else {
            $savedTabNames = $this->saveAllChangesInTabs($post, $saveAll);
        }

        $transientName = $this->getSaveAllNoticesTransientName();
        $previousBatches = $isFirst ? [] : get_transient($transientName);
        if (!is_array($previousBatches)) {
            $previousBatches = [];
        }
        /**
         * The notices and the tabs saved of the batches sent before, in
         * this same save (a save left unfinished must not add its notices
         * to the next one)
         *
         * @var array{notices?:array<array<string,string>>,savedTabNames?:string[]} $previousBatches
         */
        $notices = array_merge($previousBatches['notices'] ?? [], get_settings_errors());
        $savedTabNames = array_merge($previousBatches['savedTabNames'] ?? [], $savedTabNames);
        if ($isPartial) {
            set_transient(
                $transientName,
                [
                    'notices' => $notices,
                    'savedTabNames' => $savedTabNames,
                ],
                300
            );
            wp_send_json([
                'notices' => $notices,
            ]);
        }
        delete_transient($transientName);

        /**
         * If some tab failed, in any batch, say which ones were saved
         */
        $hasErrors = array_filter($notices, static fn (array $notice): bool => ($notice['type'] ?? '') === 'error') !== [];
        if ($hasErrors && $savedTabNames !== []) {
            $notices[] = [
                'setting' => 'general',
                'code' => 'gato_save_all_saved_tabs',
                'message' => sprintf(
                    __('The changes in these tabs were saved: %s.', 'gatographql'),
                    esc_html(implode(__(', ', 'gatographql'), $savedTabNames))
                ),
                'type' => 'success',
            ];
        }

        /**
         * As options.php: a request that raised no message says so
         */
        if ($notices === []) {
            $notices[] = [
                'setting' => 'general',
                'code' => 'settings_updated',
                'message' => __('Settings saved.', 'gatographql'),
                'type' => 'success',
            ];
        }
        set_transient('settings_errors', $notices, 30);

        /** @var string */
        $redirect = wp_unslash((string) ($saveAll[self::SAVE_ALL_REDIRECT] ?? ''));
        $redirect = wp_validate_redirect($redirect, admin_url());
        wp_safe_redirect(add_query_arg('settings-updated', 'true', $redirect));
        exit;
    }

    /**
     * @param array<string,mixed> $post
     * @param array<string,mixed> $saveAll
     * @return string[] The names of the tabs saved
     */
    protected function saveAllChangesInTabs(array $post, array $saveAll): array
    {
        /**
         * Submitted by the browser, so not to be trusted
         *
         * @var mixed[]
         */
        $tabs = $saveAll[self::SAVE_ALL_TABS] ?? [];
        /** @var array<string,mixed> */
        $nonces = $saveAll[self::SAVE_ALL_NONCES] ?? [];

        $settingsCategoryResolvers = [];
        foreach ($this->getSettingsCategoryRegistry()->getSettingsCategorySettingsCategoryResolvers() as $settingsCategory => $settingsCategoryResolver) {
            if (!$settingsCategoryResolver->canSaveWithOtherCategories($settingsCategory)) {
                continue;
            }
            $settingsCategoryResolvers[$this->getOptionsFormOrigin($settingsCategoryResolver, $settingsCategory)] = [$settingsCategory, $settingsCategoryResolver];
        }

        $savedTabNames = [];
        foreach ($tabs as $optionsFormOrigin) {
            if (!is_string($optionsFormOrigin) || !isset($settingsCategoryResolvers[$optionsFormOrigin])) {
                add_settings_error(
                    'general',
                    'gato_save_all_unknown_tab',
                    sprintf(
                        __('Tab "%s" can\'t be saved with the others.', 'gatographql'),
                        esc_html(is_string($optionsFormOrigin) ? $optionsFormOrigin : '')
                    ),
                    'error'
                );
                continue;
            }
            /** @var string $settingsCategory */
            /** @var SettingsCategoryResolverInterface $settingsCategoryResolver */
            [$settingsCategory, $settingsCategoryResolver] = $settingsCategoryResolvers[$optionsFormOrigin];
            $tabName = $settingsCategoryResolver->getName($settingsCategory);

            $nonce = $nonces[$optionsFormOrigin] ?? '';
            if (!is_string($nonce) || !wp_verify_nonce($nonce, $optionsFormOrigin . '-options')) {
                add_settings_error(
                    'general',
                    'gato_save_all_expired',
                    sprintf(
                        __('The changes in tab "%s" were not saved, as the page had expired. Please reload the page, and save them again.', 'gatographql'),
                        esc_html($tabName)
                    ),
                    'error'
                );
                continue;
            }

            /** @var string */
            $capability = apply_filters("option_page_capability_{$optionsFormOrigin}", 'manage_options');
            if (!current_user_can($capability)) {
                add_settings_error(
                    'general',
                    'gato_save_all_not_allowed',
                    sprintf(
                        __('You are not allowed to save the settings in tab "%s".', 'gatographql'),
                        esc_html($tabName)
                    ),
                    'error'
                );
                continue;
            }

            $optionName = $settingsCategoryResolver->getDBOptionName($settingsCategory);
            $value = isset($post[$optionName]) ? wp_unslash($post[$optionName]) : [];
            if (!is_array($value)) {
                $value = [];
            }
            update_option($optionName, $value);
            $savedTabNames[] = $tabName;
        }

        return $savedTabNames;
    }

    protected function getSaveAllNoticesTransientName(): string
    {
        return $this->getSaveAllActionName() . '_notices_' . get_current_user_id();
    }
}
