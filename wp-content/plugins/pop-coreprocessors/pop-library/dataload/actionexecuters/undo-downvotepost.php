<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_DATALOAD_ACTIONEXECUTER_UNDODOWNVOTEPOST', 'undo-downvotepost');

class GD_DataLoad_ActionExecuter_UndoDownvotePost extends GD_DataLoad_ActionExecuter {

	function get_name() {

		return GD_DATALOAD_ACTIONEXECUTER_UNDODOWNVOTEPOST;
	}

	function execute(&$block_data_settings, $block_atts, &$block_execution_bag) {

		global $gd_undodownvotepost;
		$errors = array();
		$target_id = $gd_undodownvotepost->execute($errors, $block_atts);

		if ($errors) {

			return array(
				GD_DATALOAD_IOHANDLER_FORM_ERRORSTRINGS => $errors
			);
		}
		
		$block_execution_bag['dataset'] = array($target_id);
		$success_msg = sprintf(
			__('You have stopped down-voting <em><strong>%s</strong></em>.', 'pop-coreprocessors'),
			get_the_title($target_id)
		);

		// No errors => success
		return array(
			GD_DATALOAD_IOHANDLER_FORM_SUCCESSSTRINGS => array($success_msg), 
			GD_DATALOAD_IOHANDLER_FORM_SUCCESS => true
		);			
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoad_ActionExecuter_UndoDownvotePost();