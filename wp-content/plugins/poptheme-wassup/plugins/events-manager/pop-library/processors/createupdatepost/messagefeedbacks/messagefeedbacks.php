<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_MESSAGEFEEDBACK_EVENT_CREATE', PoP_TemplateIDUtils::get_template_definition('messagefeedback-event-create'));
define ('GD_TEMPLATE_MESSAGEFEEDBACK_EVENT_UPDATE', PoP_TemplateIDUtils::get_template_definition('messagefeedback-event-update'));

class GD_EM_Template_Processor_CreateUpdatePostFormMessageFeedbacks extends GD_Template_Processor_MessageFeedbacksBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_MESSAGEFEEDBACK_EVENT_CREATE,
			GD_TEMPLATE_MESSAGEFEEDBACK_EVENT_UPDATE,
		);
	}

	function get_inner_template($template_id) {

		$inners = array(
			GD_TEMPLATE_MESSAGEFEEDBACK_EVENT_CREATE => GD_TEMPLATE_MESSAGEFEEDBACKINNER_EVENT_CREATE,
			GD_TEMPLATE_MESSAGEFEEDBACK_EVENT_UPDATE => GD_TEMPLATE_MESSAGEFEEDBACKINNER_EVENT_UPDATE,
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
new GD_EM_Template_Processor_CreateUpdatePostFormMessageFeedbacks();