<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_MESSAGEFEEDBACK_WEBPOSTLINK_CREATE', PoP_TemplateIDUtils::get_template_definition('messagefeedback-webpostlink-create'));
define ('GD_TEMPLATE_MESSAGEFEEDBACK_WEBPOSTLINK_UPDATE', PoP_TemplateIDUtils::get_template_definition('messagefeedback-webpostlink-update'));
define ('GD_TEMPLATE_MESSAGEFEEDBACK_HIGHLIGHT_CREATE', PoP_TemplateIDUtils::get_template_definition('messagefeedback-highlight-create'));
define ('GD_TEMPLATE_MESSAGEFEEDBACK_HIGHLIGHT_UPDATE', PoP_TemplateIDUtils::get_template_definition('messagefeedback-highlight-update'));
define ('GD_TEMPLATE_MESSAGEFEEDBACK_WEBPOST_CREATE', PoP_TemplateIDUtils::get_template_definition('messagefeedback-webpost-create'));
define ('GD_TEMPLATE_MESSAGEFEEDBACK_WEBPOST_UPDATE', PoP_TemplateIDUtils::get_template_definition('messagefeedback-webpost-update'));

class Wassup_Template_Processor_CreateUpdatePostFormMessageFeedbacks extends GD_Template_Processor_MessageFeedbacksBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_MESSAGEFEEDBACK_WEBPOSTLINK_CREATE,
			GD_TEMPLATE_MESSAGEFEEDBACK_WEBPOSTLINK_UPDATE,
			GD_TEMPLATE_MESSAGEFEEDBACK_HIGHLIGHT_CREATE,
			GD_TEMPLATE_MESSAGEFEEDBACK_HIGHLIGHT_UPDATE,
			GD_TEMPLATE_MESSAGEFEEDBACK_WEBPOST_CREATE,
			GD_TEMPLATE_MESSAGEFEEDBACK_WEBPOST_UPDATE,
		);
	}

	function get_inner_template($template_id) {

		$inners = array(
			GD_TEMPLATE_MESSAGEFEEDBACK_WEBPOSTLINK_CREATE => GD_TEMPLATE_MESSAGEFEEDBACKINNER_LINK_CREATE,
			GD_TEMPLATE_MESSAGEFEEDBACK_WEBPOSTLINK_UPDATE => GD_TEMPLATE_MESSAGEFEEDBACKINNER_LINK_UPDATE,
			GD_TEMPLATE_MESSAGEFEEDBACK_HIGHLIGHT_CREATE => GD_TEMPLATE_MESSAGEFEEDBACKINNER_HIGHLIGHT_CREATE,
			GD_TEMPLATE_MESSAGEFEEDBACK_HIGHLIGHT_UPDATE => GD_TEMPLATE_MESSAGEFEEDBACKINNER_HIGHLIGHT_UPDATE,
			GD_TEMPLATE_MESSAGEFEEDBACK_WEBPOST_CREATE => GD_TEMPLATE_MESSAGEFEEDBACKINNER_WEBPOST_CREATE,
			GD_TEMPLATE_MESSAGEFEEDBACK_WEBPOST_UPDATE => GD_TEMPLATE_MESSAGEFEEDBACKINNER_WEBPOST_UPDATE,
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
new Wassup_Template_Processor_CreateUpdatePostFormMessageFeedbacks();