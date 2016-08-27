<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Filter Profiles
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_FilterComponent_Profiles extends GD_FilterComponent_Author {
	
	function get_filterformcomponent() {
	
		return GD_TEMPLATE_FILTERFORMCOMPONENT_TYPEAHEAD_PROFILES;
	}

	function get_formcomponent() {
	
		return GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_TYPEAHEAD_PROFILES;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
global $gd_filtercomponent_profiles;
$gd_filtercomponent_profiles = new GD_FilterComponent_Profiles();