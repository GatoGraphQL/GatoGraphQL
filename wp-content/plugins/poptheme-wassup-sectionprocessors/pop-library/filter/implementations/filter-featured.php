<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Filter Featured
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_FILTER_FEATURED', 'featured');

class GD_Filter_Featured extends GD_FilterPosts {

	function get_filtercomponents() {
	
		global $gd_filtercomponent_search, $gd_filtercomponent_hashtags, /*$gd_filtercomponent_categories, */$gd_filtercomponent_daterangepicker, $gd_filtercomponent_profiles, $gd_filtercomponent_orderpost;
		$ret = array($gd_filtercomponent_search, $gd_filtercomponent_hashtags, /*$gd_filtercomponent_categories, */$gd_filtercomponent_daterangepicker, $gd_filtercomponent_profiles, $gd_filtercomponent_orderpost);
		// if (PoPTheme_Wassup_Utils::add_appliesto()) {
		// 	global $gd_filtercomponent_appliesto;
		// 	array_splice($ret, array_search($gd_filtercomponent_categories, $ret)+1, 0, array($gd_filtercomponent_appliesto));
		// }
		$ret = apply_filters('gd_template:filter-featured:filtercomponents', $ret);
		$ret = apply_filters('gd_template:filter-posts:filtercomponents', $ret);
		return $ret;
	}
	
	function get_name() {
	
		return GD_FILTER_FEATURED;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
// $gd_filter_featured = new GD_Filter_Featured();		
new GD_Filter_Featured();		
