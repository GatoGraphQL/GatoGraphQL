<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Filter LinkCategories
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_FilterComponent_CategoriesBtnGroup extends GD_FilterComponent_Metaquery_Post {
		
	function get_filterformcomponent() {
	
		return GD_TEMPLATE_FILTERFORMCOMPONENT_BUTTONGROUP_CATEGORIES;
	}

	function get_formcomponent() {
	
		return GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_BUTTONGROUP_CATEGORIES;
	}

	function get_meta_key() {
	
		return GD_METAKEY_POST_CATEGORIES;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
global $gd_filtercomponent_categoriesbtngroup;
$gd_filtercomponent_categoriesbtngroup = new GD_FilterComponent_CategoriesBtnGroup();