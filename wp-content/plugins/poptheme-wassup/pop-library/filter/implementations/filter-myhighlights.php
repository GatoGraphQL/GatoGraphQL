<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Filter Action
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_FILTER_MYHIGHLIGHTS', 'myhighlights');

class GD_Filter_MyHighlights extends GD_FilterMyPosts {

	function get_filtercomponents() {
	
		global $gd_filtercomponent_unmoderatedstatus, $gd_filtercomponent_search, $gd_filtercomponent_references, $gd_filtercomponent_daterangepicker, $gd_filtercomponent_orderpost;		
		return array($gd_filtercomponent_unmoderatedstatus, $gd_filtercomponent_search, $gd_filtercomponent_references, $gd_filtercomponent_daterangepicker, $gd_filtercomponent_orderpost);
	}
	
	function get_name() {
	
		return GD_FILTER_MYHIGHLIGHTS;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Filter_MyHighlights();		
