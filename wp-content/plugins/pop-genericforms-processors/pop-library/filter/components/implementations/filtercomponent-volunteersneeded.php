<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Filter Name
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_FilterComponent_VolunteersNeeded extends GD_FilterComponent_Metaquery {
	
	function get_filterformcomponent() {
	
		return GD_TEMPLATE_FILTERFORMCOMPONENT_VOLUNTEERSNEEDED_MULTISELECT;
	}
	
	function get_formcomponent() {
	
		return GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_VOLUNTEERSNEEDED_MULTISELECT;
	}
	
	function get_metaquery_compare() {
	
		return 'EXISTS';
	}
		
	function get_metaquery_key() {
	
		return GD_MetaManager::get_meta_key(GD_METAKEY_POST_VOLUNTEERSNEEDED);
	}	
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
global $gd_filtercomponent_volunteersneeded;
$gd_filtercomponent_volunteersneeded = new GD_FilterComponent_VolunteersNeeded();