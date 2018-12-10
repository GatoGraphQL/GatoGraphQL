<?php

class GD_Dataloader_PostBase extends GD_QueryDataDataloader {

	function get_dataquery() {

		return GD_DATAQUERY_POST;
	}
	
	function get_database_key() {
	
		return GD_DATABASE_KEY_POSTS;
	}	

	function get_fieldprocessor() {

		return GD_DATALOAD_FIELDPROCESSOR_POSTS;
	}
	
	function execute_get_data($ids) {
	
		if ($ids) {

			$cmsapi = PoP_CMS_FunctionAPI_Factory::get_instance();
			$query = array(
				'include' => $ids,
				'post_type' => array_keys($cmsapi->get_post_types()) // From all post types
			);
	        return $cmsapi->get_posts($query);
		}
		
		return array();
	}
	
	// function execute_get_data($ids) {
	
	// 	$ret = array();
	// 	foreach ($ids as $post_id) {

	// 		$ret[] = $this->get_post($post_id);
	// 	}
	// 	return $ret;
	// }

	// function get_post($post_id) {
	
 //    	$cmsapi = PoP_CMS_FunctionAPI_Factory::get_instance();
	// 	return $cmsapi->get_post($post_id);
	// }
}
