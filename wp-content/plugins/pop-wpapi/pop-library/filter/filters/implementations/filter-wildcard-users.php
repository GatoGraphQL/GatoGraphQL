<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Filter Articles
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_FILTER_WILDCARDUSERS', 'users');

class GD_Filter_WildcardUsers extends GD_Filter {

	function get_filtercomponents() {
	
		// The Filter Components below must be included by all Filters referencing "GD_Filter_WildcardUsers" as its wildcard filter
		global $gd_filtercomponent_name /*, $gd_ure_filtercomponent_communities_user*/, $gd_filtercomponent_orderuser;		
		$ret = array($gd_filtercomponent_name /*, $gd_ure_filtercomponent_communities_user*/, $gd_filtercomponent_orderuser);
		$ret = apply_filters('gd_template:filter-wildcardusers:filtercomponents', $ret);
		$ret = apply_filters('gd_template:filter-users:filtercomponents', $ret);
		return $ret;
	}

	// function get_wildcard_filter() {
	
	// 	return GD_FILTER_WILDCARD;
	// }
	
	function get_name() {
	
		return GD_FILTER_WILDCARDUSERS;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
// $gd_filter_wildcardusers = new GD_Filter_WildcardUsers();		
new GD_Filter_WildcardUsers();		
