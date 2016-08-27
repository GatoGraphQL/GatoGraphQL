<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Filter Articles
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_FILTER_WILDCARDTAGS', 'tags');

class GD_Filter_WildcardTags extends GD_TagFilter {

	function get_filtercomponents() {
	
		// The Filter Components below must be included by all Filters referencing "GD_Filter_WildcardTags" as its wildcard filter
		global $gd_filtercomponent_search, $gd_filtercomponent_ordertag;		
		$ret = array($gd_filtercomponent_search, $gd_filtercomponent_ordertag);
		$ret = apply_filters('gd_template:filter-wildcardtags:filtercomponents', $ret);
		$ret = apply_filters('gd_template:filter-tags:filtercomponents', $ret);
		return $ret;
	}

	// function get_wildcard_filter() {
	
	// 	return GD_FILTER_WILDCARD;
	// }
	
	function get_name() {
	
		return GD_FILTER_WILDCARDTAGS;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Filter_WildcardTags();		
