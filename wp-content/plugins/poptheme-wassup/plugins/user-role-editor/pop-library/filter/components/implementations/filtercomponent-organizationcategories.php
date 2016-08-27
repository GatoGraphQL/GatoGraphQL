<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Filter OrganizationCategories
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_FilterComponent_OrganizationCategories extends GD_FilterComponent_Metaquery_User {
	
	function get_filterformcomponent() {
	
		return GD_URE_TEMPLATE_FILTERFORMCOMPONENT_ORGANIZATIONCATEGORIES;
	}

	function get_formcomponent() {
	
		return GD_URE_TEMPLATE_FILTERFORMCOMPONENTGROUP_ORGANIZATIONCATEGORIES;
	}

	function get_meta_key() {
	
		return GD_URE_METAKEY_PROFILE_ORGANIZATIONCATEGORIES;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
global $gd_ure_filtercomponent_organizationcategories;
$gd_ure_filtercomponent_organizationcategories = new GD_FilterComponent_OrganizationCategories();