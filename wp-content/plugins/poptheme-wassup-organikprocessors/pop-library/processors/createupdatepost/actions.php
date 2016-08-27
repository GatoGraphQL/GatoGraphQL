<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_ACTION_FARM_CREATE', PoP_ServerUtils::get_template_definition('action-farm-create'));
define ('GD_TEMPLATE_ACTION_FARMLINK_CREATE', PoP_ServerUtils::get_template_definition('action-farmlink-create'));
define ('GD_TEMPLATE_ACTION_FARM_UPDATE', PoP_ServerUtils::get_template_definition('action-farm-update'));
define ('GD_TEMPLATE_ACTION_FARMLINK_UPDATE', PoP_ServerUtils::get_template_definition('action-farmlink-update'));

class OP_Template_Processor_CreateUpdatePostActions extends GD_Template_Processor_ActionsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_ACTION_FARM_CREATE,
			GD_TEMPLATE_ACTION_FARMLINK_CREATE,
			GD_TEMPLATE_ACTION_FARM_UPDATE,
			GD_TEMPLATE_ACTION_FARMLINK_UPDATE,
		);
	}

	protected function get_actionexecuter($template_id) {
	
		switch ($template_id) {

			case GD_TEMPLATE_ACTION_FARM_CREATE:
			case GD_TEMPLATE_ACTION_FARM_UPDATE:

				return GD_DATALOAD_ACTIONEXECUTER_CREATEUPDATE_FARM;

			case GD_TEMPLATE_ACTION_FARMLINK_CREATE:
			case GD_TEMPLATE_ACTION_FARMLINK_UPDATE:

				return GD_DATALOAD_ACTIONEXECUTER_CREATEUPDATE_FARMLINK;
		}

		return parent::get_actionexecuter($template_id);
	}

	protected function get_iohandler($template_id) {
	
		switch ($template_id) {

			case GD_TEMPLATE_ACTION_FARM_CREATE:
			case GD_TEMPLATE_ACTION_FARMLINK_CREATE:

				return GD_DATALOAD_IOHANDLER_ADDPOST;
					
			case GD_TEMPLATE_ACTION_FARM_UPDATE:
			case GD_TEMPLATE_ACTION_FARMLINK_UPDATE:

				return GD_DATALOAD_IOHANDLER_EDITPOST;
		}

		return parent::get_iohandler($template_id);
	}


	function get_dataloader($template_id) {
	
		switch ($template_id) {

			case GD_TEMPLATE_ACTION_FARM_CREATE:
			case GD_TEMPLATE_ACTION_FARMLINK_CREATE:
			case GD_TEMPLATE_ACTION_FARM_UPDATE:
			case GD_TEMPLATE_ACTION_FARMLINK_UPDATE:

				return GD_DATALOADER_POSTLIST;
		}

		return parent::get_dataloader($template_id);
	}


	function get_settings_id($template_id) {
	
		switch ($template_id) {

			case GD_TEMPLATE_ACTION_FARM_CREATE:
			
				return GD_TEMPLATE_BLOCK_FARM_CREATE;

			case GD_TEMPLATE_ACTION_FARMLINK_CREATE:
			
				return GD_TEMPLATE_BLOCK_FARMLINK_CREATE;
			
			case GD_TEMPLATE_ACTION_FARM_UPDATE:
			
				return GD_TEMPLATE_BLOCK_FARM_UPDATE;

			case GD_TEMPLATE_ACTION_FARMLINK_UPDATE:
			
				return GD_TEMPLATE_BLOCK_FARMLINK_UPDATE;
		}

		return parent::get_settings_id($template_id);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new OP_Template_Processor_CreateUpdatePostActions();