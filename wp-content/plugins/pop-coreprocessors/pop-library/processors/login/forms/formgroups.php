<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_FORMCOMPONENTGROUP_LOGIN_USERNAME', PoP_TemplateIDUtils::get_template_definition('formcomponentgroup-log'));
define ('GD_TEMPLATE_FORMCOMPONENTGROUP_LOGIN_PWD', PoP_TemplateIDUtils::get_template_definition('formcomponentgroup-pwd'));
define ('GD_TEMPLATE_FORMCOMPONENTGROUP_LOSTPWD_USERNAME', PoP_TemplateIDUtils::get_template_definition('formcomponentgroup-lostpwd-username'));
define ('GD_TEMPLATE_FORMCOMPONENTGROUP_LOSTPWDRESET_CODE', PoP_TemplateIDUtils::get_template_definition('formcomponentgroup-lostpwdreset-code'));
define ('GD_TEMPLATE_FORMCOMPONENTGROUP_LOSTPWDRESET_NEWPASSWORD', PoP_TemplateIDUtils::get_template_definition('formcomponentgroup-lostpwdreset-newpassword'));
define ('GD_TEMPLATE_FORMCOMPONENTGROUP_LOSTPWDRESET_PASSWORDREPEAT', PoP_TemplateIDUtils::get_template_definition('formcomponentgroup-lostpwdreset-passwordrepeat'));

class GD_Template_Processor_LoginFormGroups extends GD_Template_Processor_FormComponentGroupsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_FORMCOMPONENTGROUP_LOGIN_USERNAME,
			GD_TEMPLATE_FORMCOMPONENTGROUP_LOGIN_PWD,
			GD_TEMPLATE_FORMCOMPONENTGROUP_LOSTPWD_USERNAME,
			GD_TEMPLATE_FORMCOMPONENTGROUP_LOSTPWDRESET_CODE,
			GD_TEMPLATE_FORMCOMPONENTGROUP_LOSTPWDRESET_NEWPASSWORD,
			GD_TEMPLATE_FORMCOMPONENTGROUP_LOSTPWDRESET_PASSWORDREPEAT,
		);
	}

	function use_component_configuration($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_FORMCOMPONENTGROUP_LOGIN_USERNAME:
			case GD_TEMPLATE_FORMCOMPONENTGROUP_LOGIN_PWD:
			case GD_TEMPLATE_FORMCOMPONENTGROUP_LOSTPWD_USERNAME:
			case GD_TEMPLATE_FORMCOMPONENTGROUP_LOSTPWDRESET_CODE:
			case GD_TEMPLATE_FORMCOMPONENTGROUP_LOSTPWDRESET_NEWPASSWORD:
			case GD_TEMPLATE_FORMCOMPONENTGROUP_LOSTPWDRESET_PASSWORDREPEAT:

				return false;
		}

		return parent::use_component_configuration($template_id);
	}

	function get_component($template_id) {

		$components = array(
			GD_TEMPLATE_FORMCOMPONENTGROUP_LOGIN_USERNAME => GD_TEMPLATE_FORMCOMPONENT_LOGIN_USERNAME,
			GD_TEMPLATE_FORMCOMPONENTGROUP_LOGIN_PWD => GD_TEMPLATE_FORMCOMPONENT_LOGIN_PWD,
			GD_TEMPLATE_FORMCOMPONENTGROUP_LOSTPWD_USERNAME => GD_TEMPLATE_FORMCOMPONENT_LOSTPWD_USERNAME,
			GD_TEMPLATE_FORMCOMPONENTGROUP_LOSTPWDRESET_CODE => GD_TEMPLATE_FORMCOMPONENT_LOSTPWDRESET_CODE,
			GD_TEMPLATE_FORMCOMPONENTGROUP_LOSTPWDRESET_NEWPASSWORD => GD_TEMPLATE_FORMCOMPONENT_LOSTPWDRESET_NEWPASSWORD,
			GD_TEMPLATE_FORMCOMPONENTGROUP_LOSTPWDRESET_PASSWORDREPEAT => GD_TEMPLATE_FORMCOMPONENT_LOSTPWDRESET_PASSWORDREPEAT,
		);

		if ($component = $components[$template_id]) {

			return $component;
		}
		
		return parent::get_component($template_id);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_LoginFormGroups();