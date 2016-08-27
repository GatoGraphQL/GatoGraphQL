<?php

/**---------------------------------------------------------------------------------------------------------------
 * navigation.php
 * ---------------------------------------------------------------------------------------------------------------*/
add_filter('gd_navigation_menu_item_icon', 'popwassup_gf_navigation_menu_item_icon', 10, 3); 
function popwassup_gf_navigation_menu_item_icon($icon, $menu_item_object_id, $html = true) {

	switch ($menu_item_object_id) {

		case POPTHEME_WASSUP_GF_PAGE_VOLUNTEER:

			$fontawesome = 'fa-leaf';
			break;

		case POPTHEME_WASSUP_GF_PAGE_FLAG:

			$fontawesome = 'fa-flag';
			break;

		case POPTHEME_WASSUP_GF_PAGE_CONTACTUS:
		case POPTHEME_WASSUP_GF_PAGE_CONTACTUSER:
		case POPTHEME_WASSUP_GF_PAGE_SHAREBYEMAIL:

			$fontawesome = 'fa-envelope-o';
			break;

		case POPTHEME_WASSUP_GF_PAGE_NEWSLETTER:

			$fontawesome = 'fa-envelope';
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
