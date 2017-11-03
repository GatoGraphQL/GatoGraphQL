<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_ACTION_INVITENEWMEMBERS', PoP_TemplateIDUtils::get_template_definition('action-invitemembers'));
define ('GD_TEMPLATE_ACTION_EDITMEMBERSHIP', PoP_TemplateIDUtils::get_template_definition('action-editmembership'));

class GD_URE_Template_Processor_ProfileActions extends GD_Template_Processor_ActionsBase {

	function get_templates_to_process() {
	
		return array(			
			GD_TEMPLATE_ACTION_INVITENEWMEMBERS,
			GD_TEMPLATE_ACTION_EDITMEMBERSHIP,
		);
	}

	protected function get_actionexecuter($template_id) {
	
		switch ($template_id) {

			case GD_TEMPLATE_ACTION_INVITENEWMEMBERS:
		
				return GD_DATALOAD_ACTIONEXECUTER_INVITENEWMEMBERS;

			case GD_TEMPLATE_ACTION_EDITMEMBERSHIP:
		
				return GD_DATALOAD_ACTIONEXECUTER_EDITMEMBERSHIP;
		}

		return parent::get_actionexecuter($template_id);
	}

	function get_settings_id($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_ACTION_INVITENEWMEMBERS:
			
				return GD_TEMPLATE_BLOCK_INVITENEWMEMBERS;

			case GD_TEMPLATE_ACTION_EDITMEMBERSHIP:

				return GD_TEMPLATE_BLOCK_EDITMEMBERSHIP;
		}

		return parent::get_settings_id($template_id);
	}

	protected function get_iohandler($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_ACTION_INVITENEWMEMBERS:
			case GD_TEMPLATE_ACTION_EDITMEMBERSHIP:
			
				return GD_DATALOAD_IOHANDLER_FORM;
		}
	
		return parent::get_iohandler($template_id);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_URE_Template_Processor_ProfileActions();