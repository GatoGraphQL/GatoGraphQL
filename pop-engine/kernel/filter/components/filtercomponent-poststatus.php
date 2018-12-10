<?php

class GD_FilterComponent_PostStatus extends GD_FilterComponent {
	
	function get_poststatus($filter) {

		return $this->get_filterinput_value($filter);
	}	
	
}
