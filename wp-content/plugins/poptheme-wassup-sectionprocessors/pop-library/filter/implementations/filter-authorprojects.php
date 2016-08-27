<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Filter Action
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_FILTER_AUTHORPROJECTS', 'authorprojects');

class GD_Filter_AuthorProjects extends GD_FilterAuthorPosts {

	function get_filtercomponents() {
	
		global $gd_filtercomponent_search, $gd_filtercomponent_hashtags, /*$gd_filtercomponent_projectcategories, *//*$gd_filtercomponent_categories, */$gd_filtercomponent_volunteersneeded, $gd_filtercomponent_orderpost;
		$ret = array($gd_filtercomponent_search, $gd_filtercomponent_hashtags, /*$gd_filtercomponent_categories, */$gd_filtercomponent_volunteersneeded, $gd_filtercomponent_orderpost);
		// if (PoPTheme_Wassup_Utils::add_appliesto()) {
		// 	global $gd_filtercomponent_appliesto;
		// 	array_splice($ret, array_search($gd_filtercomponent_categories, $ret)+1, 0, array($gd_filtercomponent_appliesto));
		// }
		$ret = apply_filters('gd_template:filter-authorprojects:filtercomponents', $ret);
		$ret = apply_filters('gd_template:filter-authorposts:filtercomponents', $ret);
		return $ret;
	}
	
	function get_name() {
	
		return GD_FILTER_AUTHORPROJECTS;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
// $gd_filter_authorprojects = new GD_Filter_AuthorProjects();		
new GD_Filter_AuthorProjects();		
