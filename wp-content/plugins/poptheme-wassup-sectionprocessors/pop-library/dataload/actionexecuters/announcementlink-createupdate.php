<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_DATALOAD_ACTIONEXECUTER_CREATEUPDATE_ANNOUNCEMENTLINK', 'createupdate-announcementlink');

class GD_DataLoad_ActionExecuter_CreateUpdate_AnnouncementLink extends GD_DataLoad_ActionExecuter_CreateUpdate_Post {

    function get_name() {
    
		return GD_DATALOAD_ACTIONEXECUTER_CREATEUPDATE_ANNOUNCEMENTLINK;
	}

	function get_createupdate() {

		return new GD_CreateUpdate_AnnouncementLink();
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoad_ActionExecuter_CreateUpdate_AnnouncementLink();