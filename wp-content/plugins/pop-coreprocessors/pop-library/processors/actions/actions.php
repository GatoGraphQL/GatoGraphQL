<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_ACTION_INVITENEWUSERS', PoP_ServerUtils::get_template_definition('action-inviteusers'));

class PoP_Core_Template_Processor_Actions extends GD_Template_Processor_ActionsBase {

	function get_templates_to_process() {
	
		return array(			
			GD_TEMPLATE_ACTION_INVITENEWUSERS,
		);
	}

	protected function get_actionexecuter($template_id) {
	
		switch ($template_id) {

			case GD_TEMPLATE_ACTION_INVITENEWUSERS:
		
				return GD_DATALOAD_ACTIONEXECUTER_INVITENEWUSERS;
		}

		return parent::get_actionexecuter($template_id);
	}

	function get_settings_id($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_ACTION_INVITENEWUSERS:
			
				return GD_TEMPLATE_BLOCK_INVITENEWUSERS;
		}

		return parent::get_settings_id($template_id);
	}

	protected function get_iohandler($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_ACTION_INVITENEWUSERS:
			
				return GD_DATALOAD_IOHANDLER_FORM;
		}
	
		return parent::get_iohandler($template_id);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_Core_Template_Processor_Actions();