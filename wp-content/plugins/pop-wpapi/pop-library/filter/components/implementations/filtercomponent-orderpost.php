<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Filter OrderProfile
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_FilterComponent_OrderPost extends GD_FilterComponent_Order {
	
	function get_filterformcomponent() {
	
		return GD_TEMPLATE_FILTERFORMCOMPONENT_ORDERPOST;
	}

	function get_formcomponent() {
	
		return GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_ORDERPOST;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
global $gd_filtercomponent_orderpost;
$gd_filtercomponent_orderpost = new GD_FilterComponent_OrderPost();