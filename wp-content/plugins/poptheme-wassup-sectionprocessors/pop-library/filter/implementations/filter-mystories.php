<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Filter Action
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_FILTER_MYSTORIES', 'mystories');

class GD_Filter_MyStories extends GD_FilterMyPosts {

	function get_filtercomponents() {
	
		global /*$gd_filtercomponent_moderatedstatus, */$gd_filtercomponent_search, $gd_filtercomponent_hashtags, /*$gd_filtercomponent_references, *//*$gd_filtercomponent_categories, */$gd_filtercomponent_orderpost /*,$gd_filtercomponent_projects, $gd_filtercomponent_events*/;		
		$status = $this->get_status_filtercomponent();
		$ret = array($status, $gd_filtercomponent_search, $gd_filtercomponent_hashtags, /*$gd_filtercomponent_references, *//*$gd_filtercomponent_categories, */$gd_filtercomponent_orderpost /*, $gd_filtercomponent_projects, $gd_filtercomponent_events*/);
		// if (PoPTheme_Wassup_Utils::add_appliesto()) {
		// 	global $gd_filtercomponent_appliesto;
		// 	array_splice($ret, array_search($gd_filtercomponent_categories, $ret)+1, 0, array($gd_filtercomponent_appliesto));
		// }
		$ret = apply_filters('gd_template:filter-mystories:filtercomponents', $ret);
		$ret = apply_filters('gd_template:filter-myposts:filtercomponents', $ret);
		return $ret;
	}
	
	function get_name() {
	
		return GD_FILTER_MYSTORIES;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
// $gd_filter_mystories = new GD_Filter_MyStories();		
new GD_Filter_MyStories();		
