<?php

/**---------------------------------------------------------------------------------------------------------------
 * navigation.php
 * ---------------------------------------------------------------------------------------------------------------*/
add_filter('gd_navigation_menu_item_icon', 'tppdebate_cpp_navigation_menu_item_icon', 10, 3); 
function tppdebate_cpp_navigation_menu_item_icon($icon, $menu_item_object_id, $html = true) {

	switch ($menu_item_object_id) {

		case TPPDEBATE_PAGE_ARTICLES:
		case TPPDEBATE_PAGE_MYARTICLES:

			$fontawesome = 'fa-comment';
			break;

		case TPPDEBATE_PAGE_ANNOUNCEMENTS:
		case TPPDEBATE_PAGE_MYANNOUNCEMENTS:

			$fontawesome = 'fa-bullhorn';
			break;

		case TPPDEBATE_PAGE_RESOURCES:
		case TPPDEBATE_PAGE_MYRESOURCES:

			$fontawesome = 'fa-book';
			break;

		case TPPDEBATE_PAGE_FEATURED:
		case TPPDEBATE_PAGE_MYFEATURED:

			$fontawesome = 'fa-star';
			break;

		case TPPDEBATE_PAGE_LEGAL:
		case TPPDEBATE_PAGE_MYLEGAL:

			$fontawesome = 'fa-legal';
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
