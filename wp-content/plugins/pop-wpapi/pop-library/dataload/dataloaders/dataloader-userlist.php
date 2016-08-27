<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Users Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_DATALOADER_USERLIST', 'user-list');

class GD_DataLoader_UserList extends GD_DataLoader_List {

	function get_name() {
    
		return GD_DATALOADER_USERLIST;
	}

	function get_dataquery() {

		return GD_DATAQUERY_USER;
	}

	function get_data_query($ids) {
    
		$query = array(
			'include' => $ids
		);
		return $query;
	}
	
	function get_execution_priority() {
    
		return 3;
	}

	function get_crawlabledata_printer() {
	
		return GD_DATALOAD_CRAWLABLEDATAPRINTER_USER;
	}

	function get_query($vars = array()) {
	
		$query = parent::get_query($vars);

		$paged = $vars[GD_URLPARAM_PAGED];
		$limit = $vars[GD_URLPARAM_LIMIT];

		if (GD_TemplateManager_Utils::loading_latest()) {
			$paged = 1;
			$limit = 0;
		}

		if ($limit >= 1) {

			$offset = ($paged - 1) * $limit;

			$query[GD_URLPARAM_PAGED] = $paged;
			$query['offset'] = $offset;
			$query['number'] = $limit;
		}

		$query['orderby'] = isset( $vars['orderby'] ) ? $vars['orderby'] : 'name';
		$query['order'] = isset( $vars['order'] ) ? $vars['order'] : 'ASC';
		
		// Get the role either from a provided attr
		if ($role = $vars['role']) {
	
			$query['role'] = $role;
		}

		// Metaquery: eg: filter only Actions with Locations
		if ($meta_query = $vars['meta-query']) {
			$query['meta_query'] = $meta_query;
		}

		return $query;
	}
	
    function execute_query($query) {

    	return get_users($query);
	}
	
	function execute_query_ids($query) {
    
    	// Retrieve only ids
		$query['fields'] = 'ID';
    	return $this->execute_query($query);
	}	

	function get_database_key() {
	
		return GD_DATABASE_KEY_USERS;
	}

}

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoader_UserList();