<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Filter Action
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_FILTER_BLOG', 'blog');

class GD_Filter_Blog extends GD_FilterPosts {

	function get_filtercomponents() {
	
		global $gd_filtercomponent_search, $gd_filtercomponent_daterangepicker;		
		return array($gd_filtercomponent_search, $gd_filtercomponent_daterangepicker);
	}
	
	function get_name() {
	
		return GD_FILTER_BLOG;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
// $gd_filter_blog = new GD_Filter_Blog();		
new GD_Filter_Blog();		
