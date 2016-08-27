<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_DATALOAD_ACTIONEXECUTER_UNDOUPVOTEPOST', 'undo-upvotepost');

class GD_DataLoad_ActionExecuter_UndoUpvotePost extends GD_DataLoad_ActionExecuter {

	function get_name() {

		return GD_DATALOAD_ACTIONEXECUTER_UNDOUPVOTEPOST;
	}

	function execute(&$block_data_settings, $block_atts, &$block_execution_bag) {

		global $gd_undoupvotepost;
		$errors = array();
		$target_id = $gd_undoupvotepost->execute($errors, $block_atts);

		if ($errors) {

			return array(
				GD_DATALOAD_IOHANDLER_FORM_ERRORSTRINGS => $errors
			);
		}
		
		$block_execution_bag['dataset'] = array($target_id);
		$success_msg = sprintf(
			__('You have stopped up-voting <em><strong>%s</strong></em>.', 'pop-coreprocessors'),
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
new GD_DataLoad_ActionExecuter_UndoUpvotePost();