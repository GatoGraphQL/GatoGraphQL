<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_DATALOAD_ACTIONEXECUTER_CREATEUPDATE_EVENT', 'createupdate-event');

class GD_DataLoad_ActionExecuter_CreateUpdate_Event extends GD_DataLoad_ActionExecuter_CreateUpdate_Post {

    function get_name() {
    
		return GD_DATALOAD_ACTIONEXECUTER_CREATEUPDATE_EVENT;
	}

	function get_createupdate() {

		// global $gd_createupdate_event;
		// return $gd_createupdate_event;
		return new GD_CreateUpdate_Event();
	}

	function modify_data_settings(&$block_data_settings, $post_id) {

		// Modify the block-data-settings, saying to select the id of the newly created post
		$block_data_settings['dataload-atts']['include'] = array($post_id);
		$block_data_settings['dataload-atts']['status'] = array('pending', 'draft', 'published');
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoad_ActionExecuter_CreateUpdate_Event();