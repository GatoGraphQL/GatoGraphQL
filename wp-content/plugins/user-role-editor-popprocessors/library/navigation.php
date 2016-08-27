<?php

/**---------------------------------------------------------------------------------------------------------------
 * navigation.php
 * ---------------------------------------------------------------------------------------------------------------*/
add_filter('gd_navigation_menu_item_icon', 'ure_navigation_menu_item_icon', 10, 3); 
function ure_navigation_menu_item_icon($icon, $menu_item_object_id, $html = true) {

	switch ($menu_item_object_id) {

		case POP_URE_POPPROCESSORS_PAGE_MEMBERS:
		case POP_URE_POPPROCESSORS_PAGE_MYMEMBERS:

			$fontawesome = 'fa-users';
			break;

		case POP_URE_POPPROCESSORS_PAGE_EDITPROFILEORGANIZATION:
		case POP_URE_POPPROCESSORS_PAGE_EDITPROFILEINDIVIDUAL:

			$fontawesome = 'fa-pencil-square-o';
			break;

		case POP_URE_POPPROCESSORS_PAGE_INVITENEWMEMBERS:

			$fontawesome = 'fa-user-plus';
			break;

		case POP_URE_POPPROCESSORS_PAGE_EDITMEMBERSHIP:

			$fontawesome = 'fa-certificate';
			break;

		case POP_URE_POPPROCESSORS_PAGE_MYCOMMUNITIES:
		case POP_URE_POPPROCESSORS_PAGE_COMMUNITIES:
		case POP_URE_POPPROCESSORS_PAGE_ORGANIZATIONS:
		case POP_URE_POPPROCESSORS_PAGE_ADDPROFILEORGANIZATION:

			$fontawesome = 'fa-institution';
			break;

		case POP_URE_POPPROCESSORS_PAGE_ADDPROFILEINDIVIDUAL:
		case POP_URE_POPPROCESSORS_PAGE_INDIVIDUALS:

			$fontawesome = 'fa-user';
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
