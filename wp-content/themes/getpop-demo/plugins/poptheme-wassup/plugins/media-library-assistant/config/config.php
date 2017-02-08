<?php

/**---------------------------------------------------------------------------------------------------------------
 * Custom Libraries
 * ---------------------------------------------------------------------------------------------------------------*/

/**---------------------------------------------------------------------------------------------------------------
 * core.php
 * ---------------------------------------------------------------------------------------------------------------*/
add_filter('gd_postname', 'gd_mla_postname_impl', 10, 2);
function gd_mla_postname_impl($name, $post_id) {

	if (get_post_type($post_id) == 'attachment') {
		
		return __('Resource', 'getpop-demo');
	}

	return $name;
}
add_filter('gd_posticon', 'gd_mla_posticon_impl', 10, 2);
function gd_mla_posticon_impl($icon, $post_id) {

	if (get_post_type($post_id) == 'attachment') {
		
		return gd_navigation_menu_item(POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_RESOURCES, false);
	}

	return $icon;
}