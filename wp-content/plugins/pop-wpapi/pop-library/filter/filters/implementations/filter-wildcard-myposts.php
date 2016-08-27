<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Filter Articles
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_FILTER_WILDCARDMYPOSTS', 'mycontent');

class GD_Filter_WildcardMyPosts extends GD_Filter {

	protected function get_status_filtercomponent() {

		if (GD_CreateUpdate_Utils::moderate()) {
	
			global $gd_filtercomponent_moderatedstatus;
			return $gd_filtercomponent_moderatedstatus;
		}

		global $gd_filtercomponent_unmoderatedstatus;
		return $gd_filtercomponent_unmoderatedstatus;
	}

	function get_filtercomponents() {
	
		// The Filter Components below must be included by all Filters referencing "GD_Filter_WildcardPosts" as its wildcard filter
		global /*$gd_filtercomponent_moderatedstatus, */$gd_filtercomponent_search, $gd_filtercomponent_hashtags, /*$gd_filtercomponent_sections, $gd_filtercomponent_categories, */$gd_filtercomponent_orderpost;		
		$status = $this->get_status_filtercomponent();
		$ret = array($status, $gd_filtercomponent_search, $gd_filtercomponent_hashtags, /*$gd_filtercomponent_sections, $gd_filtercomponent_categories, */$gd_filtercomponent_orderpost);
		
		// Allow to add appliest_to filtercomponent
		$ret = apply_filters('gd_template:filter-wildcardmyposts:filtercomponents', $ret);
		$ret = apply_filters('gd_template:filter-myposts:filtercomponents', $ret);
		return $ret;
	}

	// function get_wildcard_filter() {
	
	// 	return GD_FILTER_WILDCARD;
	// }
	
	function get_name() {
	
		return GD_FILTER_WILDCARDMYPOSTS;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
// $gd_filter_wildcardmyposts = new GD_Filter_WildcardMyPosts();		
new GD_Filter_WildcardMyPosts();		
