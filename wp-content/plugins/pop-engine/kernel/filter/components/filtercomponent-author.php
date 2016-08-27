<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Filter Base Typeahead
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_FilterComponent_Author extends GD_FilterComponent {
	
	function get_author() {

		return $this->get_filterformcomponent_value();
	}	
	
}
