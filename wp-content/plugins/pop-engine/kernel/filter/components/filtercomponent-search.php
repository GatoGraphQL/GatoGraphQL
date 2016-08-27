<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Filter Base Typeahead
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_FilterComponent_BaseSearch extends GD_FilterComponent {
	
	function get_search() {
	
		return $this->get_filterformcomponent_value();
	}	
}
