<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_FORMINNER_LOGIN', PoP_TemplateIDUtils::get_template_definition('forminner-login'));
define ('GD_TEMPLATE_FORMINNER_LOSTPWD', PoP_TemplateIDUtils::get_template_definition('forminner-lostpwd'));
define ('GD_TEMPLATE_FORMINNER_LOSTPWDRESET', PoP_TemplateIDUtils::get_template_definition('forminner-lostpwdreset'));
define ('GD_TEMPLATE_FORMINNER_LOGOUT', PoP_TemplateIDUtils::get_template_definition('forminner-logout'));

class GD_Template_Processor_LoginFormInners extends GD_Template_Processor_FormInnersBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_FORMINNER_LOGIN,
			GD_TEMPLATE_FORMINNER_LOSTPWD,
			GD_TEMPLATE_FORMINNER_LOSTPWDRESET,
			GD_TEMPLATE_FORMINNER_LOGOUT
		);
	}

	function get_layouts($template_id) {

		$ret = parent::get_layouts($template_id);
	
		switch ($template_id) {

			case GD_TEMPLATE_FORMINNER_LOGIN:

				$ret = array_merge(
					$ret,
					array(
						GD_TEMPLATE_FORMCOMPONENTGROUP_LOGIN_USERNAME,
						GD_TEMPLATE_FORMCOMPONENTGROUP_LOGIN_PWD,
						GD_TEMPLATE_FORMCOMPONENT_BROWSERURL,
						GD_TEMPLATE_SUBMITBUTTON_LOGIN,
					)
				);
				break;

			case GD_TEMPLATE_FORMINNER_LOSTPWD:

				$ret = array_merge(
					$ret,
					array(
						GD_TEMPLATE_FORMCOMPONENTGROUP_LOSTPWD_USERNAME,
						GD_TEMPLATE_SUBMITBUTTON_LOSTPWD,
					)
				);
				break;

			case GD_TEMPLATE_FORMINNER_LOSTPWDRESET:

				$ret = array_merge(
					$ret,
					array(
						GD_TEMPLATE_FORMCOMPONENTGROUP_LOSTPWDRESET_CODE,
						GD_TEMPLATE_FORMCOMPONENTGROUP_LOSTPWDRESET_NEWPASSWORD,
						GD_TEMPLATE_FORMCOMPONENTGROUP_LOSTPWDRESET_PASSWORDREPEAT,
						GD_TEMPLATE_SUBMITBUTTON_LOSTPWDRESET,
					)
				);
				break;

			case GD_TEMPLATE_FORMINNER_LOGOUT:

				$ret = array_merge(
					$ret,
					array(
						GD_TEMPLATE_FORMCOMPONENT_BROWSERURL,
						GD_TEMPLATE_SUBMITBUTTON_LOGOUT,
					)
				);
				break;
		}

		return $ret;
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_LoginFormInners();