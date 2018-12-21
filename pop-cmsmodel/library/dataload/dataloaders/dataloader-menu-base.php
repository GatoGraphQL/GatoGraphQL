<?php
namespace PoP\CMSModel;

abstract class Dataloader_MenuBase extends \PoP\Engine\QueryDataDataloader {

	function get_fieldprocessor() {

		return GD_DATALOAD_FIELDPROCESSOR_MENU;
	}

	function get_database_key() {
	
		return GD_DATABASE_KEY_MENUS;
	}
	
	function execute_get_data($ids) {
	
    	$cmsapi = \PoP\CMS\FunctionAPI_Factory::get_instance();
		$ret = array_map(array($cmsapi, 'wp_get_nav_menu_object'), $ids);
		return $ret;
	}

}