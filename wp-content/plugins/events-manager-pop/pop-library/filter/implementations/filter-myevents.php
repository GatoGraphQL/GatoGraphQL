<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Filter Events
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_FILTER_MYEVENTS', 'myevents');

class GD_Filter_MyEvents extends GD_Filter_MyEventsBase {

	function get_filtercomponents() {
	
		// Events: cannot filter by categories, since em_get_events() has no support for meta_query
		// Events: cannot filter by tags, since using arg "tag" searchs for its own post type for event tag, and not the standard post tag
		global $gd_filtercomponent_moderatedstatus, $gd_filtercomponent_search, /*$gd_filtercomponent_hashtags, $gd_filtercomponent_categories, */$gd_filtercomponent_daterangepicker;		
		$ret = array($gd_filtercomponent_moderatedstatus, $gd_filtercomponent_search, /*$gd_filtercomponent_hashtags, $gd_filtercomponent_categories, */$gd_filtercomponent_daterangepicker);
		$ret = apply_filters('gd_template:filter-myevents:filtercomponents', $ret);
		$ret = apply_filters('gd_template:filter-myposts:filtercomponents', $ret);
		return $ret;
	}
	
	function get_name() {
	
		return GD_FILTER_MYEVENTS;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
// $gd_filter_myevents = new GD_Filter_MyEvents();		
new GD_Filter_MyEvents();		
