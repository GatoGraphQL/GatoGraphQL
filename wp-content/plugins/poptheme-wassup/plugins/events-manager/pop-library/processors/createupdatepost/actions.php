<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_ACTION_EVENT_CREATE', PoP_TemplateIDUtils::get_template_definition('action-event-create'));
define ('GD_TEMPLATE_ACTION_EVENTLINK_CREATE', PoP_TemplateIDUtils::get_template_definition('action-eventlink-create'));
define ('GD_TEMPLATE_ACTION_EVENT_UPDATE', PoP_TemplateIDUtils::get_template_definition('action-event-update'));
define ('GD_TEMPLATE_ACTION_EVENTLINK_UPDATE', PoP_TemplateIDUtils::get_template_definition('action-eventlink-update'));

class GD_EM_Template_Processor_CreateUpdatePostActions extends GD_Template_Processor_ActionsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_ACTION_EVENT_CREATE,
			GD_TEMPLATE_ACTION_EVENTLINK_CREATE,
			GD_TEMPLATE_ACTION_EVENT_UPDATE,
			GD_TEMPLATE_ACTION_EVENTLINK_UPDATE,
		);
	}

	protected function get_actionexecuter($template_id) {
	
		switch ($template_id) {
			
			case GD_TEMPLATE_ACTION_EVENT_CREATE:
			case GD_TEMPLATE_ACTION_EVENT_UPDATE:
				
				return GD_DATALOAD_ACTIONEXECUTER_CREATEUPDATE_EVENT;

			case GD_TEMPLATE_ACTION_EVENTLINK_CREATE:
			case GD_TEMPLATE_ACTION_EVENTLINK_UPDATE:
				
				return GD_DATALOAD_ACTIONEXECUTER_CREATEUPDATE_EVENTLINK;
		}

		return parent::get_actionexecuter($template_id);
	}

	protected function get_iohandler($template_id) {
	
		switch ($template_id) {

			case GD_TEMPLATE_ACTION_EVENT_CREATE:
			case GD_TEMPLATE_ACTION_EVENTLINK_CREATE:

				return GD_DATALOAD_IOHANDLER_ADDPOST;
					
			case GD_TEMPLATE_ACTION_EVENT_UPDATE:
			case GD_TEMPLATE_ACTION_EVENTLINK_UPDATE:

				return GD_DATALOAD_IOHANDLER_EDITPOST;
		}

		return parent::get_iohandler($template_id);
	}


	function get_dataloader($template_id) {
	
		switch ($template_id) {

			case GD_TEMPLATE_ACTION_EVENT_CREATE:
			case GD_TEMPLATE_ACTION_EVENTLINK_CREATE:
			case GD_TEMPLATE_ACTION_EVENT_UPDATE:
			case GD_TEMPLATE_ACTION_EVENTLINK_UPDATE:

				return GD_DATALOADER_EVENTLIST;
		}

		return parent::get_dataloader($template_id);
	}


	function get_settings_id($template_id) {
	
		switch ($template_id) {
			
			case GD_TEMPLATE_ACTION_EVENT_CREATE:
			
				return GD_TEMPLATE_BLOCK_EVENT_CREATE;

			case GD_TEMPLATE_ACTION_EVENTLINK_CREATE:
			
				return GD_TEMPLATE_BLOCK_EVENTLINK_CREATE;
			
			case GD_TEMPLATE_ACTION_EVENT_UPDATE:
			
				return GD_TEMPLATE_BLOCK_EVENT_UPDATE;

			case GD_TEMPLATE_ACTION_EVENTLINK_UPDATE:
			
				return GD_TEMPLATE_BLOCK_EVENTLINK_UPDATE;
		}

		return parent::get_settings_id($template_id);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_EM_Template_Processor_CreateUpdatePostActions();