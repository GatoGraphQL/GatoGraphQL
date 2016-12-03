<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Filter ActionCategories
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_FilterComponent_LocationPostCategories extends GD_FilterComponent_Metaquery_Post {
		
	function get_filterformcomponent() {
	
		return GD_TEMPLATE_FILTERFORMCOMPONENT_LOCATIONPOSTCATEGORIES;
	}

	function get_formcomponent() {
	
		return GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_LOCATIONPOSTCATEGORIES;
	}

	function get_meta_key() {
	
		return GD_METAKEY_POST_LOCATIONPOSTCATEGORIES;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
global $gd_filtercomponent_locationpostcategories;
$gd_filtercomponent_locationpostcategories = new GD_FilterComponent_LocationPostCategories();