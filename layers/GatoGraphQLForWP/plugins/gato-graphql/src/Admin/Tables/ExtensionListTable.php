<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Admin\Tables;

use stdClass;
use WP_Plugin_Install_List_Table;

/**
 * The file containing class WP_Plugin_Install_List_Table is not
 * loaded by default in WordPress.
 */
require_once ABSPATH . 'wp-admin/includes/class-wp-plugin-install-list-table.php';

/**
 * Extension Table
 */
class ExtensionListTable extends WP_Plugin_Install_List_Table implements ItemListTableInterface
{
    use ItemListTableTrait;

    /**
	 * @global array  $tabs
	 * @global string $tab
	 * @global int    $paged
	 * @global string $type
	 * @global string $term
	 */
	public function prepare_items() {
		
        add_filter('install_plugins_tabs', $this->overrideInstallPluginTabs(...));
        add_filter('install_plugins_nonmenu_tabs', $this->overrideInstallPluginNonMenuTabs(...));
        add_filter('plugins_api', $this->overridePluginsAPI(...));
        add_filter('plugins_api_result', $this->overridePluginsAPIResult(...));
        parent::prepare_items();
        remove_filter('plugins_api_result', $this->overridePluginsAPIResult(...));
        remove_filter('plugins_api', $this->overridePluginsAPI(...));
        remove_filter('install_plugins_nonmenu_tabs', $this->overrideInstallPluginNonMenuTabs(...));
        remove_filter('install_plugins_tabs', $this->overrideInstallPluginTabs(...));
	}

    /**
     * Keep only the "Featured" tab
     *
     * @param string[] $tabs
     * @return string[]
     */
    public function overrideInstallPluginTabs(array $tabs): array
    {
        return array_intersect_key(
            ['featured' => true],
            $tabs
        );
    }

    /**
     * Remove all tabs
     *
     * @param string[] $tabs
     * @return string[]
     */
    public function overrideInstallPluginNonMenuTabs(array $tabs): array
    {
        return [];
    }

    /**
     * Do not connect to wordpress.org to retrieve data
     */
    public function overridePluginsAPI(): mixed
    {
        return new stdClass();
    }

    /**
     * Hardcoded data with extensions
     */
    public function overridePluginsAPIResult(): mixed
    {
        $data = [

        ];
        return (object) $data;
    }
}
