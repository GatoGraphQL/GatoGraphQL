<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Filter Events
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Filter_LocationsRoot extends GD_Filter {
		
	function get_filter_args() {

		if (!($filtercomponents = $this->get_filtercomponents())) {
			return;
		}
		
		$args = parent::get_filter_args();
		if ($search = $this->get_search()) {
			
			$args['search'] = $search;
		}
		
		return $args;
	}
}
