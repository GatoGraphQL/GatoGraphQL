<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Filter Profiles
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_URE_FilterComponent_Profiles_Post extends GD_FilterComponent_Profiles {
	
	function get_filterformcomponent() {
	
		return GD_URE_TEMPLATE_FILTERFORMCOMPONENT_TYPEAHEAD_PROFILES;
	}

	function get_formcomponent() {
	
		return GD_URE_TEMPLATE_FILTERFORMCOMPONENTGROUP_TYPEAHEAD_PROFILES;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
global $gd_ure_filtercomponent_profiles_post;
$gd_ure_filtercomponent_profiles_post = new GD_URE_FilterComponent_Profiles_Post();