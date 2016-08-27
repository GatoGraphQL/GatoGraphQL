<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_ACTION_PROFILEORGANIZATION_CREATE', PoP_ServerUtils::get_template_definition('action-profileorganization-create'));
define ('GD_TEMPLATE_ACTION_PROFILEORGANIZATION_UPDATE', PoP_ServerUtils::get_template_definition('action-profileorganization-update'));
define ('GD_TEMPLATE_ACTION_PROFILEINDIVIDUAL_CREATE', PoP_ServerUtils::get_template_definition('action-profileindividual-create'));
define ('GD_TEMPLATE_ACTION_PROFILEINDIVIDUAL_UPDATE', PoP_ServerUtils::get_template_definition('action-profileindividual-update'));

class GD_URE_Template_Processor_CreateUpdateProfileActions extends GD_Template_Processor_CreateUpdateUserActionsBase {

	function get_templates_to_process() {
	
		return array(			
			GD_TEMPLATE_ACTION_PROFILEORGANIZATION_CREATE,
			GD_TEMPLATE_ACTION_PROFILEORGANIZATION_UPDATE,
			GD_TEMPLATE_ACTION_PROFILEINDIVIDUAL_CREATE,
			GD_TEMPLATE_ACTION_PROFILEINDIVIDUAL_UPDATE,
		);
	}

	protected function get_actionexecuter($template_id) {
	
		switch ($template_id) {

			case GD_TEMPLATE_ACTION_PROFILEORGANIZATION_CREATE:
			case GD_TEMPLATE_ACTION_PROFILEORGANIZATION_UPDATE:

				// Allow to override
				return apply_filters('GD_URE_Template_Processor_CreateUpdateProfileActions:get_actionexecuter:profileorganization', GD_DATALOAD_ACTIONEXECUTER_CREATEUPDATE_PROFILEORGANIZATION);
			
			case GD_TEMPLATE_ACTION_PROFILEINDIVIDUAL_CREATE:
			case GD_TEMPLATE_ACTION_PROFILEINDIVIDUAL_UPDATE:
			
				// Allow to override
				return apply_filters('GD_URE_Template_Processor_CreateUpdateProfileActions:get_actionexecuter:profileindividual', GD_DATALOAD_ACTIONEXECUTER_CREATEUPDATE_PROFILEINDIVIDUAL);
		}

		return parent::get_actionexecuter($template_id);
	}

	function get_settings_id($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_ACTION_PROFILEORGANIZATION_CREATE:

				return GD_TEMPLATE_BLOCK_PROFILEORGANIZATION_CREATE;

			case GD_TEMPLATE_ACTION_PROFILEORGANIZATION_UPDATE:

				return GD_TEMPLATE_BLOCK_PROFILEORGANIZATION_UPDATE;
			
			case GD_TEMPLATE_ACTION_PROFILEINDIVIDUAL_CREATE:
				
				return GD_TEMPLATE_BLOCK_PROFILEINDIVIDUAL_CREATE;

			case GD_TEMPLATE_ACTION_PROFILEINDIVIDUAL_UPDATE:
			
				return GD_TEMPLATE_BLOCK_PROFILEINDIVIDUAL_UPDATE;
		}

		return parent::get_settings_id($template_id);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_URE_Template_Processor_CreateUpdateProfileActions();