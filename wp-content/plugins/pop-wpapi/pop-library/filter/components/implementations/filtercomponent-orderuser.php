<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Filter OrderProfile
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_FilterComponent_OrderUser extends GD_FilterComponent_Order {
	
	function get_filterformcomponent() {
	
		return GD_TEMPLATE_FILTERFORMCOMPONENT_ORDERUSER;
	}

	function get_formcomponent() {
	
		return GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_ORDERUSER;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
global $gd_filtercomponent_orderuser;
$gd_filtercomponent_orderuser = new GD_FilterComponent_OrderUser();