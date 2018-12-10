<?php

class GD_FilterComponent_BaseSearch extends GD_FilterComponent {
	
	function get_search($filter) {
	
		return $this->get_filterinput_value($filter);
	}	
}
