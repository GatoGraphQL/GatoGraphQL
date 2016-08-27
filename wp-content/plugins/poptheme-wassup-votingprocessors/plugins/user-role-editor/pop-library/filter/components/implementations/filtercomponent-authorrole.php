<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Filter Name
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_URE_FilterComponent_AuthorRole extends GD_FilterComponent_Metaquery_Post {
	
	function get_filterformcomponent() {
	
		return GD_TEMPLATE_FILTERFORMCOMPONENT_AUTHORROLE_MULTISELECT;
	}
	
	function get_formcomponent() {
	
		return GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_AUTHORROLE_MULTISELECT;
	}

	function get_meta_key() {
	
		return GD_URE_METAKEY_POST_AUTHORROLE;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
global $gd_ure_filtercomponent_authorrole;
$gd_ure_filtercomponent_authorrole = new GD_URE_FilterComponent_AuthorRole();