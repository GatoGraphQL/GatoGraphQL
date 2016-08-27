<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Filter ArticleCategories
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_FilterComponent_DiscussionCategories extends GD_FilterComponent_Metaquery_Post {
		
	function get_filterformcomponent() {
	
		return GD_TEMPLATE_FILTERFORMCOMPONENT_DISCUSSIONCATEGORIES;
	}

	function get_formcomponent() {
	
		return GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_DISCUSSIONCATEGORIES;
	}

	function get_meta_key() {
	
		return GD_METAKEY_POST_DISCUSSIONCATEGORIES;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
global $gd_filtercomponent_discussioncategories;
$gd_filtercomponent_discussioncategories = new GD_FilterComponent_DiscussionCategories();