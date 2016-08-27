<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Filter Announcements
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Filter_MyMediaBase extends GD_Filter_MediaRoot {

	function get_wildcard_filter() {
	
		// global $gd_filter_wildcardmyposts;
		// return $gd_filter_wildcardmyposts;
		return GD_FILTER_WILDCARDMYPOSTS;
	}
}
