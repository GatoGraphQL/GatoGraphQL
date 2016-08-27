<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_DATALOAD_ACTIONEXECUTER_CREATEUPDATE_PROFILE', 'createupdate-profile');

class GD_DataLoad_ActionExecuter_CreateUpdate_Profile extends GD_DataLoad_ActionExecuter_CreateUpdate_User {

    function get_name() {
    
		return GD_DATALOAD_ACTIONEXECUTER_CREATEUPDATE_PROFILE;
	}

	function get_createupdate() {

		// global $gd_createupdate_profile;
		// return $gd_createupdate_profile;
		return new GD_CreateUpdate_Profile();
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoad_ActionExecuter_CreateUpdate_Profile();