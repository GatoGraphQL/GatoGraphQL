<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Filter Action
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_FILTER_AUTHOROPINIONATEDVOTES_STANCE', 'authoropinionatedvotes-stance');

class GD_Filter_AuthorStanceOpinionatedVoteds extends GD_FilterAuthorPosts {

	function get_filtercomponents() {
	
		global $gd_filtercomponent_search, $gd_filtercomponent_references, $gd_filtercomponent_daterangepicker, $gd_filtercomponent_orderpost;
		$ret = array($gd_filtercomponent_search, $gd_filtercomponent_references, $gd_filtercomponent_daterangepicker, $gd_filtercomponent_orderpost);
		$ret = apply_filters('gd_template:filter-authoropinionatedvotes-stance:filtercomponents', $ret);
		$ret = apply_filters('gd_template:filter-authorposts:filtercomponents', $ret);
		return $ret;
	}
	
	function get_name() {
	
		return GD_FILTER_AUTHOROPINIONATEDVOTES_STANCE;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Filter_AuthorStanceOpinionatedVoteds();		
