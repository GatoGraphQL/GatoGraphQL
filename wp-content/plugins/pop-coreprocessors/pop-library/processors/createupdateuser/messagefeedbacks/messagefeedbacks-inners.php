<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_MESSAGEFEEDBACKINNER_USER_CHANGEPASSWORD', PoP_ServerUtils::get_template_definition('messagefeedbackinner-user-changepassword'));
define ('GD_TEMPLATE_MESSAGEFEEDBACKINNER_MYPREFERENCES', PoP_ServerUtils::get_template_definition('messagefeedbackinner-mypreferences'));

class GD_Template_Processor_UserMessageFeedbackInners extends GD_Template_Processor_MessageFeedbackInnersBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_USER_CHANGEPASSWORD,
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_MYPREFERENCES,
		);
	}

	function get_layouts($template_id) {

		$ret = parent::get_layouts($template_id);

		$layouts = array(
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_USER_CHANGEPASSWORD => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_USER_CHANGEPASSWORD,
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_MYPREFERENCES => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_MYPREFERENCES,
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
new GD_Template_Processor_UserMessageFeedbackInners();