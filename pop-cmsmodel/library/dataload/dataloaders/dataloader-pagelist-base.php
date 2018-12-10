<?php

class GD_Dataloader_PageListBase extends GD_Dataloader_PostListBase {

    function get_data_query($ids) {
    
    	$query = parent::get_data_query($ids);
		
		$query['post_type'] = 'page';
		
		return $query;
	}	
	
	// function execute_query_ids($query) {
    
 //    	$query['fields'] = 'ids';
 //    	return $this->execute_query($query);
	// }
	
	function get_database_key() {
	
		return GD_DATABASE_KEY_PAGES;
	}	
}
