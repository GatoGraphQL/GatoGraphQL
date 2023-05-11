<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Admin\Tables;

use GatoGraphQL\GatoGraphQL\PluginApp;
use WP_Error;
use WP_Plugin_Install_List_Table;
use stdClass;

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
     * Hardcoded data with extensions.
     *
     * To see what data must be retrieved, execute:
     *
     * http://api.wordpress.org/plugins/info/1.2/?action=query_plugins&per_page=1
     *
     * @see http://api.wordpress.org/plugins/info/1.2/?action=query_plugins&per_page=1
     */
    public function overridePluginsAPIResult(): mixed
    {
        $extensionsDataSourceFile = PluginApp::getMainPlugin()->getPluginDir() . '/data-sources/extensions.json';
        $extensionsDataSource = file_get_contents($extensionsDataSourceFile);
        if ($extensionsDataSource === false) {
            return new stdClass();
        }
        $extensionsData = json_decode($extensionsDataSource, true);
        return (object) $extensionsData;
    }

    public function no_items() {
		if ( isset( $this->error ) ) {
            parent::no_items();
        } else { ?>
			<div class="no-plugin-results"><?php _e('Ooops something went wrong: No extensions found. Please contact the admin.', 'gato-graphql'); ?></div>
			<?php
		}
	}
}
