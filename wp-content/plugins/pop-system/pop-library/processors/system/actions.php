<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_ACTION_SYSTEM_POPINSTALL', PoP_ServerUtils::get_template_definition('action-system-popinstall'));

class PoP_Core_Template_Processor_SystemActions extends GD_Template_Processor_ActionsBase {

	function get_templates_to_process() {
	
		return array(			
			GD_TEMPLATE_ACTION_SYSTEM_POPINSTALL,
		);
	}

	protected function get_actionexecuter($template_id) {
	
		switch ($template_id) {

			case GD_TEMPLATE_ACTION_SYSTEM_POPINSTALL:
		
				return GD_DATALOAD_ACTIONEXECUTER_SYSTEM_POPINSTALL;
		}

		return parent::get_actionexecuter($template_id);
	}

	protected function get_iohandler($template_id) {

		switch ($template_id) {
					
			case GD_TEMPLATE_ACTION_SYSTEM_POPINSTALL:

				return GD_DATALOAD_IOHANDLER_FORM;
		}
		
		return parent::get_iohandler($template_id);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_Core_Template_Processor_SystemActions();