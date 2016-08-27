<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Filter Action
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_FILTER_AUTHORHIGHLIGHTS', 'authorhighlights');

class GD_Filter_AuthorHighlights extends GD_FilterAuthorPosts {

	function get_filtercomponents() {
	
		global $gd_filtercomponent_search, $gd_filtercomponent_references, $gd_filtercomponent_daterangepicker, $gd_filtercomponent_orderpost;
		$ret = array($gd_filtercomponent_search, $gd_filtercomponent_references, $gd_filtercomponent_daterangepicker, $gd_filtercomponent_orderpost);
		$ret = apply_filters('gd_template:filter-authorhighlights:filtercomponents', $ret);
		$ret = apply_filters('gd_template:filter-authorposts:filtercomponents', $ret);
		return $ret;
	}
	
	function get_name() {
	
		return GD_FILTER_AUTHORHIGHLIGHTS;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Filter_AuthorHighlights();		
