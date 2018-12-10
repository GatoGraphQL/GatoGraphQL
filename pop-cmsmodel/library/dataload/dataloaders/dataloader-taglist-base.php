<?php

class GD_Dataloader_TagListBase extends GD_Dataloader_TagBase {

	use GD_Dataloader_ListTrait;

	function get_data_query($ids) {
    
		$query = array(
			'include' => implode(', ', $ids)
		);
		return $query;
	}
	
	protected function get_orderby_default() {

		return 'count';
	}

	protected function get_order_default() {

		return 'DESC';
	}
	
    function execute_query($query) {

		$cmsapi = PoP_CMS_FunctionAPI_Factory::get_instance();
    	return $cmsapi->get_tags($query);
	}
	
	function execute_query_ids($query) {
    
    	// Retrieve only ids
		$query['fields'] = 'ids';
    	return $this->execute_query($query);
	}	
}
