<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_DATALOAD_ACTIONEXECUTER_CREATEUPDATE_PROJECTLINK', 'createupdate-projectlink');

class GD_DataLoad_ActionExecuter_CreateUpdate_ProjectLink extends GD_DataLoad_ActionExecuter_CreateUpdate_Post {

    function get_name() {
    
		return GD_DATALOAD_ACTIONEXECUTER_CREATEUPDATE_PROJECTLINK;
	}

	function get_createupdate() {

		return new GD_CreateUpdate_ProjectLink();
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoad_ActionExecuter_CreateUpdate_ProjectLink();