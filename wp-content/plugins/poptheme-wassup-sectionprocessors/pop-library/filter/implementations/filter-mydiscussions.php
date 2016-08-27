<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Filter Articles
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_FILTER_MYDISCUSSIONS', 'mydiscussions');

class GD_Filter_MyDiscussions extends GD_FilterMyPosts {

	function get_filtercomponents() {
	
		global /*$gd_filtercomponent_moderatedstatus, */$gd_filtercomponent_search, $gd_filtercomponent_hashtags, /*$gd_filtercomponent_discussioncategories, *//*$gd_filtercomponent_categories, */$gd_filtercomponent_daterangepicker, /*$gd_filtercomponent_references, */$gd_filtercomponent_orderpost;
		$status = $this->get_status_filtercomponent();
		$ret = array($status, $gd_filtercomponent_search, $gd_filtercomponent_hashtags, /*$gd_filtercomponent_categories, */$gd_filtercomponent_daterangepicker, /*$gd_filtercomponent_references, */$gd_filtercomponent_orderpost);
		// if (PoPTheme_Wassup_Utils::add_appliesto()) {
		// 	global $gd_filtercomponent_appliesto;
		// 	array_splice($ret, array_search($gd_filtercomponent_categories, $ret)+1, 0, array($gd_filtercomponent_appliesto));
		// }
		$ret = apply_filters('gd_template:filter-mydiscussions:filtercomponents', $ret);
		$ret = apply_filters('gd_template:filter-myposts:filtercomponents', $ret);
		return $ret;
	}
	
	function get_name() {
	
		return GD_FILTER_MYDISCUSSIONS;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
// $gd_filter_mydiscussions = new GD_Filter_MyDiscussions();		
new GD_Filter_MyDiscussions();		
