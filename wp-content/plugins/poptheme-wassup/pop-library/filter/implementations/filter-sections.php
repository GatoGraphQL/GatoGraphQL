<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Filter Categories
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_FILTER_SECTIONS', 'sections');

class GD_Filter_Sections extends GD_FilterPosts {

	function get_filtercomponents() {
	
		global $gd_filtercomponent_sectionsbtngroup;
		$ret = array($gd_filtercomponent_sectionsbtngroup);
		return $ret;
	}
	
	function get_name() {
	
		return GD_FILTER_SECTIONS;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Filter_Sections();		
