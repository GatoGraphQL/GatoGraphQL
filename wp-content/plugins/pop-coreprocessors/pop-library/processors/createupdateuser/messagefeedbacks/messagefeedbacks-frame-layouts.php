<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_USER_CHANGEPASSWORD', PoP_ServerUtils::get_template_definition('layout-messagefeedbackframe-user-changepassword'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_MYPREFERENCES', PoP_ServerUtils::get_template_definition('layout-messagefeedbackframe-mypreferences'));

class GD_Template_Processor_UserMessageFeedbackFrameLayouts extends GD_Template_Processor_MessageFeedbackFrameLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_USER_CHANGEPASSWORD,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_MYPREFERENCES,
		);
	}

	function get_layout($template_id) {

		$layouts = array(
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_USER_CHANGEPASSWORD => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_USER_CHANGEPASSWORD,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_MYPREFERENCES => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_MYPREFERENCES,
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
new GD_Template_Processor_UserMessageFeedbackFrameLayouts();