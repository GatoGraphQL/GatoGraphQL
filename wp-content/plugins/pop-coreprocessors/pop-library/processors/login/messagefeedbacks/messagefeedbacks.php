<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_MESSAGEFEEDBACK_LOGIN', PoP_TemplateIDUtils::get_template_definition('messagefeedback-login'));
define ('GD_TEMPLATE_MESSAGEFEEDBACK_LOSTPWD', PoP_TemplateIDUtils::get_template_definition('messagefeedback-lostpwd'));
define ('GD_TEMPLATE_MESSAGEFEEDBACK_LOSTPWDRESET', PoP_TemplateIDUtils::get_template_definition('messagefeedback-lostpwdreset'));
define ('GD_TEMPLATE_MESSAGEFEEDBACK_LOGOUT', PoP_TemplateIDUtils::get_template_definition('messagefeedback-logout'));

class GD_Template_Processor_LoginMessageFeedbacks extends GD_Template_Processor_MessageFeedbacksBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_MESSAGEFEEDBACK_LOGIN,
			GD_TEMPLATE_MESSAGEFEEDBACK_LOSTPWD,
			GD_TEMPLATE_MESSAGEFEEDBACK_LOSTPWDRESET,
			GD_TEMPLATE_MESSAGEFEEDBACK_LOGOUT,
		);
	}

	function get_inner_template($template_id) {

		$inners = array(
			GD_TEMPLATE_MESSAGEFEEDBACK_LOGIN => GD_TEMPLATE_MESSAGEFEEDBACKINNER_LOGIN,
			GD_TEMPLATE_MESSAGEFEEDBACK_LOSTPWD => GD_TEMPLATE_MESSAGEFEEDBACKINNER_LOSTPWD,
			GD_TEMPLATE_MESSAGEFEEDBACK_LOSTPWDRESET => GD_TEMPLATE_MESSAGEFEEDBACKINNER_LOSTPWDRESET,
			GD_TEMPLATE_MESSAGEFEEDBACK_LOGOUT => GD_TEMPLATE_MESSAGEFEEDBACKINNER_LOGOUT,
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
new GD_Template_Processor_LoginMessageFeedbacks();