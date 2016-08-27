<?php

/**---------------------------------------------------------------------------------------------------------------
 * navigation.php
 * ---------------------------------------------------------------------------------------------------------------*/
add_filter('gd_navigation_menu_item_icon', 'popwpapi_navigation_menu_item_icon', 10, 3); 
function popwpapi_navigation_menu_item_icon($icon, $menu_item_object_id, $html = true) {

	switch ($menu_item_object_id) {

		case POP_WPAPI_PAGE_SEARCHPOSTS:
		case POP_WPAPI_PAGE_SEARCHUSERS:

			$fontawesome = 'fa-search';
			break;

		case POP_WPAPI_PAGE_ALLCONTENT:

			$fontawesome = 'fa-asterisk';
			break;

		case POP_WPAPI_PAGE_ALLUSERS:

			$fontawesome = 'fa-users';
			break;

		case POP_WPAPI_PAGE_MYCONTENT:

			$fontawesome = 'fa-edit';
			break;

		case POP_WPAPI_PAGE_LOGIN:

			$fontawesome = 'fa-sign-in';
			break;

		case POP_WPAPI_PAGE_LOSTPWD:
		case POP_WPAPI_PAGE_LOSTPWDRESET:

			$fontawesome = 'fa-warning';
			break;

		case POP_WPAPI_PAGE_LOGOUT:

			$fontawesome = 'fa-sign-out';
			break;

		case POP_WPAPI_PAGE_COMMENTS:
		case POP_WPAPI_PAGE_ADDCOMMENT:

			$fontawesome = 'fa-comments';
			break;

		case POP_WPAPI_PAGE_TAGS:
		case POP_WPAPI_PAGE_TRENDINGTAGS:

			$fontawesome = 'fa-hashtag';
			break;

		case POP_WPAPI_PAGE_EDITPROFILE:

			$fontawesome = 'fa-pencil-square-o';
			break;

		case POP_WPAPI_PAGE_EDITAVATAR:

			$fontawesome = 'fa-camera';
			break;

		case POP_WPAPI_PAGE_CHANGEPASSWORDPROFILE:

			$fontawesome = 'fa-pencil-square';
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
