<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_SUBMITBUTTON_LOGIN', PoP_ServerUtils::get_template_definition('submitbutton-login'));
define ('GD_TEMPLATE_SUBMITBUTTON_LOSTPWD', PoP_ServerUtils::get_template_definition('submitbutton-lostpwd'));
define ('GD_TEMPLATE_SUBMITBUTTON_LOSTPWDRESET', PoP_ServerUtils::get_template_definition('submitbutton-lostpwdreset'));
define ('GD_TEMPLATE_SUBMITBUTTON_LOGOUT', PoP_ServerUtils::get_template_definition('submitbutton-logout'));

class GD_Template_Processor_LoginSubmitButtons extends GD_Template_Processor_SubmitButtonsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_SUBMITBUTTON_LOGIN,
			GD_TEMPLATE_SUBMITBUTTON_LOSTPWD,
			GD_TEMPLATE_SUBMITBUTTON_LOSTPWDRESET,
			GD_TEMPLATE_SUBMITBUTTON_LOGOUT,
		);
	}

	function get_label($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_SUBMITBUTTON_LOGIN:

				return __('Log in', 'pop-coreprocessors');

			case GD_TEMPLATE_SUBMITBUTTON_LOSTPWD:

				return __('Get password reset code', 'pop-coreprocessors');

			case GD_TEMPLATE_SUBMITBUTTON_LOSTPWDRESET:

				return __('Reset password', 'pop-coreprocessors');

			case GD_TEMPLATE_SUBMITBUTTON_LOGOUT:

				return __('Yes, please log me out', 'pop-coreprocessors');
		}

		return parent::get_label($template_id, $atts);
	}
	
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_LoginSubmitButtons();