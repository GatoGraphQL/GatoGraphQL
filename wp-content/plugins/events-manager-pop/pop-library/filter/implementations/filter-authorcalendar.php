<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Filter Events
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_FILTER_AUTHOREVENTSCALENDAR', 'authorcalendar');

class GD_Filter_AuthorEventsCalendar extends GD_Filter_AuthorEventsBase {

	function get_filtercomponents() {
	
		// Events: cannot filter by categories, since em_get_events() has no support for meta_query
		// Events: cannot filter by tags, since using arg "tag" searchs for its own post type for event tag, and not the standard post tag
		global $gd_filtercomponent_search/*, $gd_filtercomponent_hashtags, $gd_filtercomponent_categories*/;		
		$ret = array($gd_filtercomponent_search/*, $gd_filtercomponent_hashtags, $gd_filtercomponent_categories*/);
		$ret = apply_filters('gd_template:filter-authoreventscalendar:filtercomponents', $ret);
		$ret = apply_filters('gd_template:filter-authorposts:filtercomponents', $ret);
		return $ret;
	}
	
	function get_name() {
	
		return GD_FILTER_AUTHOREVENTSCALENDAR;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
// $gd_filter_authoreventscalendar = new GD_Filter_AuthorEventsCalendar();		
new GD_Filter_AuthorEventsCalendar();		
