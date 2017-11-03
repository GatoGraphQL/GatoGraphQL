<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_MESSAGEFEEDBACK_COMMENTS', PoP_TemplateIDUtils::get_template_definition('messagefeedback-comments'));
define ('GD_TEMPLATE_MESSAGEFEEDBACK_ADDCOMMENT', PoP_TemplateIDUtils::get_template_definition('messagefeedback-addcomment'));

class GD_Template_Processor_CommentsMessageFeedbacks extends GD_Template_Processor_MessageFeedbacksBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_MESSAGEFEEDBACK_COMMENTS,
			GD_TEMPLATE_MESSAGEFEEDBACK_ADDCOMMENT,
		);
	}

	function get_inner_template($template_id) {

		$inners = array(
			GD_TEMPLATE_MESSAGEFEEDBACK_COMMENTS => GD_TEMPLATE_MESSAGEFEEDBACKINNER_COMMENTS,
			GD_TEMPLATE_MESSAGEFEEDBACK_ADDCOMMENT => GD_TEMPLATE_MESSAGEFEEDBACKINNER_ADDCOMMENT,
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
new GD_Template_Processor_CommentsMessageFeedbacks();