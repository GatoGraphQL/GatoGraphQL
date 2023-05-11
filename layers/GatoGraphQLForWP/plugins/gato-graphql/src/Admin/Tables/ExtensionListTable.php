<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Admin\Tables;

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
		
        add_filter('install_plugins_tabs', $this->getInstallPluginTabs(...));
        add_filter('install_plugins_nonmenu_tabs', $this->getInstallPluginNonMenuTabs(...));
        parent::prepare_items();
        remove_filter('install_plugins_nonmenu_tabs', $this->getInstallPluginNonMenuTabs(...));
        remove_filter('install_plugins_tabs', $this->getInstallPluginTabs(...));
	}

    /**
     * Keep only the "Featured" tab
     *
     * @param string[] $tabs
     * @return string[]
     */
    public function getInstallPluginTabs(array $tabs): array
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
    public function getInstallPluginNonMenuTabs(array $tabs): array
    {
        return [];
    }
}
