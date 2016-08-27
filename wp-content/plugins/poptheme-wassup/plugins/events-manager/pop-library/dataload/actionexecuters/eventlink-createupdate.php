<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_DATALOAD_ACTIONEXECUTER_CREATEUPDATE_EVENTLINK', 'createupdate-eventlink');

// class GD_DataLoad_ActionExecuter_CreateUpdate_Event extends GD_DataLoad_ActionExecuter_CreateUpdate_Post {
class GD_DataLoad_ActionExecuter_CreateUpdate_EventLink extends GD_DataLoad_ActionExecuter_CreateUpdate_Event {

    function get_name() {
    
		return GD_DATALOAD_ACTIONEXECUTER_CREATEUPDATE_EVENTLINK;
	}

	function get_createupdate() {

		return new GD_CreateUpdate_EventLink();
	}

	// function modify_data_settings(&$block_data_settings, $post_id) {

	// 	// Modify the block-data-settings, saying to select the id of the newly created post
	// 	$block_data_settings['dataload-atts']['include'] = array($post_id);
	// 	$block_data_settings['dataload-atts']['status'] = array('pending', 'draft', 'published');
	// }
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoad_ActionExecuter_CreateUpdate_EventLink();