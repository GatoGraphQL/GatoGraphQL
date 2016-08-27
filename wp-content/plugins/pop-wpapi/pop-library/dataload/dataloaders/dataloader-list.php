<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/
 
class GD_DataLoader_List extends GD_DataLoader {
	
    /**
     * Function to override
     */
    function execute_query($query) {
    
		return array();
	}
	
	function execute_query_ids($query) {
    
		return $this->execute_query($query);
	}
	
	function get_data_ids($vars = array(), $is_main_query = false) {

		// If no load, then return nothing (eg: for the Search, initialize with no results)
		if ($vars['load'] === false) {

			return array();
		}

		// If already indicating the ids to get back, then already return them
		if ($include = $vars['include']) {

			if (!is_array($include)) {
				return explode(',', $include);
			}

			return $include;
		}

		// Customize query
		$query = $this->get_query($vars);

		// Allow URE to modify the role, limiting selected users and excluding others, like 'subscriber'
		$query = apply_filters('gd_dataload_query:'.$this->get_name(), $query, $is_main_query, $vars);
				
		// Allow $gd_filter to filter the query
		$query = apply_filters('gd_dataload_pre_execute', $query, $is_main_query, $vars);

		// Execute the query, get ids
		$ids = $this->execute_query_ids($query);
		
		// Allow $gd_filter to clear (remove unneeded filters)
		do_action('gd_dataload_pos_execute', $is_main_query);
		
		return $ids;
	}
	

	/**
     * Function to override
     */
    function get_data_query($ids) {
    
		return array();
	}		
	
	function execute_get_data($ids) {
	
		$query = $this->get_data_query($ids);
		return $this->execute_query($query);
	}
 	
 	/**
     * Function to override
     */
    function get_query($vars = array()) {

    	$query = array();

    	// Addition of ID for the Typeahead
		if ($include = $vars['include']) {

			if (!is_array($include)) {
				$include = explode(',', $include);
			}
			$query['include'] = $include;
		}

		return $query;
	}

}
	
