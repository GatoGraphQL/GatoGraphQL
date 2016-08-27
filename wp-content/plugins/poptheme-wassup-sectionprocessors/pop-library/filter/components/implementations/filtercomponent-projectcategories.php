<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Filter ActionCategories
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_FilterComponent_ProjectCategories extends GD_FilterComponent_Metaquery_Post {
		
	function get_filterformcomponent() {
	
		return GD_TEMPLATE_FILTERFORMCOMPONENT_PROJECTCATEGORIES;
	}

	function get_formcomponent() {
	
		return GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_PROJECTCATEGORIES;
	}

	function get_meta_key() {
	
		return GD_METAKEY_POST_PROJECTCATEGORIES;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
global $gd_filtercomponent_projectcategories;
$gd_filtercomponent_projectcategories = new GD_FilterComponent_ProjectCategories();