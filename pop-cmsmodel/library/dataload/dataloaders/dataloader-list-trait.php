<?php
 
trait GD_Dataloader_ListTrait {

	use GD_FilterQueryDataloaderTrait;
	
    /**
     * Function to override
     */
    function execute_query($query) {
    
		return array();
	}
	
	function execute_query_ids($query) {
    
		return $this->execute_query($query);
	}

	protected function get_paged_param($query_args) {
		
		// Allow to check for PoP_Application_Engine_Utils::loading_latest():
		return apply_filters(
			'GD_Dataloader_List:query:paged',
			$query_args[GD_URLPARAM_PAGED]
		);
	}
	protected function get_limit_param($query_args) {
		
		return $this->get_meta_limit_param($query_args);
	}
	protected function get_meta_limit_param($query_args) {
		
		// Allow to check for PoP_Application_Engine_Utils::loading_latest():
		return apply_filters(
			'GD_Dataloader_List:query:limit',
			$query_args[GD_URLPARAM_LIMIT]
		);
	}
	
	function get_dbobject_ids($data_properties) {

		$query_args = $data_properties[GD_DATALOAD_QUERYARGS];

		// If already indicating the ids to get back, then already return them
		if ($include = $query_args['include']) {

			if (!is_array($include)) {
				return explode(',', $include);
			}

			return $include;
		}

		// Customize query
		$query = $this->get_query($query_args);

		// Allow URE to modify the role, limiting selected users and excluding others, like 'subscriber'
		$query = apply_filters('gd_dataload_query:'.$this->get_name(), $query, $data_properties);
				
		// Apply filtering of the data
		$query = $this->filter_query($query, $data_properties);

		// Execute the query, get ids
		$ids = $this->execute_query_ids($query);
		
		// Allow $gd_filter to clear (remove unneeded filters)
		$this->clear_filter();
		
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

	protected function get_orderby_default() {

		return '';
	}

	protected function get_order_default() {

		return '';
	}

	protected function get_meta_query($query_args) {

    	$query = array();

  		// Allow to check for PoP_Application_Engine_Utils::loading_latest()
		$paged = $this->get_paged_param($query_args);
		$limit = $this->get_limit_param($query_args);
		if ($limit >= 1) {

			$offset = ($paged - 1) * $limit;
			$query['offset'] = $offset;
			$query['number'] = $limit;
		}
		else {
			// $limit is 0 (EM) or -1 (WP)
			$query['numberposts'] = $limit;
		}

		// Params and values by default		
		if ($orderby = isset( $query_args['orderby'] ) ? $query_args['orderby'] : $this->get_orderby_default()) {

			$query['orderby'] = $orderby;
		}
		if ($order = isset( $query_args['order'] ) ? $query_args['order'] : $this->get_order_default()) {
		
			$query['order'] = $order;
		}

		// Metaquery: eg: filter only Actions with Locations
		if ($meta_query = $query_args['meta-query']) {
			
			$query['meta_query'] = $meta_query;
		}

		return $query;
	}
 	
 	/**
     * Function to override
     */
    function get_query($query_args) {

    	return $this->get_meta_query($query_args);
	}

}
	
