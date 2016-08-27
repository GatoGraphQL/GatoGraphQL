<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Filter Base Typeahead
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_FilterComponent_Taxonomy extends GD_FilterComponent_Media {
	
	function get_taxonomy() {

		return $this->get_filterformcomponent_value();
	}
	
	function get_filterformcomponent() {
	
		return GD_TEMPLATE_FILTERFORMCOMPONENT_TAXONOMY;
	}
	
	function get_formcomponent() {
	
		return GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_TAXONOMY;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
global $gd_filtercomponent_taxonomy;
$gd_filtercomponent_taxonomy = new GD_FilterComponent_Taxonomy();