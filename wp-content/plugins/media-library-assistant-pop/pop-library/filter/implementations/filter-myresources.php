<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Filter Announcements
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_FILTER_MYRESOURCES', 'myresources');

class GD_Filter_MyResources extends GD_Filter_MyMediaBase {

	function get_filtercomponents() {
	
		global $gd_filtercomponent_search, $gd_filtercomponent_mimetype, $gd_filtercomponent_taxonomy;		
		$ret = array($gd_filtercomponent_search, $gd_filtercomponent_mimetype, $gd_filtercomponent_taxonomy);
		$ret = apply_filters('gd_template:filter-myresources:filtercomponents', $ret);
		$ret = apply_filters('gd_template:filter-myposts:filtercomponents', $ret);
		return $ret;
	}
	
	function get_media_filtercomponents() {
	
		global $gd_filtercomponent_mimetype, $gd_filtercomponent_taxonomy;		
		return array($gd_filtercomponent_mimetype, $gd_filtercomponent_taxonomy);
	}
	
	function get_name() {
	
		return GD_FILTER_MYRESOURCES;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
// $gd_filter_myresources = new GD_Filter_MyResources();		
new GD_Filter_MyResources();		
