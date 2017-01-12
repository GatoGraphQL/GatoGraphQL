<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_DATALOAD_IOHANDLER_EDITPOST', 'editpost');

class GD_DataLoad_IOHandler_EditPost extends GD_DataLoad_IOHandler_Form {

    function get_name() {
    
		return GD_DATALOAD_IOHANDLER_EDITPOST;
	}

	function get_params($checkpoint, $dataset, $vars_atts, $iohandler_atts, $executed = null, $atts) {
	
		$ret = parent::get_params($checkpoint, $dataset, $vars_atts, $iohandler_atts, $executed, $atts);

		$pid = $_REQUEST['pid'];
		$ret[GD_DATALOAD_PARAMS]['pid'] = $pid;

		// If the user is sending the '_wpnonce', because has sent a POST editing a post, then use that one, and make the nonce validation with it
		// $ret[GD_DATALOAD_PARAMS]['_wpnonce'] = isset($_REQUEST['_wpnonce']) ? $_REQUEST['_wpnonce'] : gd_create_nonce(GD_NONCE_EDITURL, $pid);
		// The nonce must be passed already in the link, otherwise it will not work
		$ret[GD_DATALOAD_PARAMS]['_wpnonce'] = $_REQUEST['_wpnonce'] ?? '';
		
		return $ret;
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoad_IOHandler_EditPost();
