<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Navigation
 *
 * ---------------------------------------------------------------------------------------------------------------*/

add_filter('gd_navigation_menu_item_icon', 'popcore_navigation_menu_item_icon', 10, 3); 
function popcore_navigation_menu_item_icon($icon, $menu_item_object_id, $html = true) {

	switch ($menu_item_object_id) {

		case POP_COREPROCESSORS_PAGE_DESCRIPTION:
		case POP_COREPROCESSORS_PAGE_MAIN:

			$fontawesome = 'fa-circle';
			break;

		case POP_COREPROCESSORS_PAGE_SUMMARY:

			$fontawesome = 'fa-star';
			break;

		case POP_COREPROCESSORS_PAGE_RELATEDCONTENT:

			$fontawesome = 'fa-asterisk';
			break;

		case POP_COREPROCESSORS_PAGE_POSTAUTHORS:

			$fontawesome = 'fa-user';
			break;

		case POP_COREPROCESSORS_PAGE_FOLLOWERS:

			$fontawesome = 'fa-users';
			break;

		case POP_COREPROCESSORS_PAGE_FOLLOWUSER:
		case POP_COREPROCESSORS_PAGE_UNFOLLOWUSER:
		case POP_COREPROCESSORS_PAGE_FOLLOWINGUSERS:
		case POP_COREPROCESSORS_PAGE_SUBSCRIBERS:
		case POP_COREPROCESSORS_PAGE_SUBSCRIBEDTO:
		case POP_COREPROCESSORS_PAGE_SUBSCRIBETOTAG:
		case POP_COREPROCESSORS_PAGE_UNSUBSCRIBEFROMTAG:
		
		
			$fontawesome = 'fa-hand-o-right';
			break;

		case POP_COREPROCESSORS_PAGE_RECOMMENDPOST:
		case POP_COREPROCESSORS_PAGE_UNRECOMMENDPOST:
		case POP_COREPROCESSORS_PAGE_RECOMMENDEDPOSTS:
		case POP_COREPROCESSORS_PAGE_RECOMMENDEDBY:

			// $fontawesome = 'fa-hand-o-right';
			$fontawesome = 'fa-thumbs-o-up';
			break;

		case POP_COREPROCESSORS_PAGE_UPVOTEPOST:
		case POP_COREPROCESSORS_PAGE_UNDOUPVOTEPOST:
		case POP_COREPROCESSORS_PAGE_UPVOTEDBY:

			$fontawesome = 'fa-thumbs-up';
			break;

		case POP_COREPROCESSORS_PAGE_DOWNVOTEPOST:
		case POP_COREPROCESSORS_PAGE_UNDODOWNVOTEPOST:
		case POP_COREPROCESSORS_PAGE_DOWNVOTEDBY:

			$fontawesome = 'fa-thumbs-down';
			break;

		case POP_COREPROCESSORS_PAGE_SETTINGS:

			$fontawesome = 'fa-gear';
			break;

		case POP_COREPROCESSORS_PAGE_MYPROFILE:

			$fontawesome = 'fa-user';
			break;

		case POP_COREPROCESSORS_PAGE_MYPREFERENCES:

			$fontawesome = 'fa-cog';
			break;

		case POP_COREPROCESSORS_PAGE_INVITENEWUSERS:

			$fontawesome = 'fa-user-plus';
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


/**---------------------------------------------------------------------------------------------------------------
 * Add the menu item object id to the class to pick it up with AAPL to paint again that element in the navigation
 * ---------------------------------------------------------------------------------------------------------------*/    
add_filter('nav_menu_css_class', 'gd_nav_menu_css_class_add_object_id', 10, 3);
function gd_nav_menu_css_class_add_object_id($classes, $item, $args) {
	
	// Add the object id
	$classes[] = 'menu-item-object-id-'.$item->object_id;
	
	return $classes;
}


// Priority 10000: it comes after block-contents.php function block_title_addmore (which adds a 'More' text before the title)
add_filter('the_title', 'gd_navigation_update_menu_item', 10000, 2);
function gd_navigation_update_menu_item($title, $post_id = null) {

	// 1. Only for pages
	// 2. This is used only if $post_id is passed along:
	// - wp_bootstrap_navwalker.php
	if ($post_id && get_post_type($post_id) == 'page') {

		if ($icon = gd_navigation_menu_item($post_id)) {

			return $icon.$title;	
		}
	}

	return $title;
}
function gd_navigation_menu_item($post_id, $html = true) {

	// Do not do anything while in the back-end
	// Otherwise, this code is actually added inside the Menu Item when creating the Menu
	if (is_admin()) {

		return '';
	}	

	return apply_filters('gd_navigation_menu_item_icon', '', $post_id, $html);
}