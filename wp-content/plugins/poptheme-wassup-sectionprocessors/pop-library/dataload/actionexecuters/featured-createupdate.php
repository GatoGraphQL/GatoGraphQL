<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_DATALOAD_ACTIONEXECUTER_CREATEUPDATE_FEATURED', 'createupdate-featured');

class GD_DataLoad_ActionExecuter_CreateUpdate_Featured extends GD_DataLoad_ActionExecuter_CreateUpdate_Post {

    function get_name() {
    
		return GD_DATALOAD_ACTIONEXECUTER_CREATEUPDATE_FEATURED;
	}

	function get_createupdate() {

		return new GD_CreateUpdate_Featured();
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoad_ActionExecuter_CreateUpdate_Featured();