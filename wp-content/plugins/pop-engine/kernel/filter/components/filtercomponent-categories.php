<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Filter Base Typeahead
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_FilterComponent_CategoriesBase extends GD_FilterComponent {
	
	function get_categories() {
	
		return $this->get_filterformcomponent_value();
	}	
	
}
