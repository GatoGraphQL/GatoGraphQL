<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Filter Events
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Filter_CommentsBase extends GD_Filter {
		
	/**
	 * Comments do not filter by date. 
	 */
	function filter_where( $where = '' ) {

		return $where;
	}
	
	function get_filter_args() {
	
		if (!($filtercomponents = $this->get_filtercomponents())) {
			return;
		}
		
		$args = parent::get_filter_args();
						
		if ($search = $this->get_search()) {
			
			$args['search'] = $search;
			unset($args['s']);
			unset($args['is_search']);
		}

		// Profile
		if ($profiles = $this->get_author()) {
			
			$args['user_id'] = $profiles;
		}
		
		return $args;
	}
}
