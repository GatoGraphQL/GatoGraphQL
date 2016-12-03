<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_DATALOAD_ACTIONEXECUTER_CREATEUPDATE_LOCATIONPOSTLINK', 'createupdate-locationpostlink');

class GD_DataLoad_ActionExecuter_CreateUpdate_LocationPostLink extends GD_DataLoad_ActionExecuter_CreateUpdate_Post {

    function get_name() {
    
		return GD_DATALOAD_ACTIONEXECUTER_CREATEUPDATE_LOCATIONPOSTLINK;
	}

	function get_createupdate() {

		return new GD_CreateUpdate_LocationPostLink();
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoad_ActionExecuter_CreateUpdate_LocationPostLink();