<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Filter Action
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_FILTER_HIGHLIGHTS', 'highlights');

class GD_Filter_Highlights extends GD_FilterPosts {

	function get_filtercomponents() {
	
		// No need for references, because the highlights are always shown under the Single Post, so then that is its reference
		global $gd_filtercomponent_search, /*$gd_filtercomponent_references, */$gd_filtercomponent_profiles, $gd_filtercomponent_daterangepicker, $gd_filtercomponent_orderpost;
		$ret = array($gd_filtercomponent_search, /*$gd_filtercomponent_references, */$gd_filtercomponent_profiles, $gd_filtercomponent_daterangepicker, $gd_filtercomponent_orderpost);
		$ret = apply_filters('gd_template:filter-highlights:filtercomponents', $ret);
		$ret = apply_filters('gd_template:filter-posts:filtercomponents', $ret);
		return $ret;
	}
	
	function get_name() {
	
		return GD_FILTER_HIGHLIGHTS;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Filter_Highlights();		
