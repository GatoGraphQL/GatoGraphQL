<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Filter Categories
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_FILTER_WEBPOSTSECTIONS', 'postsections');

class GD_Filter_WebPostSections extends GD_FilterPosts {

	function get_filtercomponents() {
	
		global $gd_filtercomponent_webpostsectionsbtngroup;
		$ret = array($gd_filtercomponent_webpostsectionsbtngroup);
		return $ret;
	}
	
	function get_name() {
	
		return GD_FILTER_WEBPOSTSECTIONS;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Filter_WebPostSections();		
