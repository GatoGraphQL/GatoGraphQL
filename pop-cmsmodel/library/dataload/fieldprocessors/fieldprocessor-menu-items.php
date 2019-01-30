<?php
namespace PoP\CMSModel;
 
define ('GD_DATALOAD_FIELDPROCESSOR_MENU_ITEMS', 'menu-items');

class FieldProcessor_Menu_Items extends \PoP\Engine\FieldProcessorBase {

	function get_name() {
	
		return GD_DATALOAD_FIELDPROCESSOR_MENU_ITEMS;
	}

	function get_value($resultitem, $field) {

		// First Check if there's a hook to implement this field
		$hook_value = $this->get_hook_value(GD_DATALOAD_FIELDPROCESSOR_MENU_ITEMS, $resultitem, $field);
		if (!is_wp_error($hook_value)) {
			return $hook_value;
		}	
	
    	$cmsresolver = \PoP\CMS\ObjectPropertyResolver_Factory::get_instance();
    	$cmsapi = \PoP\CMS\FunctionAPI_Factory::get_instance();
		$menu_item = $resultitem;
		switch ($field) {
		
			case 'title' :				
				$value = apply_filters('the_title', $cmsresolver->get_menu_item_title($menu_item), $cmsresolver->get_menu_item_object_id($menu_item));
				break;

			case 'alt' :				
				$value = $cmsresolver->get_menu_item_title($menu_item);
				break;
		
			case 'url' :
				$value = $cmsresolver->get_menu_item_url($menu_item);
				break;
		
			case 'classes' :
				
				// Copied from nav-menu-template.php function start_el
				$classes = $cmsresolver->get_menu_item_classes($menu_item);
				$classes = empty($classes) ? array() : (array) $classes;
				$classes[] = 'menu-item';
				$classes[] = 'menu-item-' . $cmsresolver->get_menu_item_id($menu_item);
				if ($parent = $cmsresolver->get_menu_item_parent($menu_item)) {
					
					$classes[] = 'menu-item-parent';
					$classes[] = 'menu-item-parent-' . $parent;
				}
				$value = join(' ', apply_filters( 'nav_menu_css_class', array_filter($classes), $menu_item, array()));
				break;
			
			case 'target' :			
				$value = $cmsresolver->get_menu_item_target($menu_item);
				break;	

			case 'additional-attrs' :	
				// Using the description, because WP does not give a field for extra attributes when creating a menu,
				// and this is needed to add target="addons" for the Add ContentPost link
				$value = $cmsresolver->get_menu_item_description($menu_item);
				break;	

			case 'object-id' :			
				$value = $cmsresolver->get_menu_item_object_id($menu_item);
				break;	

			case 'menu-item-parent' :			
				$value = $cmsresolver->get_menu_item_parent($menu_item);
				break;		
			
			default:
				$value = parent::get_value($resultitem, $field);
				break;
		}

		return $value;
	}	

	function get_id($resultitem) {

    	$cmsresolver = \PoP\CMS\ObjectPropertyResolver_Factory::get_instance();
		$menu_item = $resultitem;	
		return $cmsresolver->get_menu_item_id($menu_item);
	}

	function get_field_default_dataloader($field) {

		// First Check if there's a hook to implement this field
		$default_dataloader = $this->get_hook_field_default_dataloader(GD_DATALOAD_FIELDPROCESSOR_MENU_ITEMS, $field);
		if ($default_dataloader) {
			return $default_dataloader;
		}

		return parent::get_field_default_dataloader($field);
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new FieldProcessor_Menu_Items();
