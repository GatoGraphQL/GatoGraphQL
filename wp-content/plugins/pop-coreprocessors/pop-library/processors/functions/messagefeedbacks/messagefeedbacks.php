<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_MESSAGEFEEDBACK_FOLLOWUSER', PoP_TemplateIDUtils::get_template_definition('messagefeedback-followuser'));
define ('GD_TEMPLATE_MESSAGEFEEDBACK_UNFOLLOWUSER', PoP_TemplateIDUtils::get_template_definition('messagefeedback-unfollowuser'));
define ('GD_TEMPLATE_MESSAGEFEEDBACK_RECOMMENDPOST', PoP_TemplateIDUtils::get_template_definition('messagefeedback-recommendpost'));
define ('GD_TEMPLATE_MESSAGEFEEDBACK_UNRECOMMENDPOST', PoP_TemplateIDUtils::get_template_definition('messagefeedback-unrecommendpost'));
define ('GD_TEMPLATE_MESSAGEFEEDBACK_SUBSCRIBETOTAG', PoP_TemplateIDUtils::get_template_definition('messagefeedback-subscribetotag'));
define ('GD_TEMPLATE_MESSAGEFEEDBACK_UNSUBSCRIBEFROMTAG', PoP_TemplateIDUtils::get_template_definition('messagefeedback-unsubscribefromtag'));
define ('GD_TEMPLATE_MESSAGEFEEDBACK_UPVOTEPOST', PoP_TemplateIDUtils::get_template_definition('messagefeedback-upvotepost'));
define ('GD_TEMPLATE_MESSAGEFEEDBACK_UNDOUPVOTEPOST', PoP_TemplateIDUtils::get_template_definition('messagefeedback-undoupvotepost'));
define ('GD_TEMPLATE_MESSAGEFEEDBACK_DOWNVOTEPOST', PoP_TemplateIDUtils::get_template_definition('messagefeedback-downvotepost'));
define ('GD_TEMPLATE_MESSAGEFEEDBACK_UNDODOWNVOTEPOST', PoP_TemplateIDUtils::get_template_definition('messagefeedback-undodownvotepost'));

class GD_Template_Processor_FunctionMessageFeedbacks extends GD_Template_Processor_MessageFeedbacksBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_MESSAGEFEEDBACK_FOLLOWUSER,
			GD_TEMPLATE_MESSAGEFEEDBACK_UNFOLLOWUSER,
			GD_TEMPLATE_MESSAGEFEEDBACK_RECOMMENDPOST,
			GD_TEMPLATE_MESSAGEFEEDBACK_UNRECOMMENDPOST,
			GD_TEMPLATE_MESSAGEFEEDBACK_SUBSCRIBETOTAG,
			GD_TEMPLATE_MESSAGEFEEDBACK_UNSUBSCRIBEFROMTAG,
			GD_TEMPLATE_MESSAGEFEEDBACK_UPVOTEPOST,
			GD_TEMPLATE_MESSAGEFEEDBACK_UNDOUPVOTEPOST,
			GD_TEMPLATE_MESSAGEFEEDBACK_DOWNVOTEPOST,
			GD_TEMPLATE_MESSAGEFEEDBACK_UNDODOWNVOTEPOST,
		);
	}

	function get_inner_template($template_id) {

		$inners = array(
			GD_TEMPLATE_MESSAGEFEEDBACK_FOLLOWUSER => GD_TEMPLATE_MESSAGEFEEDBACKINNER_FOLLOWUSER,
			GD_TEMPLATE_MESSAGEFEEDBACK_UNFOLLOWUSER => GD_TEMPLATE_MESSAGEFEEDBACKINNER_UNFOLLOWUSER,
			GD_TEMPLATE_MESSAGEFEEDBACK_RECOMMENDPOST => GD_TEMPLATE_MESSAGEFEEDBACKINNER_RECOMMENDPOST,
			GD_TEMPLATE_MESSAGEFEEDBACK_UNRECOMMENDPOST => GD_TEMPLATE_MESSAGEFEEDBACKINNER_UNRECOMMENDPOST,
			GD_TEMPLATE_MESSAGEFEEDBACK_SUBSCRIBETOTAG => GD_TEMPLATE_MESSAGEFEEDBACKINNER_SUBSCRIBETOTAG,
			GD_TEMPLATE_MESSAGEFEEDBACK_UNSUBSCRIBEFROMTAG => GD_TEMPLATE_MESSAGEFEEDBACKINNER_UNSUBSCRIBEFROMTAG,
			GD_TEMPLATE_MESSAGEFEEDBACK_UPVOTEPOST => GD_TEMPLATE_MESSAGEFEEDBACKINNER_UPVOTEPOST,
			GD_TEMPLATE_MESSAGEFEEDBACK_UNDOUPVOTEPOST => GD_TEMPLATE_MESSAGEFEEDBACKINNER_UNDOUPVOTEPOST,
			GD_TEMPLATE_MESSAGEFEEDBACK_DOWNVOTEPOST => GD_TEMPLATE_MESSAGEFEEDBACKINNER_DOWNVOTEPOST,
			GD_TEMPLATE_MESSAGEFEEDBACK_UNDODOWNVOTEPOST => GD_TEMPLATE_MESSAGEFEEDBACKINNER_UNDODOWNVOTEPOST,
		);

		if ($inner = $inners[$template_id]) {

			return $inner;
		}

		return parent::get_inner_template($template_id);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_FunctionMessageFeedbacks();