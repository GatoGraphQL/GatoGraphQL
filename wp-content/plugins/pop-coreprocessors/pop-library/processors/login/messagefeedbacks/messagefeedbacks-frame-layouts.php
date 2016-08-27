<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_LOGIN', PoP_ServerUtils::get_template_definition('layout-messagefeedbackframe-login'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_LOSTPWD', PoP_ServerUtils::get_template_definition('layout-messagefeedbackframe-lostpwd'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_LOSTPWDRESET', PoP_ServerUtils::get_template_definition('layout-messagefeedbackframe-lostpwdreset'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_LOGOUT', PoP_ServerUtils::get_template_definition('layout-messagefeedbackframe-logout'));

class GD_Template_Processor_LoginMessageFeedbackFrameLayouts extends GD_Template_Processor_MessageFeedbackFrameLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_LOGIN,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_LOSTPWD,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_LOSTPWDRESET,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_LOGOUT,
		);
	}

	function get_layout($template_id) {

		$layouts = array(
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_LOGIN => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_LOGIN,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_LOSTPWD => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_LOSTPWD,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_LOSTPWDRESET => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_LOSTPWDRESET,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_LOGOUT => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_LOGOUT,
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
new GD_Template_Processor_LoginMessageFeedbackFrameLayouts();