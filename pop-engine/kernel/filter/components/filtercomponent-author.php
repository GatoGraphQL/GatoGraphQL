<?php

class GD_FilterComponent_Author extends GD_FilterComponent {
	
	function get_author($filter) {

		return $this->get_filterinput_value($filter);
	}	
	
}
