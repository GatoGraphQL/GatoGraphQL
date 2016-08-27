<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_DATALOAD_ACTIONEXECUTER_CREATEUPDATE_PROJECT', 'createupdate-project');

class GD_DataLoad_ActionExecuter_CreateUpdate_Project extends GD_DataLoad_ActionExecuter_CreateUpdate_Post {

    function get_name() {
    
		return GD_DATALOAD_ACTIONEXECUTER_CREATEUPDATE_PROJECT;
	}

	function get_createupdate() {

		// global $gd_createupdate_project;
		// return $gd_createupdate_project;
		return new GD_CreateUpdate_Project();
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoad_ActionExecuter_CreateUpdate_Project();