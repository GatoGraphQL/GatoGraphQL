<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_FORMCOMPONENTGROUP_CUU_USERNAME', PoP_ServerUtils::get_template_definition('formcomponentgroup-cuu-username'));
define ('GD_TEMPLATE_FORMCOMPONENTGROUP_CUU_EMAIL', PoP_ServerUtils::get_template_definition('formcomponentgroup-cuu-email'));
define ('GD_TEMPLATE_FORMCOMPONENTGROUP_CUU_CURRENTPASSWORD', PoP_ServerUtils::get_template_definition('formcomponentgroup-cuu-currentpassword'));
define ('GD_TEMPLATE_FORMCOMPONENTGROUP_CUU_PASSWORD', PoP_ServerUtils::get_template_definition('formcomponentgroup-cuu-password'));
define ('GD_TEMPLATE_FORMCOMPONENTGROUP_CUU_NEWPASSWORD', PoP_ServerUtils::get_template_definition('formcomponentgroup-cuu-newpassword'));
define ('GD_TEMPLATE_FORMCOMPONENTGROUP_CUU_PASSWORDREPEAT', PoP_ServerUtils::get_template_definition('formcomponentgroup-cuu-passwordrepeat'));
define ('GD_TEMPLATE_FORMCOMPONENTGROUP_CUU_NEWPASSWORDREPEAT', PoP_ServerUtils::get_template_definition('formcomponentgroup-cuu-newpasswordrepeat'));
define ('GD_TEMPLATE_FORMCOMPONENTGROUP_CUU_FIRSTNAME', PoP_ServerUtils::get_template_definition('formcomponentgroup-cuu-firstname'));
define ('GD_TEMPLATE_FORMCOMPONENTGROUP_CUU_USERURL', PoP_ServerUtils::get_template_definition('formcomponentgroup-cuu-userurl'));
define ('GD_TEMPLATE_FORMCOMPONENTGROUP_CUU_DESCRIPTION', PoP_ServerUtils::get_template_definition('formcomponentgroup-cuu-description'));

class GD_Template_Processor_UserFormGroups extends GD_Template_Processor_FormComponentGroupsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_FORMCOMPONENTGROUP_CUU_USERNAME,
			GD_TEMPLATE_FORMCOMPONENTGROUP_CUU_EMAIL,
			GD_TEMPLATE_FORMCOMPONENTGROUP_CUU_CURRENTPASSWORD,
			GD_TEMPLATE_FORMCOMPONENTGROUP_CUU_PASSWORD,
			GD_TEMPLATE_FORMCOMPONENTGROUP_CUU_NEWPASSWORD,
			GD_TEMPLATE_FORMCOMPONENTGROUP_CUU_PASSWORDREPEAT,
			GD_TEMPLATE_FORMCOMPONENTGROUP_CUU_NEWPASSWORDREPEAT,
			GD_TEMPLATE_FORMCOMPONENTGROUP_CUU_FIRSTNAME,
			GD_TEMPLATE_FORMCOMPONENTGROUP_CUU_USERURL,
			GD_TEMPLATE_FORMCOMPONENTGROUP_CUU_DESCRIPTION,
		);
	}

	function get_label($template_id, $atts) {

		$ret = parent::get_label($template_id, $atts);

		switch ($template_id) {

			case GD_TEMPLATE_FORMCOMPONENTGROUP_CUU_USERURL:

				$ret = '<i class="fa fa-fw fa-home"></i>'.$ret;
				break;
		}
		
		return $ret;
	}

	function get_component($template_id) {

		$components = array(
			GD_TEMPLATE_FORMCOMPONENTGROUP_CUU_USERNAME => GD_TEMPLATE_FORMCOMPONENT_CUU_USERNAME,
			GD_TEMPLATE_FORMCOMPONENTGROUP_CUU_EMAIL => GD_TEMPLATE_FORMCOMPONENT_CUU_EMAIL,
			GD_TEMPLATE_FORMCOMPONENTGROUP_CUU_CURRENTPASSWORD => GD_TEMPLATE_FORMCOMPONENT_CUU_CURRENTPASSWORD,
			GD_TEMPLATE_FORMCOMPONENTGROUP_CUU_PASSWORD => GD_TEMPLATE_FORMCOMPONENT_CUU_PASSWORD,
			GD_TEMPLATE_FORMCOMPONENTGROUP_CUU_NEWPASSWORD => GD_TEMPLATE_FORMCOMPONENT_CUU_NEWPASSWORD,
			GD_TEMPLATE_FORMCOMPONENTGROUP_CUU_PASSWORDREPEAT => GD_TEMPLATE_FORMCOMPONENT_CUU_PASSWORDREPEAT,
			GD_TEMPLATE_FORMCOMPONENTGROUP_CUU_NEWPASSWORDREPEAT => GD_TEMPLATE_FORMCOMPONENT_CUU_NEWPASSWORDREPEAT,
			GD_TEMPLATE_FORMCOMPONENTGROUP_CUU_FIRSTNAME => GD_TEMPLATE_FORMCOMPONENT_CUU_FIRSTNAME,
			GD_TEMPLATE_FORMCOMPONENTGROUP_CUU_USERURL => GD_TEMPLATE_FORMCOMPONENT_CUU_USERURL,
			GD_TEMPLATE_FORMCOMPONENTGROUP_CUU_DESCRIPTION => GD_TEMPLATE_FORMCOMPONENT_CUU_DESCRIPTION,
		);

		if ($component = $components[$template_id]) {

			return $component;
		}
		
		return parent::get_component($template_id);
	}

	function get_info($template_id, $atts) {
	
		switch ($template_id) {
		
			case GD_TEMPLATE_FORMCOMPONENTGROUP_CUU_EMAIL:
			
				return __('Please be careful to fill-in the email properly! It will not only appear in the profile page, but also be used by our system to send you notifications.', 'pop-coreprocessors');
		}
		
		return parent::get_info($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_UserFormGroups();