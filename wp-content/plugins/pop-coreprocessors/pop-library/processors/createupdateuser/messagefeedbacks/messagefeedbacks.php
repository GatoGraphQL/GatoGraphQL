<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_MESSAGEFEEDBACK_USER_CHANGEPASSWORD', PoP_TemplateIDUtils::get_template_definition('messagefeedback-user-changepassword'));
define ('GD_TEMPLATE_MESSAGEFEEDBACK_MYPREFERENCES', PoP_TemplateIDUtils::get_template_definition('messagefeedback-mypreferences'));

class GD_Template_Processor_UserMessageFeedbacks extends GD_Template_Processor_MessageFeedbacksBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_MESSAGEFEEDBACK_USER_CHANGEPASSWORD,
			GD_TEMPLATE_MESSAGEFEEDBACK_MYPREFERENCES,
		);
	}

	function get_inner_template($template_id) {

		$inners = array(
			GD_TEMPLATE_MESSAGEFEEDBACK_USER_CHANGEPASSWORD => GD_TEMPLATE_MESSAGEFEEDBACKINNER_USER_CHANGEPASSWORD,
			GD_TEMPLATE_MESSAGEFEEDBACK_MYPREFERENCES => GD_TEMPLATE_MESSAGEFEEDBACKINNER_MYPREFERENCES,
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
new GD_Template_Processor_UserMessageFeedbacks();