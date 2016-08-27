<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Filter Articles
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_FILTER_AUTHORWILDCARDPOSTS', 'authorcontent');

class GD_Filter_AuthorWildcardPosts extends GD_Filter {

	function get_filtercomponents() {
	
		// The Filter Components below must be included by all Filters referencing "GD_Filter_WildcardPosts" as its wildcard filter
		global $gd_filtercomponent_search, $gd_filtercomponent_hashtags, /*$gd_filtercomponent_sections, $gd_filtercomponent_categories, */$gd_filtercomponent_orderpost;		
		$ret = array($gd_filtercomponent_search, $gd_filtercomponent_hashtags, /*$gd_filtercomponent_sections, $gd_filtercomponent_categories, */$gd_filtercomponent_orderpost);

		// Allow to add appliest_to filtercomponent
		$ret = apply_filters('gd_template:filter-authorwildcardposts:filtercomponents', $ret);
		$ret = apply_filters('gd_template:filter-authorposts:filtercomponents', $ret);
		return $ret;
	}

	// function get_wildcard_filter() {
	
	// 	return GD_FILTER_WILDCARD;
	// }
	
	function get_name() {
	
		return GD_FILTER_AUTHORWILDCARDPOSTS;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
// $gd_filter_authorwildcardposts = new GD_Filter_AuthorWildcardPosts();		
new GD_Filter_AuthorWildcardPosts();		
