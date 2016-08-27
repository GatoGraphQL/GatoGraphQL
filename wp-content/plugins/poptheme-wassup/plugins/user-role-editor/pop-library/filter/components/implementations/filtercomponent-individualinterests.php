<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Filter ArticleCategories
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_FilterComponent_IndividualInterests extends GD_FilterComponent_Metaquery_User {
		
	function get_filterformcomponent() {
	
		return GD_URE_TEMPLATE_FILTERFORMCOMPONENT_INDIVIDUALINTERESTS;
	}

	function get_formcomponent() {
	
		return GD_URE_TEMPLATE_FILTERFORMCOMPONENTGROUP_INDIVIDUALINTERESTS;
	}

	function get_meta_key() {
	
		return GD_URE_METAKEY_PROFILE_INDIVIDUALINTERESTS;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
global $gd_ure_filtercomponent_individualinterests;
$gd_ure_filtercomponent_individualinterests = new GD_FilterComponent_IndividualInterests();