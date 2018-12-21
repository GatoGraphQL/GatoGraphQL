<?php
namespace PoP\CMSModel;

abstract class Dataloader_UserListBase extends Dataloader_UserBase {

	use Dataloader_ListTrait;

	function get_data_query($ids) {
    
		$query = array(
			'include' => $ids
		);
		return $query;
	}
	
	protected function get_orderby_default() {

		return 'name';
	}

	protected function get_order_default() {

		return 'ASC';
	}

	function get_query($query_args) {
	
		$query = $this->get_meta_query($query_args);

		// Get the role either from a provided attr, and allow PoP User Platform to set the default role
		if ($role = apply_filters(
			'Dataloader_UserListBase:query:role',
			$query_args['role']
			)) {
	
			$query['role'] = $role;
		}

		return $query;
	}
	
    function execute_query($query) {

		$cmsapi = \PoP\CMS\FunctionAPI_Factory::get_instance();
    	return $cmsapi->get_users($query);
	}
	
	function execute_query_ids($query) {
    
    	// Retrieve only ids
		$query['fields'] = 'ID';
    	return $this->execute_query($query);
	}	
}
