<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Filter Dates
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_FilterComponent_DateRangePicker extends GD_FilterComponent_PostDates {
	
	function get_filterformcomponent() {
	
		return GD_TEMPLATE_FILTERFORMCOMPONENT_DATERANGEPICKER;
	}

	function get_formcomponent() {
	
		return GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_DATERANGEPICKER;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
global $gd_filtercomponent_daterangepicker;
$gd_filtercomponent_daterangepicker = new GD_FilterComponent_DateRangePicker();