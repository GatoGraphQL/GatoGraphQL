<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Admin\Tables;

use GatoGraphQL\GatoGraphQL\App;
use GatoGraphQL\GatoGraphQL\ConditionalOnContext\Admin\SystemServices\TableActions\ModuleListTableAction;
use GatoGraphQL\GatoGraphQL\Constants\AdminRequestParams;
use GatoGraphQL\GatoGraphQL\Constants\HTMLCodes;
use GatoGraphQL\GatoGraphQL\Constants\RequestParams;
use GatoGraphQL\GatoGraphQL\Facades\Registries\ModuleRegistryFacade;
use GatoGraphQL\GatoGraphQL\Facades\Registries\ModuleTypeRegistryFacade;
use GatoGraphQL\GatoGraphQL\Facades\Registries\SystemSettingsCategoryRegistryFacade;
use GatoGraphQL\GatoGraphQL\Facades\UserSettingsManagerFacade;
use GatoGraphQL\GatoGraphQL\ModuleResolvers\PluginGeneralSettingsFunctionalityModuleResolver;
use GatoGraphQL\GatoGraphQL\ObjectModels\AbstractDependedOnWordPressPlugin;
use GatoGraphQL\GatoGraphQL\ObjectModels\DependedOnActiveWordPressPlugin;
use GatoGraphQL\GatoGraphQL\ObjectModels\DependedOnInactiveWordPressPlugin;
use GatoGraphQL\GatoGraphQL\PluginApp;
use GatoGraphQL\GatoGraphQL\Services\MenuPages\ModulesMenuPage;
use GatoGraphQL\GatoGraphQL\Services\MenuPages\SettingsMenuPage;
use GatoGraphQL\GatoGraphQL\Settings\UserSettingsManagerInterface;
use PoP\Root\Facades\Instances\InstanceManagerFacade;
use PoP\Root\Facades\Instances\SystemInstanceManagerFacade;

/**
 * Module Table
 */
class ModuleListTable extends AbstractItemListTable
{
    use WithOpeningModuleDocInModalListTableTrait;

    private ?UserSettingsManagerInterface $userSettingsManager = null;

    protected function getUserSettingsManager(): UserSettingsManagerInterface
    {
        return $this->userSettingsManager ??= UserSettingsManagerFacade::getInstance();
    }

    /**
     * Singular name of the listed records
     */
    public function getItemSingularName(): string
    {
        return \__('Module', 'gatographql');
    }

    /**
     * Plural name of the listed records
     */
    public function getItemPluralName(): string
    {
        return \__('Modules', 'gatographql');
    }

    /**
     * Return all the items to display on the table
     *
     * @return array<array<string,mixed>> Each item is an array of prop => value
     */
    public function getAllItems(): array
    {
        $items = [];
        $moduleRegistry = ModuleRegistryFacade::getInstance();
        $moduleTypeRegistry = ModuleTypeRegistryFacade::getInstance();
        $settingsCategoryRegistry = SystemSettingsCategoryRegistryFacade::getInstance();
        $modules = $moduleRegistry->getAllModules(false, false, true);
        $currentViews = $this->getCurrentViews();
        /** @var array<string,string> */
        $settingsCategoryIDs = [];
        foreach ($modules as $module) {
            $moduleResolver = $moduleRegistry->getModuleResolver($module);
            $moduleType = $moduleResolver->getModuleType($module);
            $moduleTypeResolver = $moduleTypeRegistry->getModuleTypeResolver($moduleType);
            $moduleTypeSlug = $moduleTypeResolver->getSlug($moduleType);
            $hasSettings = $moduleResolver->hasSettings($module);
            $settingsCategoryID = null;
            if ($hasSettings) {
                $settingsCategory = $moduleResolver->getSettingsCategory($module);
                if (!isset($settingsCategoryIDs[$settingsCategory])) {
                    $settingsCategoryIDs[$settingsCategory] = $settingsCategoryRegistry->getSettingsCategoryResolver($settingsCategory)->getID($settingsCategory);
                }
                $settingsCategoryID = $settingsCategoryIDs[$settingsCategory];
            }
            // If filtering the view, only add the items with that module type
            if ($currentViews === [] || in_array($moduleTypeSlug, $currentViews)) {
                $isEnabled = $moduleRegistry->isModuleEnabled($module);
                $canBeEnabled = $isEnabled ? false : $moduleRegistry->canModuleBeEnabled($module);
                $isPredefinedEnabledOrDisabled = $moduleResolver->isPredefinedEnabledOrDisabled($module);
                $items[] = [
                    'module' => $module,
                    'module-type' => $moduleTypeSlug,
                    'id' => $moduleResolver->getID($module),
                    'is-enabled' => $isEnabled,
                    'can-be-disabled' => $isEnabled && $isPredefinedEnabledOrDisabled === null,
                    'can-be-enabled' => !$isEnabled && $isPredefinedEnabledOrDisabled === null && $canBeEnabled,
                    'has-settings' => $hasSettings,
                    'are-settings-hidden' => $moduleResolver->areSettingsHidden($module),
                    'name' => $moduleResolver->getName($module),
                    'description' => $moduleResolver->getDescription($module),
                    'depends-on-modules' => $moduleResolver->getDependedModuleLists($module),
                    'depends-on-active-plugins' => $moduleResolver->getDependentOnActiveWordPressPlugins($module),
                    'depends-on-inactive-plugins' => $moduleResolver->getDependentOnInactiveWordPressPlugins($module),
                    // 'url' => $moduleResolver->getURL($module),
                    'slug' => $moduleResolver->getSlug($module),
                    'has-docs' => $moduleResolver->hasDocumentation($module),
                    'settings-category-id' => $settingsCategoryID,
                ];
            }
        }
        return $items;
    }

    /**
     * Gets the current filtering view(s).
     *
     * Can pass more than 1 view by concatenating them with ",":
     *
     *   &module-type=schema-type,schema-directive
     *
     * @return string[]
     */
    protected function getCurrentViews(): array
    {
        /** @var string */
        $currentView = App::request(AdminRequestParams::MODULE_TYPE) ?? App::query(AdminRequestParams::MODULE_TYPE, '');
        if ($currentView === '') {
            return [];
        }
        return explode(',', $currentView);
    }

    /**
     * Gets the list of views available on this table.
     *
     * @return array<string,string>
     * phpcs:disable PSR1.Methods.CamelCapsMethodName.NotCamelCaps
     */
    protected function get_views()
    {
        $views = [];
        $currentViews = $this->getCurrentViews();

        // Module page URL
        $url = admin_url(sprintf(
            'admin.php?page=%s',
            esc_attr(App::request('page') ?? App::query('page', ''))
        ));

        // All entries
        $views['all'] = sprintf(
            '<a href="%s" class="%s">%s</a>',
            $url,
            $currentViews === [] ? 'current' : '',
            \__('All', 'gatographql')
        );

        // Entries for every module type: retrieve the moduleType from all modules
        $moduleRegistry = ModuleRegistryFacade::getInstance();
        $moduleTypeRegistry = ModuleTypeRegistryFacade::getInstance();
        $modules = $moduleRegistry->getAllModules(false, false, true);
        $moduleTypes = [];
        foreach ($modules as $module) {
            $moduleResolver = $moduleRegistry->getModuleResolver($module);
            $moduleTypes[] = $moduleResolver->getModuleType($module);
        }
        $moduleTypes = array_unique($moduleTypes);
        foreach ($moduleTypes as $moduleType) {
            $moduleTypeResolver = $moduleTypeRegistry->getModuleTypeResolver($moduleType);
            if ($moduleTypeResolver->isHidden($moduleType)) {
                continue;
            }
            $moduleTypeSlug = $moduleTypeResolver->getSlug($moduleType);
            $views[$moduleTypeSlug] = sprintf(
                '<a href="%s" class="%s">%s</a>',
                \add_query_arg(AdminRequestParams::MODULE_TYPE, $moduleTypeSlug, $url),
                'module-type-view module-type-' . $moduleTypeSlug . (in_array($moduleTypeSlug, $currentViews) ? ' current' : ''),
                $moduleTypeResolver->getName($moduleType)
            );
        }

        return $views;
    }

    /**
     * List of item data
     *
     * @return array<array<string,mixed>>
     */
    public function getItems(int $per_page, int $page_number): mixed
    {
        $results = $this->getAllItems();
        return array_splice(
            $results,
            ($page_number - 1) * $per_page,
            $per_page
        );
    }

    /**
     * Returns the count of records in the database.
     */
    public function getRecordCount(): int
    {
        $results = $this->getAllItems();
        return count($results);
    }

    /**
     * Render a column when no column specific method exist.
     *
     * @param object $item
     * @param string $column_name
     *
     * @return mixed
     * phpcs:disable PSR1.Methods.CamelCapsMethodName.NotCamelCaps
     */
    protected function column_default($item, $column_name)
    {
        /**
         * Cast object so PHPStan doesn't throw error
         * @var array<string,mixed>
         */
        $item = $item;
        switch ($column_name) {
            case 'desc':
                $actions = [];
                // If it has, add a link to the documentation
                $instanceManager = InstanceManagerFacade::getInstance();
                /**
                 * @var ModulesMenuPage
                 */
                $modulesMenuPage = $instanceManager->getInstance(ModulesMenuPage::class);
                if ($item['has-docs']) {
                    $url = $this->getOpeningModuleDocInModalLinkURL(
                        $modulesMenuPage->getScreenID(),
                        $item['module']
                    );
                    $actions['docs'] = \sprintf(
                        '<a href="%s" class="%s" data-title="%s">%s</a>',
                        \esc_url($url),
                        'thickbox open-plugin-details-modal',
                        \esc_attr($item['name']),
                        \__('View details', 'gatographql')
                    );
                }
                return sprintf(
                    '<div class="plugin-description"><p>%s</p></div><div class="second">%s</div>',
                    $item['description'],
                    $this->row_actions($actions, true)
                );

            case 'depends-on':
                /** @var array<array<string>> */
                $dependedModuleLists = $item['depends-on-modules'];
                /** @var DependedOnActiveWordPressPlugin[] */
                $dependsOnActivePlugins = $item['depends-on-active-plugins'];
                /** @var DependedOnInactiveWordPressPlugin[] */
                $dependsOnInactivePlugins = $item['depends-on-inactive-plugins'];
                if (!$dependedModuleLists && !$dependsOnActivePlugins && !$dependsOnInactivePlugins) {
                    return \__('-', 'gatographql');
                }

                $moduleItems = [];
                $activePluginItems = [];
                $inactivePluginItems = [];
                $moduleRegistry = ModuleRegistryFacade::getInstance();

                /**
                 * This is a list of lists of modules, as to model both OR
                 * and AND conditions.
                 *
                 * - Outer level: List with AND lists of dependencies.
                 * - Inner level: List item is an OR list of depended modules.
                 *   It's formatted like this: module1, module2, module3 or module4, ...
                 */
                foreach ($dependedModuleLists as $dependedModuleList) {
                    if (!$dependedModuleList) {
                        continue;
                    }
                    $dependedModuleListNames = array_map(
                        function ($dependedModule) use ($moduleRegistry) {
                            $after = '';
                            // Check if it has the "inverse" token at the beginning,
                            // then it depends on the module being disabled, not enabled
                            if ($moduleRegistry->isInverseDependency($dependedModule)) {
                                // Revert to the normal module
                                $dependedModule = $moduleRegistry->getInverseDependency($dependedModule);
                                $after = \__('⇠ as disabled', 'gatographql');
                            }
                            $moduleResolver = $moduleRegistry->getModuleResolver($dependedModule);
                            return sprintf(
                                '%1$s %2$s %3$s',
                                '▹',
                                $moduleResolver->getName($dependedModule),
                                $after
                            );
                        },
                        $dependedModuleList
                    );
                    if (count($dependedModuleListNames) >= 2) {
                        $lastElem = array_pop($dependedModuleListNames);
                        $commaElems = implode(
                            \__(', ', 'gatographql'),
                            $dependedModuleListNames
                        );
                        $moduleItems[] = sprintf(
                            \__('%s or %s', 'gatographql'),
                            $commaElems,
                            $lastElem
                        );
                    } else {
                        $moduleItems[] = $dependedModuleListNames[0];
                    }
                }

                /**
                 * List of WordPress plugin slugs that must be active
                 * for the module to be enabled
                 */
                foreach ($dependsOnActivePlugins as $dependedPlugin) {
                    $dependedPluginHTML = $this->getDependedPluginHTML($dependedPlugin);
                    if ($dependedPlugin->versionConstraint !== null) {
                        $dependedPluginHTML = sprintf(
                            \__('%s (version constraint: <code>%s</code>)', 'gatographql'),
                            $dependedPluginHTML,
                            $dependedPlugin->versionConstraint
                        );
                    }
                    $activePluginItems[] = sprintf(
                        '%1$s %2$s',
                        '☑︎',
                        $dependedPluginHTML
                    );
                }

                /**
                 * List of WordPress plugin slugs that must be inactive
                 * for the module to be enabled
                 */
                foreach ($dependsOnInactivePlugins as $dependedPlugin) {
                    $dependedPluginHTML = $this->getDependedPluginHTML($dependedPlugin);
                    $inactivePluginItems[] = sprintf(
                        '%1$s %2$s',
                        '☒',
                        $dependedPluginHTML
                    );
                }

                return implode('<br/>', array_merge($moduleItems, $activePluginItems, $inactivePluginItems));

            case 'enabled':
                return \sprintf(
                    '<span role="img" aria-label="%s">%s</span>',
                    $item['is-enabled'] ? \__('Yes', 'gatographql') : \__('No', 'gatographql'),
                    $item['is-enabled'] ? '✅' : '❌'
                );
        }
        return '';
    }

    protected function getDependedPluginHTML(AbstractDependedOnWordPressPlugin $dependedPlugin): string
    {
        return $dependedPlugin->url === ''
            ? $dependedPlugin->name
            : sprintf(
                '<a href="%s" target="%s">%s%s</a>',
                $dependedPlugin->url,
                '_blank',
                $dependedPlugin->name,
                HTMLCodes::OPEN_IN_NEW_WINDOW
            );
    }

    /**
     * Render the bulk edit checkbox
     *
     * @param object $item
     *
     * @return string
     * phpcs:disable PSR1.Methods.CamelCapsMethodName.NotCamelCaps
     */
    protected function column_cb($item)
    {
        /**
         * Cast object so PHPStan doesn't throw error
         * @var array<string,mixed>
         */
        $item = $item;
        return sprintf(
            '<input type="checkbox" name="%s[]" value="%s" />',
            ModuleListTableAction::INPUT_BULK_ACTION_IDS,
            $item['id']
        );
    }

    /**
     * Method for name column
     *
     * @param array<string,string> $item an array of DB data
     *
     * @return string
     * phpcs:disable PSR1.Methods.CamelCapsMethodName.NotCamelCaps
     */
    public function column_name($item)
    {
        $title = '<strong>' . $item['name'] . '</strong>';
        $actions = $this->getColumnActions($item);
        return $title . $this->row_actions($actions/*, $this->usePluginTableStyle()*/);
    }

    /**
     * @param array<string,string> $item an array of DB data
     * @return array<string,string>
     */
    protected function getColumnActions(array $item): array
    {
        $nonce = \wp_create_nonce('gatographql_enable_or_disable_module');
        $currentViews = $this->getCurrentViews();
        $maybeCurrentViewParam = $currentViews !== [] ? '&' . AdminRequestParams::MODULE_TYPE . '=' . implode(',', $currentViews) : '';
        $linkPlaceholder = '<a href="?page=%s&action=%s&item=%s&_wpnonce=%s' . ($maybeCurrentViewParam) . '">%s</a>';
        $page = esc_attr(App::request('page') ?? App::query('page', ''));
        $actions = [];
        if ($item['is-enabled']) {
            // If it is enabled, offer to disable it
            // Unless the module cannot be disabled
            if ($item['can-be-disabled']) {
                $actions['disable'] = \sprintf(
                    $linkPlaceholder,
                    $page,
                    ModuleListTableAction::ACTION_DISABLE,
                    $item['id'],
                    $nonce,
                    \__('Disable', 'gatographql')
                );
            } else {
                $actions['enabled'] = \__('Enabled', 'gatographql');
            }

            // Maybe add settings links
            if ($item['has-settings'] && !$item['are-settings-hidden']) {
                $instanceManager = InstanceManagerFacade::getInstance();
                /**
                 * @var SettingsMenuPage
                 */
                $settingsMenuPage = $instanceManager->getInstance(SettingsMenuPage::class);
                /**
                 * If the Settings page is not organized by tabs,
                 * then scroll down to the item
                 */
                $settingsURLPlaceholder = 'admin.php?page=%1$s&%2$s=%3$s&%4$s=%5$s';
                if (!$this->printSettingsPageWithTabs()) {
                    $settingsURLPlaceholder .= '#%5$s';
                }
                $actions['settings'] = \sprintf(
                    '<a href="%s">%s</a>',
                    sprintf(
                        \admin_url(sprintf(
                            $settingsURLPlaceholder,
                            $settingsMenuPage->getScreenID(),
                            RequestParams::CATEGORY,
                            $item['settings-category-id'],
                            RequestParams::MODULE,
                            $item['id']
                        ))
                    ),
                    \__('Settings', 'gatographql')
                );
            }
        } elseif ($item['can-be-enabled']) {
            // If not enabled and can be enabled, offer to do it
            $actions['enable'] = \sprintf(
                $linkPlaceholder,
                $page,
                ModuleListTableAction::ACTION_ENABLE,
                $item['id'],
                $nonce,
                \__('Enable', 'gatographql')
            );
        } else {
            // Not enabled and can't be enabled, mention requirements not met
            // Not enabled for "striped" table style because, without a link, color contrast is not good:
            // gray font color over gray background
            // if ($this->usePluginTableStyle()) {
            $actions['disabled'] = \__('Disabled', 'gatographql');
            // }
        }
        return $actions;
    }

    /**
     * The user can define this behavior through the Settings.
     * If `true`, print the sections using tabs
     * If `false`, print the sections one below the other
     */
    protected function printSettingsPageWithTabs(): bool
    {
        return $this->getUserSettingsManager()->getSetting(
            PluginGeneralSettingsFunctionalityModuleResolver::GENERAL,
            PluginGeneralSettingsFunctionalityModuleResolver::OPTION_PRINT_SETTINGS_WITH_TABS
        );
    }

    /**
     * Indicate if to show the enabled column or not
     */
    protected function usePluginTableStyle(): bool
    {
        return true;
    }

    /**
     *  Associative array of columns
     *
     * @return array<string,string>
     * phpcs:disable PSR1.Methods.CamelCapsMethodName.NotCamelCaps
     */
    public function get_columns()
    {
        return array_merge(
            [
                'cb' => '<input type="checkbox" />',
                'name' => \__('Module', 'gatographql'),
            ],
            $this->usePluginTableStyle() ?
                [] :
                [
                    'enabled' => \__('Enabled', 'gatographql'),
                ],
            [
                'desc' => \__('Description', 'gatographql'),
                'depends-on' => sprintf(
                    \__('%s<br/>&nbsp;&nbsp;%s<br/>&nbsp;&nbsp;%s<br/>&nbsp;&nbsp;%s', 'gatographql'),
                    \__('Depends on:', 'gatographql'),
                    \__('▹ active module', 'gatographql'),
                    \__('☑︎ active plugin', 'gatographql'),
                    \__('☒ inactive plugin', 'gatographql'),
                ),
            ]
        );
    }

    /**
     * Returns an associative array containing the bulk action
     *
     * @return array<string,string>
     * phpcs:disable PSR1.Methods.CamelCapsMethodName.NotCamelCaps
     */
    public function get_bulk_actions()
    {
        return [
            ModuleListTableAction::ACTION_ENABLE => \__('Enable', 'gatographql'),
            ModuleListTableAction::ACTION_DISABLE => \__('Disable', 'gatographql'),
        ];
    }

    /**
    * Get a list of CSS classes for the WP_List_Table table tag.
    *
    * @since 3.1.0
    *
    * @return mixed[]|string[] Array of CSS classes for the table tag.
    * phpcs:disable PSR1.Methods.CamelCapsMethodName.NotCamelCaps
    */
    protected function get_table_classes()
    {
        // return array_merge(
        //     parent::get_table_classes(),
        //     [
        //         'plugins'
        //     ]
        // );
        if ($this->usePluginTableStyle()) {
            return array( 'widefat', 'plugins', $this->_args['plural'] );
        }
        return array_diff(
            parent::get_table_classes(),
            [
                'fixed'
            ]
        );
    }

    /**
     * Classnames to add to the row for the item
     *
     * @param object $item The current item
     */
    protected function getTableStyleRowClassnames($item): string
    {
        /**
         * Cast object so PHPStan doesn't throw error
         * @var array<string,mixed>
         */
        $item = $item;
        return sprintf(
            'module-%s',
            $item['module-type']
        );
    }

    /**
     * Generates content for a single row of the table
     *
     * @since 3.1.0
     *
     * @param object $item The current item
     * @return void
     * phpcs:disable PSR1.Methods.CamelCapsMethodName.NotCamelCaps
     */
    public function single_row($item)
    {
        /**
         * Cast object so PHPStan doesn't throw error
         * @var array<string,mixed>
         */
        $arrayItem = $item;
        if ($this->usePluginTableStyle()) {
            $classnames = sprintf(
                '%s %s',
                $this->getTableStyleRowClassnames($item),
                $arrayItem['is-enabled'] ? 'active' : 'inactive'
            );
            ?>
                <tr class="<?php echo \esc_attr($classnames) ?>">
                <?php
                    $this->single_row_columns($item);
                ?>
                </tr>
            <?php
        } else {
            parent::single_row($item);
        }
    }

    /**
     * Handles data query and filter, sorting, and pagination.
     *
     * @return void
     * phpcs:disable PSR1.Methods.CamelCapsMethodName.NotCamelCaps
     */
    public function prepare_items()
    {
        $this->_column_headers = $this->get_column_info();

        /**
         * Watch out! ModuleListTableAction is registered in the SystemContainer,
         * not in the ApplicationContainer
         */
        /** Process bulk or single action */
        $systemInstanceManager = SystemInstanceManagerFacade::getInstance();
        /**
         * @var ModuleListTableAction
         */
        $tableAction = $systemInstanceManager->getInstance(ModuleListTableAction::class);
        $tableAction->maybeProcessAction();

        $per_page = $this->get_items_per_page(
            $this->getItemsPerPageOptionName(),
            $this->getDefaultItemsPerPage()
        );
        $current_page = $this->get_pagenum();
        $total_items  = $this->getRecordCount();

        $this->set_pagination_args([
            'total_items' => $total_items,
            'per_page'    => $per_page,
        ]);

        $this->items = $this->getItems($per_page, $current_page);
    }

    /**
     * Enqueue the required assets
     */
    public function enqueueAssets(): void
    {
        parent::enqueueAssets();

        $mainPlugin = PluginApp::getMainPlugin();
        $mainPluginURL = $mainPlugin->getPluginURL();
        $mainPluginVersion = $mainPlugin->getPluginVersion();

        /**
         * Fix the issues with the WP List Table
         */
        \wp_enqueue_style(
            'gatographql-module-list-table',
            $mainPluginURL . 'assets/css/module-list-table.css',
            array(),
            $mainPluginVersion
        );
    }

    /**
     * Customize the width of the columns
     */
    public function printStyles(): void
    {
        parent::printStyles();

        /*
        if ($this->usePluginTableStyle()) {
            ?>
            <style type="text/css">
                .wp-list-table .column-name { width: 25%; }
                .wp-list-table .column-description { width: 75%; }
            </style>
            <?php
        } else {
            ?>
            <style type="text/css">
                .wp-list-table .column-name { width: 25%; }
                .wp-list-table .column-enabled { width: 10%; }
                .wp-list-table .column-description { width: 65%; }
            </style>
            <?php
        }
        */
    }
}
