<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_FORMCOMPONENT_CUU_USERNAME', PoP_ServerUtils::get_template_definition('formcomponent-cuu-username'));
define ('GD_TEMPLATE_FORMCOMPONENT_CUU_EMAIL', PoP_ServerUtils::get_template_definition('formcomponent-cuu-email'));
define ('GD_TEMPLATE_FORMCOMPONENT_CUU_CURRENTPASSWORD', PoP_ServerUtils::get_template_definition('formcomponent-cuu-currentpassword'));
define ('GD_TEMPLATE_FORMCOMPONENT_CUU_PASSWORD', PoP_ServerUtils::get_template_definition('formcomponent-cuu-password'));
define ('GD_TEMPLATE_FORMCOMPONENT_CUU_NEWPASSWORD', PoP_ServerUtils::get_template_definition('formcomponent-cuu-newpassword'));
define ('GD_TEMPLATE_FORMCOMPONENT_CUU_PASSWORDREPEAT', PoP_ServerUtils::get_template_definition('formcomponent-cuu-passwordrepeat'));
define ('GD_TEMPLATE_FORMCOMPONENT_CUU_NEWPASSWORDREPEAT', PoP_ServerUtils::get_template_definition('formcomponent-cuu-newpasswordrepeat'));
define ('GD_TEMPLATE_FORMCOMPONENT_CUU_FIRSTNAME', PoP_ServerUtils::get_template_definition('formcomponent-cuu-firstname'));
define ('GD_TEMPLATE_FORMCOMPONENT_CUU_USERURL', PoP_ServerUtils::get_template_definition('formcomponent-cuu-userurl'));

class GD_Template_Processor_CreateUpdateUserTextFormComponentInputs extends GD_Template_Processor_TextFormComponentsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_FORMCOMPONENT_CUU_USERNAME,
			GD_TEMPLATE_FORMCOMPONENT_CUU_EMAIL,
			GD_TEMPLATE_FORMCOMPONENT_CUU_CURRENTPASSWORD,
			GD_TEMPLATE_FORMCOMPONENT_CUU_PASSWORD,
			GD_TEMPLATE_FORMCOMPONENT_CUU_NEWPASSWORD,
			GD_TEMPLATE_FORMCOMPONENT_CUU_PASSWORDREPEAT,
			GD_TEMPLATE_FORMCOMPONENT_CUU_NEWPASSWORDREPEAT,
			GD_TEMPLATE_FORMCOMPONENT_CUU_FIRSTNAME,
			GD_TEMPLATE_FORMCOMPONENT_CUU_USERURL
		);
	}

	function get_label_text($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_FORMCOMPONENT_CUU_USERNAME:
				
				return __('Username', 'pop-coreprocessors');

			case GD_TEMPLATE_FORMCOMPONENT_CUU_EMAIL:
				
				return __('Email', 'pop-coreprocessors');

			case GD_TEMPLATE_FORMCOMPONENT_CUU_CURRENTPASSWORD:
				
				return __('Current Password', 'pop-coreprocessors');

			case GD_TEMPLATE_FORMCOMPONENT_CUU_PASSWORD:
				
				return __('Password', 'pop-coreprocessors');

			case GD_TEMPLATE_FORMCOMPONENT_CUU_NEWPASSWORD:
				
				return __('New password', 'pop-coreprocessors');

			case GD_TEMPLATE_FORMCOMPONENT_CUU_PASSWORDREPEAT:
				
				return __('Repeat password', 'pop-coreprocessors');

			case GD_TEMPLATE_FORMCOMPONENT_CUU_NEWPASSWORDREPEAT:
				
				return __('Repeat new password', 'pop-coreprocessors');

			case GD_TEMPLATE_FORMCOMPONENT_CUU_FIRSTNAME:
				
				return __('Name', 'pop-coreprocessors');

			case GD_TEMPLATE_FORMCOMPONENT_CUU_USERURL:

				// return '<i class="fa fa-fw fa-home"></i> '.__('Website URL', 'pop-coreprocessors');
				return __('Website URL', 'pop-coreprocessors');
		}
		
		return parent::get_label_text($template_id, $atts);
	}

	function is_mandatory($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_FORMCOMPONENT_CUU_USERNAME:
			case GD_TEMPLATE_FORMCOMPONENT_CUU_EMAIL:
			case GD_TEMPLATE_FORMCOMPONENT_CUU_PASSWORD:
			case GD_TEMPLATE_FORMCOMPONENT_CUU_NEWPASSWORD:
			case GD_TEMPLATE_FORMCOMPONENT_CUU_PASSWORDREPEAT:
			case GD_TEMPLATE_FORMCOMPONENT_CUU_NEWPASSWORDREPEAT:
			case GD_TEMPLATE_FORMCOMPONENT_CUU_FIRSTNAME:
				
				return true;
		}
		
		return parent::is_mandatory($template_id, $atts);
	}

	function get_type($template_id, $atts) {
	
		switch ($template_id) {
		
			case GD_TEMPLATE_FORMCOMPONENT_CUU_CURRENTPASSWORD:
			case GD_TEMPLATE_FORMCOMPONENT_CUU_PASSWORD:
			case GD_TEMPLATE_FORMCOMPONENT_CUU_NEWPASSWORD:
			case GD_TEMPLATE_FORMCOMPONENT_CUU_PASSWORDREPEAT:
			case GD_TEMPLATE_FORMCOMPONENT_CUU_NEWPASSWORDREPEAT:
			
				return 'password';
		}
		
		return parent::get_type($template_id, $atts);
	}

	function get_override_from_itemobject($template_id) {

		$ret = parent::get_override_from_itemobject($template_id);
	
		switch ($template_id) {

			case GD_TEMPLATE_FORMCOMPONENT_CUU_USERNAME:
				
				$ret[] = array('key' => 'value', 'field' => 'username');
				break;

			case GD_TEMPLATE_FORMCOMPONENT_CUU_EMAIL:
				
				$ret[] = array('key' => 'value', 'field' => 'email');
				break;

			case GD_TEMPLATE_FORMCOMPONENT_CUU_FIRSTNAME:
				
				$ret[] = array('key' => 'value', 'field' => 'firstname');
				break;

			case GD_TEMPLATE_FORMCOMPONENT_CUU_USERURL:
				
				$ret[] = array('key' => 'value', 'field' => 'user-url');
				break;
		}
		
		return $ret;
	}


	function init_atts($template_id, &$atts) {
	
		switch ($template_id) {

			case GD_TEMPLATE_FORMCOMPONENT_CUU_CURRENTPASSWORD:
			case GD_TEMPLATE_FORMCOMPONENT_CUU_PASSWORD:
			case GD_TEMPLATE_FORMCOMPONENT_CUU_NEWPASSWORD:
			case GD_TEMPLATE_FORMCOMPONENT_CUU_PASSWORDREPEAT:
			case GD_TEMPLATE_FORMCOMPONENT_CUU_NEWPASSWORDREPEAT:			

				// For security reasons: delete passwords (more since introducing Login block in offcanvas, so that it will stay there forever and other users could re-login using the exisiting filled-in info)
				$this->add_att($template_id, $atts, 'pop-form-clear', true);
				break;
		}
		
		return parent::init_atts($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_CreateUpdateUserTextFormComponentInputs();