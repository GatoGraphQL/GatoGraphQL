<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Ajax Load Posts Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_DATALOADER_PAGELIST', 'page-list');

class GD_DataLoader_PageList extends GD_DataLoader_PostListBase {

	function get_name() {
    
		return GD_DATALOADER_PAGELIST;
	}

    function get_execution_priority() {
    
		return 1;
	}

    function get_data_query($ids) {
    
    	$query = parent::get_data_query($ids);
		
		$query['post_type'] = 'page';
		
		return $query;
	}		
	
    function execute_query($query) {

    	return get_posts($query);
	}
	
	function execute_query_ids($query) {
    
    	$query['fields'] = 'ids';
    	return $this->execute_query($query);
	}
	
	function get_database_key() {
	
		return GD_DATABASE_KEY_PAGES;
	}	
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoader_PageList();