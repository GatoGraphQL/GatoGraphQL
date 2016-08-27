<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_DATALOAD_ACTIONEXECUTER_CREATEUPDATE_FARM', 'createupdate-farm');

class GD_DataLoad_ActionExecuter_CreateUpdate_Farm extends GD_DataLoad_ActionExecuter_CreateUpdate_Post {

    function get_name() {
    
		return GD_DATALOAD_ACTIONEXECUTER_CREATEUPDATE_FARM;
	}

	function get_createupdate() {

		// global $gd_createupdate_farm;
		// return $gd_createupdate_farm;
		return new GD_CreateUpdate_Farm();
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoad_ActionExecuter_CreateUpdate_Farm();