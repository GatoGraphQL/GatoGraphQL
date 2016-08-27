<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Filter Events
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Filter_AuthorEventsBase extends GD_Filter_EventsRoot {
		
	function get_wildcard_filter() {
	
		// global $gd_filter_authorwildcardposts;
		// return $gd_filter_authorwildcardposts;
		return GD_FILTER_AUTHORWILDCARDPOSTS;
	}
}
