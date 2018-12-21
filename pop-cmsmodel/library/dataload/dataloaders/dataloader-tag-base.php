<?php
namespace PoP\CMSModel;

abstract class Dataloader_TagBase extends \PoP\Engine\QueryDataDataloader {

	function get_dataquery() {

		return GD_DATAQUERY_TAG;
	}

	function get_database_key() {
	
		return GD_DATABASE_KEY_TAGS;
	}

	function get_fieldprocessor() {

		return GD_DATALOAD_FIELDPROCESSOR_TAGS;
	}

	function execute_get_data($ids) {
	
		if ($ids) {

			$query = array(
				'include' => implode(', ', $ids)
			);
			$cmsapi = \PoP\CMS\FunctionAPI_Factory::get_instance();
			return $cmsapi->get_tags($query);
		}
		
		return array();
	}
	
	// function execute_get_data($ids) {
	
 //    	$cmsapi = \PoP\CMS\FunctionAPI_Factory::get_instance();
	// 	if ($tag_id = $ids[0]) {
	// 		return array($cmsapi->get_tag($tag_id));
	// 	}
	// 	return array();
	// }
}
