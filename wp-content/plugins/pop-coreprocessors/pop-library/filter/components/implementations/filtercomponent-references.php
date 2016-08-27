<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Filter ActionCategories
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_FilterComponent_References extends GD_FilterComponent_Metaquery_Post {
		
	function get_filterformcomponent() {
	
		return GD_TEMPLATE_FILTERFORMCOMPONENT_TYPEAHEAD_REFERENCES;
	}

	function get_formcomponent() {
	
		return GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_TYPEAHEAD_REFERENCES;
	}

	function get_meta_key() {
	
		return GD_METAKEY_POST_REFERENCES;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
global $gd_filtercomponent_references;
$gd_filtercomponent_references = new GD_FilterComponent_References();