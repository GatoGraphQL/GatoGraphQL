<?php

class GD_FilterComponent_PostDates extends GD_FilterComponent {
	
	function get_postdates($filter) {

		return $this->get_filterinput_value($filter);	
	}	
	
}
