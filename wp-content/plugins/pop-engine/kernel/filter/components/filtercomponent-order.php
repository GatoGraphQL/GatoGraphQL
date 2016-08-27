<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Filter Base Typeahead
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_FilterComponent_Order extends GD_FilterComponent {
	
	function get_order() {
	
		return $this->get_filterformcomponent_value();
	}	
	
}
