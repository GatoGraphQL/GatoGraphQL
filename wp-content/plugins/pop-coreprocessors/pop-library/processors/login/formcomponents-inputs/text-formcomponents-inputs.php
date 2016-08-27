<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

// Important: do NOT change the name of these formcomponents, they are needed like this by function wp_signon()
define ('GD_TEMPLATE_FORMCOMPONENT_LOGIN_USERNAME', PoP_ServerUtils::get_template_definition('log'));
define ('GD_TEMPLATE_FORMCOMPONENT_LOGIN_PWD', PoP_ServerUtils::get_template_definition('pwd'));
define ('GD_TEMPLATE_FORMCOMPONENT_LOSTPWD_USERNAME', PoP_ServerUtils::get_template_definition('user_login'));
define ('GD_TEMPLATE_FORMCOMPONENT_LOSTPWDRESET_CODE', PoP_ServerUtils::get_template_definition('code'));
define ('GD_TEMPLATE_FORMCOMPONENT_LOSTPWDRESET_NEWPASSWORD', PoP_ServerUtils::get_template_definition('pass1'));
define ('GD_TEMPLATE_FORMCOMPONENT_LOSTPWDRESET_PASSWORDREPEAT', PoP_ServerUtils::get_template_definition('pass2'));

class GD_Template_Processor_LoginTextFormComponentInputs extends GD_Template_Processor_TextFormComponentsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_FORMCOMPONENT_LOGIN_USERNAME,
			GD_TEMPLATE_FORMCOMPONENT_LOGIN_PWD,
			GD_TEMPLATE_FORMCOMPONENT_LOSTPWD_USERNAME,
			GD_TEMPLATE_FORMCOMPONENT_LOSTPWDRESET_CODE,
			GD_TEMPLATE_FORMCOMPONENT_LOSTPWDRESET_NEWPASSWORD,
			GD_TEMPLATE_FORMCOMPONENT_LOSTPWDRESET_PASSWORDREPEAT,
		);
	}

	function get_label_text($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_FORMCOMPONENT_LOGIN_USERNAME:
				
				return __('Username or email', 'pop-coreprocessors');
			
			case GD_TEMPLATE_FORMCOMPONENT_LOGIN_PWD:
				
				return __('Password', 'pop-coreprocessors');
			
			case GD_TEMPLATE_FORMCOMPONENT_LOSTPWD_USERNAME:
				
				return __('Username or email', 'pop-coreprocessors');

			case GD_TEMPLATE_FORMCOMPONENT_LOSTPWDRESET_CODE:
				
				return __('Code', 'pop-coreprocessors');

			case GD_TEMPLATE_FORMCOMPONENT_LOSTPWDRESET_NEWPASSWORD:
				
				return __('New password', 'pop-coreprocessors');

			case GD_TEMPLATE_FORMCOMPONENT_LOSTPWDRESET_PASSWORDREPEAT:
				
				return __('Repeat password', 'pop-coreprocessors');
		}
		
		return parent::get_label_text($template_id, $atts);
	}

	function is_mandatory($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_FORMCOMPONENT_LOSTPWDRESET_CODE:
			case GD_TEMPLATE_FORMCOMPONENT_LOSTPWDRESET_NEWPASSWORD:
			case GD_TEMPLATE_FORMCOMPONENT_LOSTPWDRESET_PASSWORDREPEAT:
				
				return true;
		}
		
		return parent::is_mandatory($template_id, $atts);
	}

	function get_type($template_id, $atts) {
	
		switch ($template_id) {
		
			case GD_TEMPLATE_FORMCOMPONENT_LOGIN_PWD:
			case GD_TEMPLATE_FORMCOMPONENT_LOSTPWDRESET_NEWPASSWORD:
			case GD_TEMPLATE_FORMCOMPONENT_LOSTPWDRESET_PASSWORDREPEAT:
			
				return 'password';
		}
		
		return parent::get_type($template_id, $atts);
	}
	function init_atts($template_id, &$atts) {
	
		switch ($template_id) {

			case GD_TEMPLATE_FORMCOMPONENT_LOGIN_USERNAME:
			case GD_TEMPLATE_FORMCOMPONENT_LOGIN_PWD:
			case GD_TEMPLATE_FORMCOMPONENT_LOSTPWD_USERNAME:
			case GD_TEMPLATE_FORMCOMPONENT_LOSTPWDRESET_CODE:
			case GD_TEMPLATE_FORMCOMPONENT_LOSTPWDRESET_NEWPASSWORD:
			case GD_TEMPLATE_FORMCOMPONENT_LOSTPWDRESET_PASSWORDREPEAT:

				// Delete values after logging in (more since introducing Login block in offcanvas, so that it will stay there forever and other users could re-login using the exisiting filled-in info)
				// $this->append_att($template_id, $atts, 'class', 'pop-form-clear');
				$this->add_att($template_id, $atts, 'pop-form-clear', true);
				break;
		}
		
		return parent::init_atts($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_LoginTextFormComponentInputs();