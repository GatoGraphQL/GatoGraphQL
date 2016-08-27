<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Filter LinkCategories
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_FilterComponent_LinkCategories extends GD_FilterComponent_Metaquery_Post {
		
	function get_filterformcomponent() {
	
		return GD_TEMPLATE_FILTERFORMCOMPONENT_LINKCATEGORIES;
	}

	function get_formcomponent() {
	
		return GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_LINKCATEGORIES;
	}

	function get_meta_key() {
	
		return GD_METAKEY_POST_LINKCATEGORIES;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
global $gd_filtercomponent_linkcategories;
$gd_filtercomponent_linkcategories = new GD_FilterComponent_LinkCategories();