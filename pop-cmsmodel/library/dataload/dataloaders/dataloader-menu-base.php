<?php

class GD_Dataloader_MenuBase extends GD_QueryDataDataloader {

	function get_fieldprocessor() {

		return GD_DATALOAD_FIELDPROCESSOR_MENU;
	}

	function get_database_key() {
	
		return GD_DATABASE_KEY_MENUS;
	}
	
	function execute_get_data($ids) {
	
    	$cmsapi = PoP_CMS_FunctionAPI_Factory::get_instance();
		$ret = array_map(array($cmsapi, 'wp_get_nav_menu_object'), $ids);
		return $ret;
	}

}