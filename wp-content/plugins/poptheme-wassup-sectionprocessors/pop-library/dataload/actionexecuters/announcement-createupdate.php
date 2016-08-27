<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_DATALOAD_ACTIONEXECUTER_CREATEUPDATE_ANNOUNCEMENT', 'createupdate-announcement');

class GD_DataLoad_ActionExecuter_CreateUpdate_Announcement extends GD_DataLoad_ActionExecuter_CreateUpdate_Post {

    function get_name() {
    
		return GD_DATALOAD_ACTIONEXECUTER_CREATEUPDATE_ANNOUNCEMENT;
	}

	function get_createupdate() {

		// global $gd_createupdate_announcement;
		// return $gd_createupdate_announcement;
		return new GD_CreateUpdate_Announcement();
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoad_ActionExecuter_CreateUpdate_Announcement();