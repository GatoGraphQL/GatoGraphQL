<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_DATALOAD_IOHANDLER_EDITMEMBERSHIP', 'editmembership');

class GD_DataLoad_IOHandler_EditMembership extends GD_DataLoad_IOHandler_Form {

    function get_name() {
    
		return GD_DATALOAD_IOHANDLER_EDITMEMBERSHIP;
	}

	function get_general_querystate($checkpoint, $dataset, $vars_atts, $iohandler_atts, $executed = null, $atts) {
	
		$ret = parent::get_general_querystate($checkpoint, $dataset, $vars_atts, $iohandler_atts, $executed, $atts);

		$uid = $_REQUEST['uid'];
		$ret[GD_DATALOAD_PARAMS]['uid'] = $uid;
		$ret[GD_DATALOAD_PARAMS]['_wpnonce'] = gd_create_nonce(GD_NONCE_EDITMEMBERSHIPURL, $uid);
		
		return $ret;
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoad_IOHandler_EditMembership();
