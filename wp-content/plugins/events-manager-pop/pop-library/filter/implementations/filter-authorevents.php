<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Filter Events
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_FILTER_AUTHOREVENTS', 'authorevents');

class GD_Filter_AuthorEvents extends GD_Filter_AuthorEventsBase {

	function get_filtercomponents() {
	
		global $gd_filtercomponent_search, /*$gd_filtercomponent_hashtags, $gd_filtercomponent_categories, */$gd_filtercomponent_daterangepicker;
		$ret = array($gd_filtercomponent_search, /*$gd_filtercomponent_hashtags, $gd_filtercomponent_categories, */$gd_filtercomponent_daterangepicker);
		$ret = apply_filters('gd_template:filter-authorevents:filtercomponents', $ret);
		$ret = apply_filters('gd_template:filter-authorposts:filtercomponents', $ret);
		return $ret;
	}
	
	function get_name() {
	
		return GD_FILTER_AUTHOREVENTS;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
// $gd_filter_authorevents = new GD_Filter_AuthorEvents();		
new GD_Filter_AuthorEvents();		
