<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_DATALOAD_ACTIONEXECUTER_CREATEUPDATE_DISCUSSIONLINK', 'createupdate-discussionlink');

class GD_DataLoad_ActionExecuter_CreateUpdate_DiscussionLink extends GD_DataLoad_ActionExecuter_CreateUpdate_Post {

    function get_name() {
    
		return GD_DATALOAD_ACTIONEXECUTER_CREATEUPDATE_DISCUSSIONLINK;
	}

	function get_createupdate() {

		return new GD_CreateUpdate_DiscussionLink();
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoad_ActionExecuter_CreateUpdate_DiscussionLink();