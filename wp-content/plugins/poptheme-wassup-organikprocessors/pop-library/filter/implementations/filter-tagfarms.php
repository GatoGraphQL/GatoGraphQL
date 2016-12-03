<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Filter Action
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_FILTER_TAGFARMS', 'tagfarms');

class GD_Filter_TagFarms extends GD_FilterTagPosts {

	function get_filtercomponents() {
	
		global $gd_filtercomponent_search, /*$gd_filtercomponent_farmcategories, *//*$gd_filtercomponent_categories, */$gd_filtercomponent_profiles, /*$gd_filtercomponent_volunteersneeded, */$gd_filtercomponent_orderpost;
		$ret = array($gd_filtercomponent_search, /*$gd_filtercomponent_categories, */$gd_filtercomponent_profiles, /*$gd_filtercomponent_volunteersneeded, */$gd_filtercomponent_orderpost);
		// if (PoPTheme_Wassup_Utils::add_appliesto()) {
		// 	global $gd_filtercomponent_appliesto;
		// 	array_splice($ret, array_search($gd_filtercomponent_categories, $ret)+1, 0, array($gd_filtercomponent_appliesto));
		// }
		$ret = apply_filters('gd_template:filter-tagfarms:filtercomponents', $ret);
		$ret = apply_filters('gd_template:filter-tagposts:filtercomponents', $ret);
		$ret = apply_filters('gd_template:filter-farms:filtercomponents', $ret);
		$ret = apply_filters('gd_template:filter-posts:filtercomponents', $ret);
		$ret = apply_filters('gd_template:filter:filtercomponents:maybevolunteersneeded', $ret);
		return $ret;
	}
	
	function get_name() {
	
		return GD_FILTER_TAGFARMS;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Filter_TagFarms();		
