<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Filter Events
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_FILTER_TAGEVENTS', 'tagevents');

class GD_Filter_TagEvents extends GD_Filter_TagEventsBase {

	function get_filtercomponents() {
	
		global $gd_filtercomponent_search, /*$gd_filtercomponent_categories, */$gd_filtercomponent_daterangepicker, $gd_filtercomponent_profiles;
		$ret = array($gd_filtercomponent_search, /*$gd_filtercomponent_categories, */$gd_filtercomponent_daterangepicker, $gd_filtercomponent_profiles);
		$ret = apply_filters('gd_template:filter-tagevents:filtercomponents', $ret);
		$ret = apply_filters('gd_template:filter-tagposts:filtercomponents', $ret);
		$ret = apply_filters('gd_template:filter-events:filtercomponents', $ret);
		$ret = apply_filters('gd_template:filter-posts:filtercomponents', $ret);
		return $ret;
	}
	
	function get_name() {
	
		return GD_FILTER_TAGEVENTS;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Filter_TagEvents();		
