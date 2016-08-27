<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Filter LinkCategories
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_FilterComponent_WebPostSections extends GD_FilterComponent_CategoriesBase {
		
	function get_filterformcomponent() {
	
		return GD_TEMPLATE_FILTERFORMCOMPONENT_WEBPOSTSECTIONS;
	}

	function get_formcomponent() {
	
		return GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_WEBPOSTSECTIONS;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
global $gd_filtercomponent_webpostsections;
$gd_filtercomponent_webpostsections = new GD_FilterComponent_WebPostSections();