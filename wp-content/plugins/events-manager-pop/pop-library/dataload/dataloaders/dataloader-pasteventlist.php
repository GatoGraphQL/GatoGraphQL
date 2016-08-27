<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Ajax Load Posts Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_DATALOADER_PASTEVENTLIST', 'pastevent-list');

class GD_DataLoader_PastEventList extends GD_DataLoader_EventList {

    function get_name() {
    
		return GD_DATALOADER_PASTEVENTLIST;
	}

	function get_query($vars = array()) {
	
		$query = parent::get_query($vars);

		// Order DESC => needed for past events to show up from top to bottom
		$query['order'] = 'DESC';

		// Execute after filter.php function filter_query
		// add_filter( 'gd_dataload_pre_execute', array($this, 'get_after_filter_query'), 20, 3 );

		return $query;
	}

	function get_after_filter_query($query, $is_main_query, $vars = array()) {
	
		if (!$is_main_query)
			return $query;

		$query = parent::get_after_filter_query($query, $is_main_query, $vars);

		if (!isset($query['scope'])) {
		
			$query['scope'] = 'past';
		}

		// Remove this filter so it doesn't execute anymore with the other dataloaders
		// remove_filter( 'gd_dataload_pre_execute', array($this, 'get_after_filter_query'), 20, 3 );

		return $query;
	}
	
	function get_data_query($ids) {
    
		$query = parent::get_data_query($ids);

		// Order DESC => needed for past events to show up from top to bottom
		$query['order'] = 'DESC';
		$query['orderby'] = 'event_start_date';

		return $query;
	}			
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoader_PastEventList();