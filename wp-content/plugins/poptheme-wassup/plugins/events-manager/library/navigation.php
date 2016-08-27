<?php

/**---------------------------------------------------------------------------------------------------------------
 * navigation.php
 * ---------------------------------------------------------------------------------------------------------------*/
add_filter('gd_navigation_menu_item_icon', 'popwassup_em_navigation_menu_item_icon', 10, 3); 
function popwassup_em_navigation_menu_item_icon($icon, $menu_item_object_id, $html = true) {

	switch ($menu_item_object_id) {

		case POPTHEME_WASSUP_EM_PAGE_EVENTS:
		case POPTHEME_WASSUP_EM_PAGE_PASTEVENTS:
		case POPTHEME_WASSUP_EM_PAGE_MYEVENTS:
		case POPTHEME_WASSUP_EM_PAGE_ADDEVENT:
		case POPTHEME_WASSUP_EM_PAGE_EDITEVENT:
		case POPTHEME_WASSUP_EM_PAGE_MYPASTEVENTS:

			$fontawesome = 'fa-calendar';
			break;

		case POPTHEME_WASSUP_EM_PAGE_ADDEVENTLINK:
		case POPTHEME_WASSUP_EM_PAGE_EDITEVENTLINK:

			$fontawesome = 'fa-link';
			break;

		case POPTHEME_WASSUP_EM_PAGE_EVENTSCALENDAR:

			$fontawesome = 'fa-calendar-o';
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
