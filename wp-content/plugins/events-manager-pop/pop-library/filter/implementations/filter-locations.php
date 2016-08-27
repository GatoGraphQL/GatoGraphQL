<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Filter Announcements
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_FILTER_LOCATIONS', 'locations');

class GD_Filter_Locations extends GD_Filter_LocationsBase {

	function get_filtercomponents() {
	
		global $gd_filtercomponent_search;
		$ret = array($gd_filtercomponent_search);
		$ret = apply_filters('gd_template:filter-locations:filtercomponents', $ret);
		// $ret = apply_filters('gd_template:filter-posts:filtercomponents', $ret);
		return $ret;
	}
	
	function get_name() {
	
		return GD_FILTER_LOCATIONS;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
// $gd_filter_locations = new GD_Filter_Locations();		
new GD_Filter_Locations();		
