<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_FOLLOWUSER', PoP_TemplateIDUtils::get_template_definition('layout-messagefeedbackframe-followuser'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_UNFOLLOWUSER', PoP_TemplateIDUtils::get_template_definition('layout-messagefeedbackframe-unfollowuser'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_RECOMMENDPOST', PoP_TemplateIDUtils::get_template_definition('layout-messagefeedbackframe-recommendpost'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_UNRECOMMENDPOST', PoP_TemplateIDUtils::get_template_definition('layout-messagefeedbackframe-unrecommendpost'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_SUBSCRIBETOTAG', PoP_TemplateIDUtils::get_template_definition('layout-messagefeedbackframe-subscribetotag'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_UNSUBSCRIBEFROMTAG', PoP_TemplateIDUtils::get_template_definition('layout-messagefeedbackframe-unsubscribefromtag'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_UPVOTEPOST', PoP_TemplateIDUtils::get_template_definition('layout-messagefeedbackframe-upvotepost'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_UNDOUPVOTEPOST', PoP_TemplateIDUtils::get_template_definition('layout-messagefeedbackframe-undoupvotepost'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_DOWNVOTEPOST', PoP_TemplateIDUtils::get_template_definition('layout-messagefeedbackframe-downvotepost'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_UNDODOWNVOTEPOST', PoP_TemplateIDUtils::get_template_definition('layout-messagefeedbackframe-undodownvotepost'));

class GD_Template_Processor_FunctionMessageFeedbackFrameLayouts extends GD_Template_Processor_MessageFeedbackFrameLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_FOLLOWUSER,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_UNFOLLOWUSER,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_RECOMMENDPOST,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_UNRECOMMENDPOST,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_SUBSCRIBETOTAG,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_UNSUBSCRIBEFROMTAG,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_UPVOTEPOST,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_UNDOUPVOTEPOST,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_DOWNVOTEPOST,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_UNDODOWNVOTEPOST,
		);
	}

	function get_layout($template_id) {

		$layouts = array(
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_FOLLOWUSER => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_FOLLOWUSER,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_UNFOLLOWUSER => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_UNFOLLOWUSER,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_RECOMMENDPOST => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_RECOMMENDPOST,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_UNRECOMMENDPOST => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_UNRECOMMENDPOST,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_SUBSCRIBETOTAG => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_SUBSCRIBETOTAG,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_UNSUBSCRIBEFROMTAG => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_UNSUBSCRIBEFROMTAG,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_UPVOTEPOST => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_UPVOTEPOST,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_UNDOUPVOTEPOST => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_UNDOUPVOTEPOST,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_DOWNVOTEPOST => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_DOWNVOTEPOST,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_UNDODOWNVOTEPOST => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_UNDODOWNVOTEPOST,
		);

		if ($layout = $layouts[$template_id]) {

			return $layout;
		}

		return parent::get_layout($template_id);
	}

	function get_block_jsmethod($template_id, $atts) {

		$ret = parent::get_block_jsmethod($template_id, $atts);
		
		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_FOLLOWUSER:
			case GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_UNFOLLOWUSER:
			case GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_RECOMMENDPOST:
			case GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_UNRECOMMENDPOST:
			case GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_SUBSCRIBETOTAG:
			case GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_UNSUBSCRIBEFROMTAG:
			case GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_UPVOTEPOST:
			case GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_UNDOUPVOTEPOST:
			case GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_DOWNVOTEPOST:
			case GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_UNDODOWNVOTEPOST:
			
				$this->add_jsmethod($ret, 'alertCloseOnTimeout');
				break;
		}

		return $ret;
	}

	function init_atts($template_id, &$atts) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_FOLLOWUSER:
			case GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_UNFOLLOWUSER:
			case GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_RECOMMENDPOST:
			case GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_UNRECOMMENDPOST:
			case GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_SUBSCRIBETOTAG:
			case GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_UNSUBSCRIBEFROMTAG:
			case GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_UPVOTEPOST:
			case GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_UNDOUPVOTEPOST:
			case GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_DOWNVOTEPOST:
			case GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_UNDODOWNVOTEPOST:

				$this->merge_att($template_id, $atts, 'params', array(
					'data-closetime' => 3500,
				));
				break;
		}
			
		return parent::init_atts($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_FunctionMessageFeedbackFrameLayouts();