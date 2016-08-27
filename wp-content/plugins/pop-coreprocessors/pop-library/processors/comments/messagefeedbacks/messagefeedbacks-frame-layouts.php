<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_COMMENTS', PoP_ServerUtils::get_template_definition('layout-messagefeedbackframe-comments'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_ADDCOMMENT', PoP_ServerUtils::get_template_definition('layout-messagefeedbackframe-addcomment'));

class GD_Template_Processor_CommentsMessageFeedbackFrameLayouts extends GD_Template_Processor_MessageFeedbackFrameLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_COMMENTS,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_ADDCOMMENT,
		);
	}

	function get_layout($template_id) {

		$layouts = array(
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_COMMENTS => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_COMMENTS,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_ADDCOMMENT => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_ADDCOMMENT,
		);

		if ($layout = $layouts[$template_id]) {

			return $layout;
		}

		return parent::get_layout($template_id);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_CommentsMessageFeedbackFrameLayouts();