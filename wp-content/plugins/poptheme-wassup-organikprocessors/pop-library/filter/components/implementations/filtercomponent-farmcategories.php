<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Filter ActionCategories
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_FilterComponent_FarmCategories extends GD_FilterComponent_Metaquery_Post {
		
	function get_filterformcomponent() {
	
		return GD_TEMPLATE_FILTERFORMCOMPONENT_FARMCATEGORIES;
	}

	function get_formcomponent() {
	
		return GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_FARMCATEGORIES;
	}

	function get_meta_key() {
	
		return GD_METAKEY_POST_FARMCATEGORIES;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
global $gd_filtercomponent_farmcategories;
$gd_filtercomponent_farmcategories = new GD_FilterComponent_FarmCategories();