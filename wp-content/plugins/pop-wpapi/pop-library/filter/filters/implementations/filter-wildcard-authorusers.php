<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Filter Articles
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_FILTER_AUTHORWILDCARDUSERS', 'authorprofiles');

class GD_Filter_AuthorWildcardUsers extends GD_Filter {

	function get_filtercomponents() {
	
		// The Filter Components below must be included by all Filters referencing "GD_Filter_WildcardUsers" as its wildcard filter
		global $gd_filtercomponent_name, $gd_filtercomponent_orderuser;		
		$ret = array($gd_filtercomponent_name, $gd_filtercomponent_orderuser);
		$ret = apply_filters('gd_template:filter-authorwildcardusers:filtercomponents', $ret);
		$ret = apply_filters('gd_template:filter-authorusers:filtercomponents', $ret);
		return $ret;
	}

	// function get_wildcard_filter() {
	
	// 	return GD_FILTER_WILDCARD;
	// }
	
	function get_name() {
	
		return GD_FILTER_AUTHORWILDCARDUSERS;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
// $gd_filter_authorwildcardusers = new GD_Filter_AuthorWildcardUsers();		
new GD_Filter_AuthorWildcardUsers();		
