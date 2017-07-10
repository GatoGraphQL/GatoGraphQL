<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Users Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_DATABASE_KEY_NOTIFICATIONS', 'notifications');
define ('GD_DATALOADER_NOTIFICATIONLIST', 'notification-list');

class GD_DataLoader_NotificationList extends GD_DataLoader_List {
	
    function get_name() {
    
		return GD_DATALOADER_NOTIFICATIONLIST;
	}
	
	function get_dataquery() {

		return GD_DATAQUERY_NOTIFICATION;
	}
	
	function get_execution_priority() {
    
		// Before CommentList Dataloader
		return 1;
	}
	
	function get_query($vars = array()) {
    
		$query = parent::get_query($vars);
		
		// Params and values by default		
		$orderby = isset( $vars['orderby'] ) ? $vars['orderby'] : 'histid';
		$order = isset( $vars['order'] ) ? $vars['order'] : 'DESC';
		
		$query['orderby'] = $orderby;
		$query['order'] = $order;
		
		if ($user_id = $vars['user_id']) {
			$query['user_id'] = $user_id;
		}

		if ($hist_time = $vars['hist_time']) {
			$query['hist_time'] = $hist_time;
		}
		if ($hist_time_compare = $vars['hist_time_compare']) {
			$query['hist_time_compare'] = $hist_time_compare;
		}

		if ($include = $vars['include']) {
			$query['include'] = $include;
		}

		// Pagination
		if (GD_TemplateManager_Utils::loading_latest()) {
			$query['paged'] = 1;
			$query['limit'] = 0; // Limit=0 => Bring all results
		}
		else {
			if ($paged = $vars[GD_URLPARAM_PAGED]) {
				$query['paged'] = $paged;
			}
			if ($limit = $vars[GD_URLPARAM_LIMIT]) {
				$query['limit'] = $limit;
			}
		}

		// left outer join for the status table?
		if (isset($vars['joinstatus'])) {
			$query['joinstatus'] = $vars['joinstatus'];

			// Also copy the status value, if passed, so that we can filter by either read or non-read notifications
			if (isset($vars['status'])) {
				$query['status'] = $vars['status'];
			}
		}

		return $query;
	}
	

	/**
     * Function to override
     */
    function get_data_query($ids) {
    
    	return array(
    		'include' => $ids,
    	);
	}	
	
	function execute_query_ids($query) {

    	$query['array'] = true;
    	$query['fields'] = array('histid');
    	
    	// By default, we use joinstatus => false to make the initial query run faster
    	// however, this param can be provided in the dataload_query_args, 
    	// eg: to bring in only notifications which have not been read, for the automated emails daily notification digest
    	// then keep it as it is
    	if (!isset($query['joinstatus'])) {
	    	$query['joinstatus'] = false;
	    }

    	$results = $this->execute_query($query);

		$ret = array();
		if ($results) {

			foreach ( $results as $value ) {
				$ret[] = $value['histid'];
			}
		}

		return $ret;
	}
	
	function execute_query($query) {

    	return AAL_Main::instance()->api->get_notifications($query);
	}
	
	function get_database_key() {
	
		return GD_DATABASE_KEY_NOTIFICATIONS;
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoader_NotificationList();