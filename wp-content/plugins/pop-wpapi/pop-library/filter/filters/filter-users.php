<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Filter
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_FilterUsers extends GD_Filter {

	function get_wildcard_filter() {
	
		// global $gd_filter_wildcardusers;
		// return $gd_filter_wildcardusers;
		return GD_FILTER_WILDCARDUSERS;
	}
}
