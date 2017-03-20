<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Users Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_DATALOADER_MENU', 'menu');

class GD_DataLoader_Menu extends GD_DataLoader {

	function get_name() {
    
		return GD_DATALOADER_MENU;
	}
	
	function get_execution_priority() {
    
		return 1;
	}

	function get_crawlabledata_printer() {
	
		return GD_DATALOAD_CRAWLABLEDATAPRINTER_MENU;
	}

	function get_data_ids($vars = array(), $is_main_query = false) {
	
		$menu = $vars['menu'];

		$locations = get_nav_menu_locations();
		$menu_object = wp_get_nav_menu_object( $locations[ $menu ] );

		return array($menu_object->term_id);
	}
	
	function execute_get_data($ids) {
	
		$ret = array_map('wp_get_nav_menu_object', $ids);

		return $ret;
	}

	function get_database_key() {
	
		return GD_DATABASE_KEY_MENU;
	}

}

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoader_Menu();