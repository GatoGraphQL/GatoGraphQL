<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Filter Profiles
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_URE_FilterComponent_CommunitiyUsers extends GD_FilterComponent_Profiles {
	
	function get_filterformcomponent() {
	
		return GD_URE_TEMPLATE_FILTERFORMCOMPONENT_TYPEAHEAD_COMMUNITYUSERS;
	}

	function get_formcomponent() {
	
		return GD_URE_TEMPLATE_FILTERFORMCOMPONENTGROUP_TYPEAHEAD_COMMUNITYUSERS;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
global $gd_ure_filtercomponent_communityusers;
$gd_ure_filtercomponent_communityusers = new GD_URE_FilterComponent_CommunitiyUsers();