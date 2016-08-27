<?php

/**---------------------------------------------------------------------------------------------------------------
 * navigation.php
 * ---------------------------------------------------------------------------------------------------------------*/
add_filter('gd_navigation_menu_item_icon', 'op_navigation_menu_item_icon', 10, 3); 
function op_navigation_menu_item_icon($icon, $menu_item_object_id, $html = true) {

	switch ($menu_item_object_id) {

		case POPTHEME_WASSUP_ORGANIKPROCESSORS_PAGE_FARMS:
		case POPTHEME_WASSUP_ORGANIKPROCESSORS_PAGE_MYFARMS:
		case POPTHEME_WASSUP_ORGANIKPROCESSORS_PAGE_ADDFARM:
		case POPTHEME_WASSUP_ORGANIKPROCESSORS_PAGE_EDITFARM:

			$fontawesome = 'fa-tree';
			break;

		case POPTHEME_WASSUP_ORGANIKPROCESSORS_PAGE_ADDFARMLINK:
		case POPTHEME_WASSUP_ORGANIKPROCESSORS_PAGE_EDITFARMLINK:

			$fontawesome = 'fa-link';
			break;
	}

	// Important: do not replace the \' below for quotes, otherwise the "Share by email" and "Embed" buttons
	// don't work for pages (eg: http://m3l.localhost/become-a-featured-community/) since the fontawesome icons
	// screw up the data-header attr in the link
	if ($fontawesome) {

		if ($html) {
			return sprintf('<i class=\'fa fa-fw %s\'></i>', $fontawesome);
		}
		return $fontawesome;
	}
	
	return $icon;
}
