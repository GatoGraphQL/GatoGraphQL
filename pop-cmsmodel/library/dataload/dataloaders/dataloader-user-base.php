<?php

class GD_Dataloader_UserBase extends GD_QueryDataDataloader {

	function get_dataquery() {

		return GD_DATAQUERY_USER;
	}
	
	function get_database_key() {
	
		return GD_DATABASE_KEY_USERS;
	}

	function get_fieldprocessor() {

		return GD_DATALOAD_FIELDPROCESSOR_USERS;
	}
	
	function execute_get_data($ids) {
	
		if ($ids) {

			$cmsapi = PoP_CMS_FunctionAPI_Factory::get_instance();
			$ret = array();
			foreach ($ids as $user_id) {
				
				$ret[] = $cmsapi->get_user_by('id', $user_id);
			}
			return $ret;
		}
		
		return array();
	}

}
