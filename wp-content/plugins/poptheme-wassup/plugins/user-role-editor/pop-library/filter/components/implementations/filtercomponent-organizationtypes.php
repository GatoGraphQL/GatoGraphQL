<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Filter OrganizationTypes
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_FilterComponent_OrganizationTypes extends GD_FilterComponent_Metaquery_User {
	
	function get_filterformcomponent() {
	
		return GD_URE_TEMPLATE_FILTERFORMCOMPONENT_ORGANIZATIONTYPES;
	}

	function get_formcomponent() {
	
		return GD_URE_TEMPLATE_FILTERFORMCOMPONENTGROUP_ORGANIZATIONTYPES;
	}

	function get_meta_key() {
	
		return GD_URE_METAKEY_PROFILE_ORGANIZATIONTYPES;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
global $gd_ure_filtercomponent_organizationtypes;
$gd_ure_filtercomponent_organizationtypes = new GD_FilterComponent_OrganizationTypes();