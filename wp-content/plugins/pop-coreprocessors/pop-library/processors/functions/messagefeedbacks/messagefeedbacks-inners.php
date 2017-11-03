<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_MESSAGEFEEDBACKINNER_FOLLOWUSER', PoP_TemplateIDUtils::get_template_definition('messagefeedbackinner-followuser'));
define ('GD_TEMPLATE_MESSAGEFEEDBACKINNER_UNFOLLOWUSER', PoP_TemplateIDUtils::get_template_definition('messagefeedbackinner-unfollowuser'));
define ('GD_TEMPLATE_MESSAGEFEEDBACKINNER_RECOMMENDPOST', PoP_TemplateIDUtils::get_template_definition('messagefeedbackinner-recommendpost'));
define ('GD_TEMPLATE_MESSAGEFEEDBACKINNER_UNRECOMMENDPOST', PoP_TemplateIDUtils::get_template_definition('messagefeedbackinner-unrecommendpost'));
define ('GD_TEMPLATE_MESSAGEFEEDBACKINNER_SUBSCRIBETOTAG', PoP_TemplateIDUtils::get_template_definition('messagefeedbackinner-subscribetotag'));
define ('GD_TEMPLATE_MESSAGEFEEDBACKINNER_UNSUBSCRIBEFROMTAG', PoP_TemplateIDUtils::get_template_definition('messagefeedbackinner-unsubscribefromtag'));
define ('GD_TEMPLATE_MESSAGEFEEDBACKINNER_UPVOTEPOST', PoP_TemplateIDUtils::get_template_definition('messagefeedbackinner-upvotepost'));
define ('GD_TEMPLATE_MESSAGEFEEDBACKINNER_UNDOUPVOTEPOST', PoP_TemplateIDUtils::get_template_definition('messagefeedbackinner-undoupvotepost'));
define ('GD_TEMPLATE_MESSAGEFEEDBACKINNER_DOWNVOTEPOST', PoP_TemplateIDUtils::get_template_definition('messagefeedbackinner-downvotepost'));
define ('GD_TEMPLATE_MESSAGEFEEDBACKINNER_UNDODOWNVOTEPOST', PoP_TemplateIDUtils::get_template_definition('messagefeedbackinner-undodownvotepost'));

class GD_Template_Processor_FunctionMessageFeedbackInners extends GD_Template_Processor_MessageFeedbackInnersBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_FOLLOWUSER,
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_UNFOLLOWUSER,
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_RECOMMENDPOST,
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_UNRECOMMENDPOST,
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_SUBSCRIBETOTAG,
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_UNSUBSCRIBEFROMTAG,
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_UPVOTEPOST,
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_UNDOUPVOTEPOST,
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_DOWNVOTEPOST,
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_UNDODOWNVOTEPOST,
		);
	}

	function get_layouts($template_id) {

		$ret = parent::get_layouts($template_id);

		$layouts = array(
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_FOLLOWUSER => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_FOLLOWUSER,
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_UNFOLLOWUSER => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_UNFOLLOWUSER,
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_RECOMMENDPOST => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_RECOMMENDPOST,
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_UNRECOMMENDPOST => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_UNRECOMMENDPOST,
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_SUBSCRIBETOTAG => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_SUBSCRIBETOTAG,
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_UNSUBSCRIBEFROMTAG => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_UNSUBSCRIBEFROMTAG,
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_UPVOTEPOST => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_UPVOTEPOST,
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_UNDOUPVOTEPOST => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_UNDOUPVOTEPOST,
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_DOWNVOTEPOST => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_DOWNVOTEPOST,
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_UNDODOWNVOTEPOST => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_UNDODOWNVOTEPOST,
		);

		if ($layout = $layouts[$template_id]) {

			$ret[] = $layout;
		}

		return $ret;
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_FunctionMessageFeedbackInners();