<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Ajax Load Posts Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_DATALOADER_EVENTLIST', 'event-list');

// Comment Leo 06/05/2014: Commented and not used, because in the References Typeahead we have 2 components
// with different db-keys: Projects and Events, and since we can define only one dataloader (in this case, GD_DATALOADER_POSTLIST),
// then the db-keys must be the same, or it produces a javascript error
//define ('GD_DATABASE_KEY_EVENTS', 'events');

class GD_DataLoader_EventList extends GD_DataLoader_PostListBase {

    function get_name() {
    
		return GD_DATALOADER_EVENTLIST;
	}

    function get_execution_priority() {
    
		return 1;
	}
	
	function get_query($vars = array()) {
	
		$query = parent::get_query($vars);

		// EM uses variable 'limit' to limit how many posts. If 'posts_per_page' or 'numberposts' is not unset, it creates trouble
		// $query['limit'] = $query['posts_per_page'] ? $query['posts_per_page'] : $query['numberposts'];
		$limit = $vars[GD_URLPARAM_LIMIT];
		if ($limit == -1) {

			// 0: no limit
			$limit = 0;
		}
		$query['limit'] = $limit;

		// No need the values set by the parent
		unset($query['posts_per_page']);
		unset($query['numberposts']);

		// Order by Event Start Date
		$query['order'] = 'ASC';
		$query['orderby'] = 'event_start_date';

		// Profile
		// Always use no owner. If needed to filter by author, it's done getting the post_ids below (because of interaction with CoAuthor Plus plugin)
		$query['owner'] = false;
		if ($profiles = $query['author']) {

			$post_ids = gd_user_event_post_ids($profiles);
			
			// If empty, then force it to bring no results with a -1 id (otherwise, it brings all results)
			if (empty($post_ids)) {
				$post_ids = array(-1);
			}
		
			// Limit posts to the profile	
			$query['post_id'] = $post_ids;
			unset($query['author']);
		}

		// Include post ids
		if ($post_ids = $query['include']) {

			$query['post_id'] = $post_ids;
			unset($query['include']);
		}

		// Search
		// if ($query['is_search'] === true) {

		// 	$query['search'] = $query['s'];
		// 	unset($query['s']);
		// 	unset($query['is_search']);
		// }

		if ($scope = $vars['scope']) {
		
			$query['scope'] = $scope;
		}

		// Execute after filter.php function filter_query
		add_filter( 'gd_dataload_pre_execute', array($this, 'get_after_filter_query'), 20, 3 );

		return $query;
	}

	function get_after_filter_query($query, $is_main_query, $vars = array()) {
	
		if (!$is_main_query)
			return $query;	

		// Post status: it can only be set after the filter: if not filtering by status in the filter, set the default one (passed as parameter)
		// $query['status'] is set in this class. If not set, check if set in the filter, as post_status
		if ($status = $query['post_status']) {

			$query['status'] = $status;
			unset($query['post_status']);
		}
		elseif ($status = $vars['status']) {
		
			$query['status'] = $status;
		}

		// Remove this filter so it doesn't execute anymore with the other dataloaders
		remove_filter( 'gd_dataload_pre_execute', array($this, 'get_after_filter_query'), 20, 3 );

		return $query;
	}
	
    function execute_query($query) {

    	return EM_Events::get($query);
	}	
	
	function get_data_query($ids) {
    
		$query = array(
			//'post_id' => implode(',', $ids)
			'post_id' => $ids,
			'scope' => 'all',
			'limit' => 0,
			'owner' => false,
			'status' => 'all'
		);

		return $query;
	}
	
	function execute_query_ids($query) {

    	$query['array'] = true;

    	// Add filter to only bring the ids and nothing else
		add_filter('em_events_get_sql', array($this, 'em_events_get_sql'));
    	$results = $this->execute_query($query);
		remove_filter('em_events_get_sql', array($this, 'em_events_get_sql'));

    	$ret = array();
    	foreach ($results as $key => $value) {
    		$ret[] = $value['post_id'];
    	}

    	return $ret;
	}

	function em_events_get_sql($sql) {

		// Modify the $sql to bring only the ids field
		$parts = explode(' FROM ', $sql);

		// Copied from EM_Events::get() (em-events.php)
		$events_table = EM_EVENTS_TABLE;
		$post_id_field = $events_table.'.post_id';

		$sql = "SELECT " . $post_id_field . " FROM " . $parts[1];

		return $sql;
	}
	
	function get_database_key() {
	
		// return GD_DATABASE_KEY_EVENTS;
		return GD_DATABASE_KEY_POSTS;
	}			
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoader_EventList();