<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_ACTION_FOLLOWUSER', PoP_ServerUtils::get_template_definition('action-followuser'));
define ('GD_TEMPLATE_ACTION_UNFOLLOWUSER', PoP_ServerUtils::get_template_definition('action-unfollowuser'));
define ('GD_TEMPLATE_ACTION_RECOMMENDPOST', PoP_ServerUtils::get_template_definition('action-recommendpost'));
define ('GD_TEMPLATE_ACTION_UNRECOMMENDPOST', PoP_ServerUtils::get_template_definition('action-unrecommendpost'));
define ('GD_TEMPLATE_ACTION_UPVOTEPOST', PoP_ServerUtils::get_template_definition('action-upvotepost'));
define ('GD_TEMPLATE_ACTION_UNDOUPVOTEPOST', PoP_ServerUtils::get_template_definition('action-undoupvotepost'));
define ('GD_TEMPLATE_ACTION_DOWNVOTEPOST', PoP_ServerUtils::get_template_definition('action-downvotepost'));
define ('GD_TEMPLATE_ACTION_UNDODOWNVOTEPOST', PoP_ServerUtils::get_template_definition('action-undodownvotepost'));

class GD_Template_Processor_FunctionsActions extends GD_Template_Processor_ActionsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_ACTION_FOLLOWUSER,
			GD_TEMPLATE_ACTION_UNFOLLOWUSER,
			GD_TEMPLATE_ACTION_RECOMMENDPOST,
			GD_TEMPLATE_ACTION_UNRECOMMENDPOST,
			GD_TEMPLATE_ACTION_UPVOTEPOST,
			GD_TEMPLATE_ACTION_UNDOUPVOTEPOST,
			GD_TEMPLATE_ACTION_DOWNVOTEPOST,
			GD_TEMPLATE_ACTION_UNDODOWNVOTEPOST,
		);
	}

	protected function get_actionexecuter($template_id) {

		$executers = array(
			GD_TEMPLATE_ACTION_FOLLOWUSER => GD_DATALOAD_ACTIONEXECUTER_FOLLOWUSER,
			GD_TEMPLATE_ACTION_UNFOLLOWUSER => GD_DATALOAD_ACTIONEXECUTER_UNFOLLOWUSER,
			GD_TEMPLATE_ACTION_RECOMMENDPOST => GD_DATALOAD_ACTIONEXECUTER_RECOMMENDPOST,
			GD_TEMPLATE_ACTION_UNRECOMMENDPOST => GD_DATALOAD_ACTIONEXECUTER_UNRECOMMENDPOST,
			GD_TEMPLATE_ACTION_UPVOTEPOST => GD_DATALOAD_ACTIONEXECUTER_UPVOTEPOST,
			GD_TEMPLATE_ACTION_UNDOUPVOTEPOST => GD_DATALOAD_ACTIONEXECUTER_UNDOUPVOTEPOST,
			GD_TEMPLATE_ACTION_DOWNVOTEPOST => GD_DATALOAD_ACTIONEXECUTER_DOWNVOTEPOST,
			GD_TEMPLATE_ACTION_UNDODOWNVOTEPOST => GD_DATALOAD_ACTIONEXECUTER_UNDODOWNVOTEPOST,
		);
		if ($executer = $executers[$template_id]) {

			return $executer;
		}

		return parent::get_actionexecuter($template_id);
	}

	protected function get_messagefeedback($template_id) {

		$messagefeedbacks = array(
			GD_TEMPLATE_ACTION_FOLLOWUSER => GD_TEMPLATE_MESSAGEFEEDBACK_FOLLOWUSER,
			GD_TEMPLATE_ACTION_UNFOLLOWUSER => GD_TEMPLATE_MESSAGEFEEDBACK_UNFOLLOWUSER,
			GD_TEMPLATE_ACTION_RECOMMENDPOST => GD_TEMPLATE_MESSAGEFEEDBACK_RECOMMENDPOST,
			GD_TEMPLATE_ACTION_UNRECOMMENDPOST => GD_TEMPLATE_MESSAGEFEEDBACK_UNRECOMMENDPOST,
			GD_TEMPLATE_ACTION_UPVOTEPOST => GD_TEMPLATE_MESSAGEFEEDBACK_UPVOTEPOST,
			GD_TEMPLATE_ACTION_UNDOUPVOTEPOST => GD_TEMPLATE_MESSAGEFEEDBACK_UNDOUPVOTEPOST,
			GD_TEMPLATE_ACTION_DOWNVOTEPOST => GD_TEMPLATE_MESSAGEFEEDBACK_DOWNVOTEPOST,
			GD_TEMPLATE_ACTION_UNDODOWNVOTEPOST => GD_TEMPLATE_MESSAGEFEEDBACK_UNDODOWNVOTEPOST,
		);
		if ($messagefeedback = $messagefeedbacks[$template_id]) {

			return $messagefeedback;
		}

		return parent::get_messagefeedback($template_id);
	}

	protected function get_iohandler($template_id) {

		switch ($template_id) {
					
			case GD_TEMPLATE_ACTION_FOLLOWUSER:
			case GD_TEMPLATE_ACTION_UNFOLLOWUSER:
			case GD_TEMPLATE_ACTION_RECOMMENDPOST:
			case GD_TEMPLATE_ACTION_UNRECOMMENDPOST:
			case GD_TEMPLATE_ACTION_UPVOTEPOST:
			case GD_TEMPLATE_ACTION_UNDOUPVOTEPOST:
			case GD_TEMPLATE_ACTION_DOWNVOTEPOST:
			case GD_TEMPLATE_ACTION_UNDODOWNVOTEPOST:

				// return GD_DATALOAD_IOHANDLER_NOHEADERFORM;
				return GD_DATALOAD_IOHANDLER_FORM;
		}
		
		return parent::get_iohandler($template_id);
	}

	protected function add_clearfixdiv($template_id) {

		// The clearfix div is not allowing the messagefeedbacks to move up when the one above is closed
		switch ($template_id) {
					
			case GD_TEMPLATE_ACTION_FOLLOWUSER:
			case GD_TEMPLATE_ACTION_UNFOLLOWUSER:
			case GD_TEMPLATE_ACTION_RECOMMENDPOST:
			case GD_TEMPLATE_ACTION_UNRECOMMENDPOST:
			case GD_TEMPLATE_ACTION_UPVOTEPOST:
			case GD_TEMPLATE_ACTION_UNDOUPVOTEPOST:
			case GD_TEMPLATE_ACTION_DOWNVOTEPOST:
			case GD_TEMPLATE_ACTION_UNDODOWNVOTEPOST:

				return false;
		}
	
		return parent::add_clearfixdiv($template_id);
	}
	
	function init_atts($template_id, &$atts) {
	
		switch ($template_id) {
				
			case GD_TEMPLATE_ACTION_FOLLOWUSER:
			case GD_TEMPLATE_ACTION_UNFOLLOWUSER:
			case GD_TEMPLATE_ACTION_RECOMMENDPOST:
			case GD_TEMPLATE_ACTION_UNRECOMMENDPOST:
			case GD_TEMPLATE_ACTION_UPVOTEPOST:
			case GD_TEMPLATE_ACTION_UNDOUPVOTEPOST:
			case GD_TEMPLATE_ACTION_DOWNVOTEPOST:
			case GD_TEMPLATE_ACTION_UNDODOWNVOTEPOST:

				$this->append_att($template_id, $atts, 'class', 'pop-functionalblock');	
				break;
		}
		
		return parent::init_atts($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_FunctionsActions();