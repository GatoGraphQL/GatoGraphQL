<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Filter Action
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_FILTER_TAGSTORIES', 'tagstories');

class GD_Filter_TagStories extends GD_FilterTagPosts {

	function get_filtercomponents() {
	
		global $gd_filtercomponent_search, /*$gd_filtercomponent_references, *//*$gd_filtercomponent_categories, */$gd_filtercomponent_profiles, $gd_filtercomponent_orderpost;		
		$ret = array($gd_filtercomponent_search, /*$gd_filtercomponent_references, *//*$gd_filtercomponent_categories, */$gd_filtercomponent_profiles, $gd_filtercomponent_orderpost);
		// if (PoPTheme_Wassup_Utils::add_appliesto()) {
		// 	global $gd_filtercomponent_appliesto;
		// 	array_splice($ret, array_search($gd_filtercomponent_categories, $ret)+1, 0, array($gd_filtercomponent_appliesto));
		// }
		$ret = apply_filters('gd_template:filter-tagstories:filtercomponents', $ret);
		$ret = apply_filters('gd_template:filter-tagposts:filtercomponents', $ret);
		$ret = apply_filters('gd_template:filter-stories:filtercomponents', $ret);
		$ret = apply_filters('gd_template:filter-posts:filtercomponents', $ret);
		return $ret;
	}
	
	function get_name() {
	
		return GD_FILTER_TAGSTORIES;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Filter_TagStories();		
