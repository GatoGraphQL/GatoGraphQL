<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/
 
define ('GD_DATALOAD_FIELDPROCESSOR_MENU_ITEMS', 'menu-items');

class GD_DataLoad_FieldProcessor_Menu_Items extends GD_DataLoad_FieldProcessor {

	function get_name() {
	
		return GD_DATALOAD_FIELDPROCESSOR_MENU_ITEMS;
	}

	function get_value($resultitem, $field) {

		// First Check if there's a hook to implement this field
		$hook_value = $this->get_hook_value(GD_DATALOAD_FIELDPROCESSOR_MENU_ITEMS, $resultitem, $field);
		if (!is_wp_error($hook_value)) {
			return $hook_value;
		}	
	
		$menu_item = $resultitem;		

		switch ($field) {
		
			// case 'id' :
			// 	$value = $this->get_id($menu_item);
			// 	break;
			
			case 'title' :
				
				$value = apply_filters('the_title', $menu_item->title, $menu_item->object_id);
				break;

			case 'alt' :
				
				$value = $menu_item->title;
				break;
		
			case 'url' :
				$value = $menu_item->url;
				break;
		
			case 'classes' :
				// Copied from nav-menu-template.php function start_el
				$classes = empty( $menu_item->classes ) ? array() : (array) $menu_item->classes;
				$classes[] = 'menu-item';
				$classes[] = 'menu-item-' . $menu_item->ID;
				if ($parent = $menu_item->menu_item_parent) {
					
					$classes[] = 'menu-item-parent';
					$classes[] = 'menu-item-parent-' . $parent;
				}
				$value = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $menu_item, array() ) );
				break;
			
			case 'target' :			
				$value = $menu_item->target;
				break;	

			case 'additional-attrs' :	
				// Using the description, because WP does not give a field for extra attributes when creating a menu,
				// and this is needed to add target="addons" for the Add WebPost link
				$value = $menu_item->description;
				break;	

			case 'object-id' :			
				$value = $menu_item->object_id;
				break;	

			case 'menu-item-parent' :			
				$value = $menu_item->menu_item_parent;
				break;		
			
			default:
				$value = parent::get_value($resultitem, $field);
				break;
		}

		return $value;
	}	

	function get_id($resultitem) {

		$menu_item = $resultitem;	
		return $menu_item->ID;		
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoad_FieldProcessor_Menu_Items();
