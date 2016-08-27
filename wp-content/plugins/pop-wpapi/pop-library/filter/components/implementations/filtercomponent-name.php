<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Filter Name
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_FilterComponent_Name extends GD_FilterComponent_Metaquery {
	
	function get_filterformcomponent() {
	
		return GD_TEMPLATE_FILTERFORMCOMPONENT_NAME;
	}

	function get_formcomponent() {
	
		return GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_NAME;
	}
	
	function get_metaquery_compare() {
	
		return 'LIKE';
	}
		
	function get_metaquery_key() {
	
		return 'nickname';
	}	
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
global $gd_filtercomponent_name;
$gd_filtercomponent_name = new GD_FilterComponent_Name();