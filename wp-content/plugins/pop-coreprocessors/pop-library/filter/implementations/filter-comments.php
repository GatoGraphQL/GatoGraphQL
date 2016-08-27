<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Filter Comments
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_FILTER_COMMENTS', 'comments');

class GD_Filter_Comments extends GD_Filter_CommentsBase {

	function get_filtercomponents() {
	
		global $gd_filtercomponent_search, $gd_filtercomponent_profiles;		
		return array($gd_filtercomponent_search, $gd_filtercomponent_profiles);
	}
	
	function get_name() {
	
		return GD_FILTER_COMMENTS;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
// $gd_filter_comments = new GD_Filter_Comments();		
new GD_Filter_Comments();		
