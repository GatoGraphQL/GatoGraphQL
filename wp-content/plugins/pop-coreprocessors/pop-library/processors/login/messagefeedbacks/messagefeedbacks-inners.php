<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_MESSAGEFEEDBACKINNER_LOGIN', PoP_TemplateIDUtils::get_template_definition('messagefeedbackinner-login'));
define ('GD_TEMPLATE_MESSAGEFEEDBACKINNER_LOSTPWD', PoP_TemplateIDUtils::get_template_definition('messagefeedbackinner-lostpwd'));
define ('GD_TEMPLATE_MESSAGEFEEDBACKINNER_LOSTPWDRESET', PoP_TemplateIDUtils::get_template_definition('messagefeedbackinner-lostpwdreset'));
define ('GD_TEMPLATE_MESSAGEFEEDBACKINNER_LOGOUT', PoP_TemplateIDUtils::get_template_definition('messagefeedbackinner-logout'));

class GD_Template_Processor_LoginMessageFeedbackInners extends GD_Template_Processor_MessageFeedbackInnersBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_LOGIN,
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_LOSTPWD,
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_LOSTPWDRESET,
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_LOGOUT,
		);
	}

	function get_layouts($template_id) {

		$ret = parent::get_layouts($template_id);

		$layouts = array(
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_LOGIN => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_LOGIN,
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_LOSTPWD => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_LOSTPWD,
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_LOSTPWDRESET => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_LOSTPWDRESET,
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_LOGOUT => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_LOGOUT,
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
new GD_Template_Processor_LoginMessageFeedbackInners();