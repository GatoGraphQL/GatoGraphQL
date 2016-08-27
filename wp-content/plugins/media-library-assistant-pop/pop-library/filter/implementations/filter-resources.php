<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Filter Announcements
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_FILTER_RESOURCES', 'resources');

class GD_Filter_Resources extends GD_Filter_MediaBase {

	function get_filtercomponents() {
	
		global $gd_filtercomponent_search, $gd_filtercomponent_mimetype, $gd_filtercomponent_profiles;		
		$ret = array($gd_filtercomponent_search, $gd_filtercomponent_mimetype, $gd_filtercomponent_profiles);
		$ret = apply_filters('gd_template:filter-resources:filtercomponents', $ret);
		$ret = apply_filters('gd_template:filter-posts:filtercomponents', $ret);
		return $ret;
	}
	
	function get_media_filtercomponents() {
	
		global $gd_filtercomponent_mimetype;		
		return array($gd_filtercomponent_mimetype);
	}
	
	function get_name() {
	
		return GD_FILTER_RESOURCES;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
// $gd_filter_resources = new GD_Filter_Resources();		
new GD_Filter_Resources();		
