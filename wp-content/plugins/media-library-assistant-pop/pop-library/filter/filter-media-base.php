<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Filter Announcements
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Filter_MediaBase extends GD_Filter_MediaRoot {

	function get_wildcard_filter() {
	
		// global $gd_filter_wildcardposts;
		// return $gd_filter_wildcardposts;
		return GD_FILTER_WILDCARDPOSTS;
	}
}
