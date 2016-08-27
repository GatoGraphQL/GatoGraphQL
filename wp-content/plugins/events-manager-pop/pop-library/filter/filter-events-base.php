<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Filter Events
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Filter_EventsBase extends GD_Filter_EventsRoot {
		
	function get_wildcard_filter() {
	
		// global $gd_filter_wildcardposts;
		// return $gd_filter_wildcardposts;
		return GD_FILTER_WILDCARDPOSTS;
	}
}
