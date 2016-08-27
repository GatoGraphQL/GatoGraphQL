<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Filter Name
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_FilterComponent_Stance extends GD_FilterComponent_CategoriesBase {
	
	function get_filterformcomponent() {
	
		return GD_TEMPLATE_FILTERFORMCOMPONENT_STANCE_MULTISELECT;
	}
	
	function get_formcomponent() {
	
		return GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_BUTTONGROUP_STANCE;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
global $gd_filtercomponent_stance;
$gd_filtercomponent_stance = new GD_FilterComponent_Stance();