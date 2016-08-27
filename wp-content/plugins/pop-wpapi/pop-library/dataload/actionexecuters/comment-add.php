<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_DATALOAD_ACTIONEXECUTER_ADDCOMMENT', 'comment-add');

class GD_DataLoad_ActionExecuter_AddComment extends GD_DataLoad_ActionExecuter {

    function get_name() {
    
		return GD_DATALOAD_ACTIONEXECUTER_ADDCOMMENT;
	}

    function execute(&$block_data_settings, $block_atts, &$block_execution_bag) {

		// If the post has been submitted, execute the Gravity Forms shortcode
		if ('POST' == $_SERVER['REQUEST_METHOD']) {

			global $gd_addcomment;
			$errors = array();
			$comment_id = $gd_addcomment->addcomment($errors, $block_atts);

			if ($errors) {

				return array(
					GD_DATALOAD_IOHANDLER_FORM_ERRORSTRINGS => $errors
				);
			}
			
			// Modify the block-data-settings, saying to select the id of the newly created comment
			// Comment Leo 09/10/2014: this will be done through template-manager.php/integrateExecution
			// $block_data_settings['dataload-atts']['include'] = $comment_id;
			$block_execution_bag['dataset'] = array($comment_id);

			// No errors => success
			return array(
				GD_DATALOAD_IOHANDLER_FORM_SUCCESS => true
			);			
		}

		return parent::execute($block_data_settings, $block_atts, $block_execution_bag);
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoad_ActionExecuter_AddComment();