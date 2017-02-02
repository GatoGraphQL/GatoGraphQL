<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_DATALOAD_ACTIONEXECUTER_UNSUBSCRIBEFROMTAG', 'unsubscribefromtag');

class GD_DataLoad_ActionExecuter_UnsubscribeFromTag extends GD_DataLoad_ActionExecuter {

	function get_name() {

		return GD_DATALOAD_ACTIONEXECUTER_UNSUBSCRIBEFROMTAG;
	}

	function execute(&$block_data_settings, $block_atts, &$block_execution_bag) {

		global $gd_unsubscribefromtag;
		$errors = array();
		$target_id = $gd_unsubscribefromtag->execute($errors, $block_atts);

		if ($errors) {

			return array(
				GD_DATALOAD_IOHANDLER_FORM_ERRORSTRINGS => $errors
			);
		}
		
		$block_execution_bag['dataset'] = array($target_id);
		$tag = get_tag($target_id);
		$success_msg = sprintf(
			__('You have unsubscribed from <em><strong>%s</strong></em>.', 'pop-coreprocessors'),
			PoP_TagUtils::get_tag_symbol().$tag->name
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
new GD_DataLoad_ActionExecuter_UnsubscribeFromTag();