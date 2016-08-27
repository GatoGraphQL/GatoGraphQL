<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Users Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_DATALOADER_LOCATIONLIST', 'location-list');

class GD_DataLoader_LocationList extends GD_DataLoader_PostListBase {
	
    function get_name() {
    
		return GD_DATALOADER_LOCATIONLIST;
	}
	
	function get_execution_priority() {
    
		return 5;
	}

	function get_crawlabledata_printer() {
	
		return GD_DATALOAD_CRAWLABLEDATAPRINTER_LOCATION;
	}
	
	function get_query($vars = array()) {
	
		$query = parent::get_query($vars);

		// EM uses variable 'limit' to limit how many posts. If 'posts_per_page' or 'numberposts' is not unset, it creates trouble
		$limit = $vars[GD_URLPARAM_LIMIT];
		if ($limit == -1) {

			// 0: no limit
			$limit = 0;
		}
		$query['limit'] = $limit;

		// No need the values set by the parent
		unset($query['posts_per_page']);
		unset($query['numberposts']);

		// Include post ids
		if ($post_ids = $query['include']) {

			$query['post_id'] = $post_ids;
			unset($query['include']);
		}

		return $query;
	}
	
	function execute_query_ids($query) {

    	$query['array'] = true;

    	// Add filter to only bring the ids and nothing else
		add_filter('gd_em_locations_get_sql', array($this, 'em_locations_get_sql'));
		add_filter('em_locations_get_sql', array($this, 'em_locations_get_sql'));
    	$results = $this->execute_query($query);
		remove_filter('em_locations_get_sql', array($this, 'em_locations_get_sql'));
		remove_filter('gd_em_locations_get_sql', array($this, 'em_locations_get_sql'));

    	$ret = array();
    	foreach ($results as $key => $value) {
    		$ret[] = $value['post_id'];
    	}

    	return $ret;
	}

	function em_locations_get_sql($sql) {

		// Modify the $sql to bring only the ids field
		$parts = explode(' FROM ', $sql);

		// Copied from EM_Events::get() (em-events.php)
		$locations_table = EM_LOCATIONS_TABLE;
		$post_id_field = $locations_table.'.post_id';

		$sql = "SELECT " . $post_id_field . " FROM " . $parts[1];

		return $sql;
	}	
	
	function execute_get_data($ids) {
	
		$locations = array();
		foreach ( $ids as $post_id ){
			$locations[] = em_get_location($post_id, 'post_id');
		}

		return $locations;
	}
	
	function get_data_query($ids) {

		$query = array(
			'post_id' => implode(',', $ids),
			'limit' => 0
		);
		return $query;
	}
	
	function execute_query($query) {

    	// Comment Leo 06/06/2015: the code below doesn't work, because it does a LEFT JOIN with wp_events table always, which means that it will only
    	// get the locations with an event associated to it
    	return EM_Locations::get($query);
	}
	
	function get_database_key() {
	
		return GD_DATABASE_KEY_LOCATIONS;
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoader_LocationList();