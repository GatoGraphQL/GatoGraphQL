<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_DATALOAD_CHECKPOINTS', 'checkpoints');
define ('GD_DATALOAD_VALIDATECHECKPOINTS', 'validate-checkpoints');

// These 2 types are needed basically to tell if the block in the front-end needs to refetch the data from the server or not.
// Eg: non-loggedin user checks My Events block, gives error, then logs in, then goes back to My Events, it must refetch the "my events" data
// But this doesn't happen with "Create new Event", there's nothing to refetch in this case
define ('GD_DATALOAD_VALIDATECHECKPOINTS_TYPE_STATIC', 'static');
define ('GD_DATALOAD_VALIDATECHECKPOINTS_TYPE_DATAFROMSERVER', 'datafromserver');
define ('GD_DATALOAD_VALIDATECHECKPOINTS_TYPE_STATELESS', 'stateless');
define ('GD_DATALOAD_NOCHECKPOINTVALIDATION_TYPE_DATAFROMSERVER', 'datafromserver-novalidation');

// add_filter('gd_jquery_constants', 'gd_jquery_constants_checkpointiohandler');
// function gd_jquery_constants_checkpointiohandler($jquery_constants) {

// 	$jquery_constants['CHECKPOINTSTYPE_STATIC'] = GD_DATALOAD_VALIDATECHECKPOINTS_TYPE_STATIC;	
// 	$jquery_constants['CHECKPOINTSTYPE_DATAFROMSERVER'] = GD_DATALOAD_VALIDATECHECKPOINTS_TYPE_DATAFROMSERVER;	

// 	return $jquery_constants;
// }

class GD_DataLoad_CheckpointIOHandler extends GD_DataLoad_IOHandler {

	function get_feedback($checkpoint, $dataset, $vars_atts, $iohandler_atts, $data_settings, $executed = null, $atts) {
	
		$ret = parent::get_feedback($checkpoint, $dataset, $vars_atts, $iohandler_atts, $data_settings, $executed, $atts);

		// This is needed also when bringing block data, that's why it's placed under CheckpointIOHandler instead of TopLevelIOHandler
		$ret[POP_UNIQUEID] = POP_CONSTANT_UNIQUE_ID;

		$show_msgs = false;
		if (is_wp_error($checkpoint)) {

			// 'show-msg' needed since adding $.extend on the feedback: the previous msg might still be there, but we don't want to show it
			$show_msgs = true;
			$msg = array();
			$msg['icon'] = 'glyphicon-warning-sign';
			$msg['header']['code'] = 'checkpoint-error-header';
			$msg['codes'] = array(
				$checkpoint->get_error_code()
			);
							
			$msg['can-close'] = true;
			$msg['class'] = 'alert-warning checkpoint';
			$msgs[] = $msg;
		}
		if ($msgs) {
			$ret['msgs'] = $msgs;
		}
		$ret['show-msgs'] = $show_msgs;

		// Allow to add extra information. Eg: add the logged-in user info under pop-wpapi
		$ret = apply_filters('GD_DataLoad_CheckpointIOHandler:feedback', $ret, $checkpoint, $dataset, $vars_atts, $iohandler_atts, $executed, $atts);
		
		return $ret;
	}

	function checkpoint($vars_atts, $iohandler_atts, $atts) {

		global $gd_dataload_checkpointprocessor_manager;

		$checkpoints = $iohandler_atts[GD_DATALOAD_CHECKPOINTS];

		// Iterate through the list of all checkpoints, process all of them, if any produces an error, already return it
		foreach ($checkpoints as $checkpoint) {

			$result = $gd_dataload_checkpointprocessor_manager->process($checkpoint);
			if (is_wp_error($result)) {
				return $result;
			}
		}

		return true;
	}
}
	
