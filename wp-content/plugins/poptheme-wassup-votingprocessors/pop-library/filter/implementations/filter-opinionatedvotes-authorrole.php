<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Filter Action
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_FILTER_OPINIONATEDVOTES_AUTHORROLE', 'opinionatedvotes-authorrole');

class GD_Filter_AuthorRoleOpinionatedVoteds extends GD_FilterPosts {

	function get_filtercomponents() {
	
		global $gd_filtercomponent_search, $gd_filtercomponent_stance, $gd_filtercomponent_references, $gd_filtercomponent_profiles, $gd_filtercomponent_daterangepicker, $gd_filtercomponent_orderpost;
		$ret = array($gd_filtercomponent_search, $gd_filtercomponent_stance, $gd_filtercomponent_references, $gd_filtercomponent_profiles, $gd_filtercomponent_daterangepicker, $gd_filtercomponent_orderpost);
		$ret = apply_filters('gd_template:filter-opinionatedvotes-authorrole:filtercomponents', $ret);
		$ret = apply_filters('gd_template:filter-posts:filtercomponents', $ret);
		return $ret;
	}
	
	function get_name() {
	
		return GD_FILTER_OPINIONATEDVOTES_AUTHORROLE;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Filter_AuthorRoleOpinionatedVoteds();		
