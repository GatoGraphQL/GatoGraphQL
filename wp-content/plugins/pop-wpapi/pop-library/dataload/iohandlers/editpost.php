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
		$ret[GD_DATALOAD_PARAMS]['_wpnonce'] = gd_create_nonce(GD_NONCE_EDITURL, $pid);
		
		return $ret;
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoad_IOHandler_EditPost();
