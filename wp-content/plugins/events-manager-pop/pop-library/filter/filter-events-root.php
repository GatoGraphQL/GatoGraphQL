<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Filter Events
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Filter_EventsRoot extends GD_Filter {
		
	/**
	 * Events do not filter by date. Instead, the dates are used to define Event dates, not post creation dates
	 */
	function filter_where( $where = '' ) {

		return $where;
	}
	
	function get_filter_args() {
	
		if (!($filtercomponents = $this->get_filtercomponents())) {
			return;
		}
		
		$args = parent::get_filter_args();
						
		// Event Dates
		if ($dates = $this->get_postdates()) {
		
			// Format description: http://wp-events-plugin.com/documentation/event-search-attributes/
			$args['scope'] = $dates['from'].','.$dates['to'];
		}

		if ($search = $this->get_search()) {
			
			$args['search'] = $search;
		}
		
		return $args;
	}

	function filter_query(&$query) {
	
		// Remove the author, use owner post_id instead
		if ($profiles = $this->get_author()) {
				
			$post_ids = gd_user_event_post_ids($profiles);
			
			// If empty, then force it to bring no results with a -1 id (otherwise, it brings all results)
			if (empty($post_ids)) {
				$post_ids = array(-1);
			}
			// Limit posts to the profile	
			$query['post_id'] = $post_ids;
			unset($query['author']);
		}
	}
}
