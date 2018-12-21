<?php
namespace PoP\CMSModel;

define ('GD_DATALOAD_FIELDPROCESSOR_MENU', 'menu');
	
class FieldProcessor_Menu extends \PoP\Engine\FieldProcessorBase {

	function get_name() {
	
		return GD_DATALOAD_FIELDPROCESSOR_MENU;
	}

	function get_value($resultitem, $field) {

		// First Check if there's a hook to implement this field
		$hook_value = $this->get_hook_value(GD_DATALOAD_FIELDPROCESSOR_MENU, $resultitem, $field);
		if (!is_wp_error($hook_value)) {
			return $hook_value;
		}	
	
    	$cmsresolver = \PoP\CMS\ObjectPropertyResolver_Factory::get_instance();
    	$cmsapi = \PoP\CMS\FunctionAPI_Factory::get_instance();
		$menu = $resultitem;
		switch ($field) {
		
			case 'items' :
				
				// Load needed values for the menu-items
				global $gd_dataload_fieldprocessor_manager;
				$gd_dataload_fieldprocessor_menu_items = $gd_dataload_fieldprocessor_manager->get(GD_DATALOAD_FIELDPROCESSOR_MENU_ITEMS);
				$items = $cmsapi->wp_get_nav_menu_items($cmsresolver->get_menu_term_id($menu));

				// Load these item data-fields. If other set needed, create another $field
				$item_data_fields = array('id', 'title', 'alt', 'classes', 'url', 'target', 'menu-item-parent', 'object-id', 'additional-attrs');
				$value = array();
				if ($items) {
					foreach ($items as $item) {

						$item_value = array();
						foreach ($item_data_fields as $item_data_field) {

							$item_value[$item_data_field] = $gd_dataload_fieldprocessor_menu_items->get_value($item, $item_data_field);
						}

						$id = $gd_dataload_fieldprocessor_menu_items->get_id($item);
						$value[] = $item_value;
					}
				}
				break;
			
			default:
				$value = parent::get_value($resultitem, $field);
				break;
		}

		return $value;
	}	

	function get_id($resultitem) {

    	$cmsresolver = \PoP\CMS\ObjectPropertyResolver_Factory::get_instance();
		$menu = $resultitem;
		return $cmsresolver->get_menu_term_id($menu);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new FieldProcessor_Menu(); 