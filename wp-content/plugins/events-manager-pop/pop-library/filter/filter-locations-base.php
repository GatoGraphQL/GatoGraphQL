<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Filter Events
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Filter_LocationsBase extends GD_Filter_LocationsRoot {
		
	function get_wildcard_filter() {
	
		// global $gd_filter_wildcardposts;
		// return $gd_filter_wildcardposts;
		return GD_FILTER_WILDCARDPOSTS;
	}
}
