<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_DATALOAD_ACTIONEXECUTER_CREATEUPDATE_HIGHLIGHT', 'createupdate-highlight');

// class GD_DataLoad_ActionExecuter_CreateUpdate_Highlight extends GD_DataLoad_ActionExecuter_CreateUpdate_Post {
class GD_DataLoad_ActionExecuter_CreateUpdate_Highlight extends GD_DataLoad_ActionExecuter_CreateUpdate_ReferencePermalinkBase {

    function get_name() {
    
		return GD_DATALOAD_ACTIONEXECUTER_CREATEUPDATE_HIGHLIGHT;
	}

	function get_createupdate() {

		return new GD_CreateUpdate_Highlight();
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoad_ActionExecuter_CreateUpdate_Highlight();