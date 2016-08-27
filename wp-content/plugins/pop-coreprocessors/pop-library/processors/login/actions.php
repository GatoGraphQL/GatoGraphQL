<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_ACTION_LOGIN', PoP_ServerUtils::get_template_definition('action-login'));
define ('GD_TEMPLATE_ACTION_LOSTPWD', PoP_ServerUtils::get_template_definition('action-lostpwd'));
define ('GD_TEMPLATE_ACTION_LOSTPWDRESET', PoP_ServerUtils::get_template_definition('action-lostpwdreset'));
define ('GD_TEMPLATE_ACTION_LOGOUT', PoP_ServerUtils::get_template_definition('action-logout'));

class GD_Template_Processor_LoginActions extends GD_Template_Processor_ActionsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_ACTION_LOGIN,
			GD_TEMPLATE_ACTION_LOSTPWD,
			GD_TEMPLATE_ACTION_LOSTPWDRESET,
			GD_TEMPLATE_ACTION_LOGOUT
		);
	}

	function get_settings_id($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_ACTION_LOGIN:
			
				return GD_TEMPLATE_BLOCK_LOGIN;
			
			case GD_TEMPLATE_ACTION_LOSTPWD:
			
				return GD_TEMPLATE_BLOCK_LOSTPWD;
			
			case GD_TEMPLATE_ACTION_LOSTPWDRESET:
			
				return GD_TEMPLATE_BLOCK_LOSTPWDRESET;

			case GD_TEMPLATE_ACTION_LOGOUT:
			
				return GD_TEMPLATE_BLOCK_LOGOUT;
		}

		return parent::get_settings_id($template_id);
	}

	protected function get_actionexecuter($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_ACTION_LOGIN:

				return GD_DATALOAD_ACTIONEXECUTER_LOGIN;

			case GD_TEMPLATE_ACTION_LOSTPWD:

				return GD_DATALOAD_ACTIONEXECUTER_LOSTPWD;

			case GD_TEMPLATE_ACTION_LOSTPWDRESET:

				return GD_DATALOAD_ACTIONEXECUTER_LOSTPWDRESET;

			case GD_TEMPLATE_ACTION_LOGOUT:

				return GD_DATALOAD_ACTIONEXECUTER_LOGOUT;
		}

		return parent::get_actionexecuter($template_id);
	}

	protected function get_iohandler($template_id) {
	
		switch ($template_id) {

			// case GD_TEMPLATE_ACTION_LOGIN:

			// 	return GD_DATALOAD_IOHANDLER_REDIRECT;

			case GD_TEMPLATE_ACTION_LOGIN:
			case GD_TEMPLATE_ACTION_LOSTPWD:
			case GD_TEMPLATE_ACTION_LOSTPWDRESET:
			case GD_TEMPLATE_ACTION_LOGOUT:

				return GD_DATALOAD_IOHANDLER_FORM;
		}

		return parent::get_iohandler($template_id);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_LoginActions();