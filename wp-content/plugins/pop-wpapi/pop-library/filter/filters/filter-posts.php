<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Filter
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_FilterPosts extends GD_Filter {

	function get_wildcard_filter() {
	
		// global $gd_filter_wildcardposts;
		// return $gd_filter_wildcardposts;
		return GD_FILTER_WILDCARDPOSTS;
	}
}
