<?php
namespace PoP\CMSModel;

define ('GD_DATALOADER_MENU', 'menu');

class Dataloader_Menu extends Dataloader_MenuBase {

	function get_name() {
    
		return GD_DATALOADER_MENU;
	}
	
	function get_dbobject_ids($data_properties) {

		$query_args = $data_properties[GD_DATALOAD_QUERYARGS];
	
		$menu = $query_args['menu'];

    	$cmsresolver = \PoP\CMS\ObjectPropertyResolver_Factory::get_instance();
    	$cmsapi = \PoP\CMS\FunctionAPI_Factory::get_instance();
		$locations = $cmsapi->get_nav_menu_locations();
		$menu_object = $cmsapi->wp_get_nav_menu_object($locations[$menu]);

		return array($cmsresolver->get_menu_object_term_id($menu_object));
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new Dataloader_Menu();