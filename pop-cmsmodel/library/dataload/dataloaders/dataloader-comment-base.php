<?php
namespace PoP\CMSModel;
 
abstract class Dataloader_CommentBase extends \PoP\Engine\QueryDataDataloader {

	function get_dataquery() {

		return GD_DATAQUERY_COMMENT;
	}

	function get_database_key() {
	
		return GD_DATABASE_KEY_COMMENTS;
	}

	function get_fieldprocessor() {

		return GD_DATALOAD_FIELDPROCESSOR_COMMENTS;
	}
	
	function execute_get_data($ids) {
	
    	$cmsapi = \PoP\CMS\FunctionAPI_Factory::get_instance();
		$ret = array();
		foreach ($ids as $id) {
			$ret[] = $cmsapi->get_comment($id);
		}

		return $ret;
	}
}