<?php

/**---------------------------------------------------------------------------------------------------------------
 * navigation.php
 * ---------------------------------------------------------------------------------------------------------------*/
add_filter('gd_navigation_menu_item_icon', 'popwassup_navigation_menu_item_icon', 10, 3); 
function popwassup_navigation_menu_item_icon($icon, $menu_item_object_id, $html = true) {

	switch ($menu_item_object_id) {

		case POPTHEME_WASSUP_PAGE_ADDCONTENT:

			$fontawesome = 'fa-plus';
			break;

		case POPTHEME_WASSUP_PAGE_WEBPOSTLINKS:
		case POPTHEME_WASSUP_PAGE_MYWEBPOSTLINKS:
		case POPTHEME_WASSUP_PAGE_ADDWEBPOSTLINK:
		case POPTHEME_WASSUP_PAGE_EDITWEBPOSTLINK:

			$fontawesome = 'fa-link';
			break;

		case POPTHEME_WASSUP_PAGE_WEBPOSTS:
		case POPTHEME_WASSUP_PAGE_MYWEBPOSTS:
		case POPTHEME_WASSUP_PAGE_ADDWEBPOST:
		case POPTHEME_WASSUP_PAGE_EDITWEBPOST:

			// $fontawesome = 'fa-flash';
			$fontawesome = 'fa-circle';
			break;

		case POPTHEME_WASSUP_PAGE_HIGHLIGHTS:
		case POPTHEME_WASSUP_PAGE_MYHIGHLIGHTS:
		case POPTHEME_WASSUP_PAGE_ADDHIGHLIGHT:
		case POPTHEME_WASSUP_PAGE_EDITHIGHLIGHT:

			$fontawesome = 'fa-bullseye';
			break;

		case POPTHEME_WASSUP_PAGE_ADDCONTENTFAQ:
		case POPTHEME_WASSUP_PAGE_ACCOUNTFAQ:

			$fontawesome = 'fa-info-circle';
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
