<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Filter Name
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_FilterComponent_UnmoderatedStatus extends GD_FilterComponent_PostStatus {
	
	function get_filterformcomponent() {
	
		return GD_TEMPLATE_FILTERFORMCOMPONENT_UNMODERATEDPOSTSTATUS;
	}

	function get_formcomponent() {
	
		return GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_UNMODERATEDPOSTSTATUS;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
global $gd_filtercomponent_unmoderatedstatus;
$gd_filtercomponent_unmoderatedstatus = new GD_FilterComponent_UnmoderatedStatus();