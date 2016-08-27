<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Filter Categories
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_FILTER_CATEGORIES', 'categories');

class GD_Filter_Categories extends GD_FilterPosts {

	function get_filtercomponents() {
	
		global $gd_filtercomponent_categoriesbtngroup;
		$ret = array($gd_filtercomponent_categoriesbtngroup);
		return $ret;
	}
	
	function get_name() {
	
		return GD_FILTER_CATEGORIES;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Filter_Categories();		
