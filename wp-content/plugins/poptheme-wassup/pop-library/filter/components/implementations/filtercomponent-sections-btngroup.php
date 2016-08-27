<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Filter LinkCategories
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_FilterComponent_SectionsBtnGroup extends GD_FilterComponent_TaxonomiesBase {
		
	function get_filterformcomponent() {
	
		return GD_TEMPLATE_FILTERFORMCOMPONENT_BUTTONGROUP_SECTIONS;
	}

	function get_formcomponent() {
	
		return GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_BUTTONGROUP_SECTIONS;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
global $gd_filtercomponent_sectionsbtngroup;
$gd_filtercomponent_sectionsbtngroup = new GD_FilterComponent_SectionsBtnGroup();