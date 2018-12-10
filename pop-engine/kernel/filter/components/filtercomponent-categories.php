<?php

class GD_FilterComponent_CategoriesBase extends GD_FilterComponent {
	
	function get_categories($filter) {
	
		return $this->get_filterinput_value($filter);
	}	
	
}
