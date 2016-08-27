<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Filter Name
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_FilterComponent_Search extends GD_FilterComponent_BaseSearch {
	
	function get_filterformcomponent() {
	
		return GD_TEMPLATE_FILTERFORMCOMPONENT_SEARCH;
	}

	function get_formcomponent() {
	
		return GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_SEARCH;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
global $gd_filtercomponent_search;
$gd_filtercomponent_search = new GD_FilterComponent_Search();