<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_DATALOAD_ACTIONEXECUTER_CREATEUPDATE_DISCUSSION', 'createupdate-discussion');

class GD_DataLoad_ActionExecuter_CreateUpdate_Discussion extends GD_DataLoad_ActionExecuter_CreateUpdate_Post {

    function get_name() {
    
		return GD_DATALOAD_ACTIONEXECUTER_CREATEUPDATE_DISCUSSION;
	}

	function get_createupdate() {

		// global $gd_createupdate_discussion;
		// return $gd_createupdate_discussion;
		return new GD_CreateUpdate_Discussion();
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoad_ActionExecuter_CreateUpdate_Discussion();