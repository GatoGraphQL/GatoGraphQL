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
		if ($joinstatus = $vars['joinstatus']) {
			$query['joinstatus'] = $joinstatus;
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
    	$query['joinstatus'] = false;

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