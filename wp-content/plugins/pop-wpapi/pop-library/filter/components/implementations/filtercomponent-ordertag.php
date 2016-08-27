<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Filter OrderProfile
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_FilterComponent_OrderTag extends GD_FilterComponent_Order {
	
	function get_filterformcomponent() {
	
		return GD_TEMPLATE_FILTERFORMCOMPONENT_ORDERTAG;
	}

	function get_formcomponent() {
	
		return GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_ORDERTAG;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
global $gd_filtercomponent_ordertag;
$gd_filtercomponent_ordertag = new GD_FilterComponent_OrderTag();