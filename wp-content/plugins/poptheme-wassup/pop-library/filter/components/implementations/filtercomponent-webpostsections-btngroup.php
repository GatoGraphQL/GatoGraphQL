<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Filter LinkCategories
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_FilterComponent_WebPostSectionsBtnGroup extends GD_FilterComponent_CategoriesBase {
		
	function get_filterformcomponent() {
	
		return GD_TEMPLATE_FILTERFORMCOMPONENT_BUTTONGROUP_WEBPOSTSECTIONS;
	}

	function get_formcomponent() {
	
		return GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_BUTTONGROUP_WEBPOSTSECTIONS;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
global $gd_filtercomponent_webpostsectionsbtngroup;
$gd_filtercomponent_webpostsectionsbtngroup = new GD_FilterComponent_WebPostSectionsBtnGroup();