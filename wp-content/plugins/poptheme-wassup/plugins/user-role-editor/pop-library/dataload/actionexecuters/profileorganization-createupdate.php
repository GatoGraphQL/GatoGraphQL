<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_CUSTOM_DATALOAD_ACTIONEXECUTER_CREATEUPDATE_PROFILEORGANIZATION', 'custom-createupdate-profileorganization');

class GD_Custom_DataLoad_ActionExecuter_CreateUpdate_ProfileOrganization extends GD_DataLoad_ActionExecuter_CreateUpdate_ProfileOrganization {

    function get_name() {
    
		return GD_CUSTOM_DATALOAD_ACTIONEXECUTER_CREATEUPDATE_PROFILEORGANIZATION;
	}

	function get_createupdate() {

		// global $gd_createupdate_profileorganization;
		// return $gd_createupdate_profileorganization;
		return new GD_URE_CreateUpdate_ProfileOrganization();
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Custom_DataLoad_ActionExecuter_CreateUpdate_ProfileOrganization();