<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Filter Events
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Filter_MyEventsBase extends GD_Filter_EventsRoot {
		
	function get_wildcard_filter() {
	
		// global $gd_filter_wildcardmyposts;
		// return $gd_filter_wildcardmyposts;
		return GD_FILTER_WILDCARDMYPOSTS;
	}
}
