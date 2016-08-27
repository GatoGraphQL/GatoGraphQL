<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_DATALOAD_ACTIONEXECUTER_CREATEUPDATE_PROFILEORGANIZATION', 'createupdate-profileorganization');

class GD_DataLoad_ActionExecuter_CreateUpdate_ProfileOrganization extends GD_DataLoad_ActionExecuter_CreateUpdate_Profile {

    function get_name() {
    
		return GD_DATALOAD_ACTIONEXECUTER_CREATEUPDATE_PROFILEORGANIZATION;
	}

	function get_createupdate() {

		// global $gd_createupdate_profileorganization;
		// return $gd_createupdate_profileorganization;
		return new GD_CreateUpdate_ProfileOrganization();
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoad_ActionExecuter_CreateUpdate_ProfileOrganization();