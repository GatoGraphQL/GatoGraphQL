<?php

class GD_FilterComponent_Order extends GD_FilterComponent {
	
	function get_order($filter) {
	
		return $this->get_filterinput_value($filter);
	}	
	
}
