<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Filter Articles
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_FILTER_TAGWILDCARDPOSTS', 'tagcontent');

class GD_Filter_TagWildcardPosts extends GD_Filter {

	function get_filtercomponents() {
	
		// The Filter Components below must be included by all Filters referencing "GD_Filter_WildcardPosts" as its wildcard filter
		global $gd_filtercomponent_search, /*$gd_filtercomponent_sections, $gd_filtercomponent_categories, */$gd_filtercomponent_profiles, $gd_filtercomponent_orderpost;		
		$ret = array($gd_filtercomponent_search, /*$gd_filtercomponent_sections, $gd_filtercomponent_categories, */$gd_filtercomponent_profiles, $gd_filtercomponent_orderpost);
		
		// Allow to add appliest_to filtercomponent
		$ret = apply_filters('gd_template:filter-tagwildcardposts:filtercomponents', $ret);
		$ret = apply_filters('gd_template:filter-tagposts:filtercomponents', $ret);
		$ret = apply_filters('gd_template:filter-wildcardposts:filtercomponents', $ret);
		$ret = apply_filters('gd_template:filter-posts:filtercomponents', $ret);
		return $ret;
	}

	// function get_wildcard_filter() {
	
	// 	return GD_FILTER_WILDCARD;
	// }
	
	function get_name() {
	
		return GD_FILTER_TAGWILDCARDPOSTS;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Filter_TagWildcardPosts();		
