<?php

/**---------------------------------------------------------------------------------------------------------------
 * navigation.php
 * ---------------------------------------------------------------------------------------------------------------*/
add_filter('gd_navigation_menu_item_icon', 'agendaurbana_cpp_navigation_menu_item_icon', 10, 3); 
function agendaurbana_cpp_navigation_menu_item_icon($icon, $menu_item_object_id, $html = true) {

	switch ($menu_item_object_id) {

		case AGENDAURBANA_PAGE_ARTICLES:
		case AGENDAURBANA_PAGE_MYARTICLES:

			$fontawesome = 'fa-comment';
			break;

		case AGENDAURBANA_PAGE_ANNOUNCEMENTS:
		case AGENDAURBANA_PAGE_MYANNOUNCEMENTS:

			$fontawesome = 'fa-bullhorn';
			break;

		case AGENDAURBANA_PAGE_RESOURCES:
		case AGENDAURBANA_PAGE_MYRESOURCES:

			$fontawesome = 'fa-book';
			break;

		case AGENDAURBANA_PAGE_FEATURED:
		case AGENDAURBANA_PAGE_MYFEATURED:

			$fontawesome = 'fa-star';
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
