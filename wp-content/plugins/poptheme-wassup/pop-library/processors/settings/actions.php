<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_ACTION_SETTINGS', PoP_ServerUtils::get_template_definition('action-settings'));

class GD_Template_Processor_CustomSettingsActions extends GD_Template_Processor_ActionsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_ACTION_SETTINGS
		);
	}

	function get_settings_id($template_id) {

		// Otherwise it is embedded in another page (eg: Homepage, Author), so the block name must identify it accordingly
		switch ($template_id) {

			case GD_TEMPLATE_ACTION_SETTINGS:
			
				return GD_TEMPLATE_BLOCK_SETTINGS;
		}

		return parent::get_settings_id($template_id);
	}

	protected function get_actionexecuter($template_id) {
	
		switch ($template_id) {

			case GD_TEMPLATE_ACTION_SETTINGS:

				return GD_DATALOAD_ACTIONEXECUTER_SETTINGS;
		}

		return parent::get_actionexecuter($template_id);
	}

	protected function get_iohandler($template_id) {
	
		switch ($template_id) {

			case GD_TEMPLATE_ACTION_SETTINGS:

				return GD_DATALOAD_IOHANDLER_REDIRECT;
		}

		return parent::get_iohandler($template_id);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_CustomSettingsActions();