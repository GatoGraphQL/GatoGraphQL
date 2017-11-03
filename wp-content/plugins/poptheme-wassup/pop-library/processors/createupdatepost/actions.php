<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_ACTION_WEBPOSTLINK_CREATE', PoP_TemplateIDUtils::get_template_definition('action-webpostlink-create'));
define ('GD_TEMPLATE_ACTION_WEBPOSTLINK_UPDATE', PoP_TemplateIDUtils::get_template_definition('action-webpostlink-update'));
define ('GD_TEMPLATE_ACTION_HIGHLIGHT_CREATE', PoP_TemplateIDUtils::get_template_definition('action-highlight-create'));
define ('GD_TEMPLATE_ACTION_HIGHLIGHT_UPDATE', PoP_TemplateIDUtils::get_template_definition('action-highlight-update'));
define ('GD_TEMPLATE_ACTION_WEBPOST_CREATE', PoP_TemplateIDUtils::get_template_definition('action-webpost-create'));
define ('GD_TEMPLATE_ACTION_WEBPOST_UPDATE', PoP_TemplateIDUtils::get_template_definition('action-webpost-update'));

class Wassup_Template_Processor_CreateUpdatePostActions extends GD_Template_Processor_ActionsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_ACTION_WEBPOSTLINK_CREATE,
			GD_TEMPLATE_ACTION_WEBPOSTLINK_UPDATE,
			GD_TEMPLATE_ACTION_HIGHLIGHT_CREATE,
			GD_TEMPLATE_ACTION_HIGHLIGHT_UPDATE,
			GD_TEMPLATE_ACTION_WEBPOST_CREATE,
			GD_TEMPLATE_ACTION_WEBPOST_UPDATE,
		);
	}

	protected function get_actionexecuter($template_id) {
	
		switch ($template_id) {

			case GD_TEMPLATE_ACTION_WEBPOSTLINK_CREATE:
			case GD_TEMPLATE_ACTION_WEBPOSTLINK_UPDATE:

				return GD_DATALOAD_ACTIONEXECUTER_CREATEUPDATE_LINK;

			case GD_TEMPLATE_ACTION_HIGHLIGHT_CREATE:
			case GD_TEMPLATE_ACTION_HIGHLIGHT_UPDATE:
				
				return GD_DATALOAD_ACTIONEXECUTER_CREATEUPDATE_HIGHLIGHT;

			case GD_TEMPLATE_ACTION_WEBPOST_CREATE:
			case GD_TEMPLATE_ACTION_WEBPOST_UPDATE:
				
				return GD_DATALOAD_ACTIONEXECUTER_CREATEUPDATE_WEBPOST;
		}

		return parent::get_actionexecuter($template_id);
	}

	protected function get_iohandler($template_id) {
	
		switch ($template_id) {

			case GD_TEMPLATE_ACTION_WEBPOSTLINK_CREATE:
			case GD_TEMPLATE_ACTION_HIGHLIGHT_CREATE:
			case GD_TEMPLATE_ACTION_WEBPOST_CREATE:

				return GD_DATALOAD_IOHANDLER_ADDPOST;
					
			case GD_TEMPLATE_ACTION_WEBPOSTLINK_UPDATE:
			case GD_TEMPLATE_ACTION_HIGHLIGHT_UPDATE:
			case GD_TEMPLATE_ACTION_WEBPOST_UPDATE:

				return GD_DATALOAD_IOHANDLER_EDITPOST;
		}

		return parent::get_iohandler($template_id);
	}


	function get_dataloader($template_id) {
	
		switch ($template_id) {

			case GD_TEMPLATE_ACTION_WEBPOSTLINK_CREATE:
			case GD_TEMPLATE_ACTION_WEBPOSTLINK_UPDATE:
			case GD_TEMPLATE_ACTION_HIGHLIGHT_CREATE:
			case GD_TEMPLATE_ACTION_HIGHLIGHT_UPDATE:
			case GD_TEMPLATE_ACTION_WEBPOST_CREATE:
			case GD_TEMPLATE_ACTION_WEBPOST_UPDATE:

				return GD_DATALOADER_POSTLIST;
		}

		return parent::get_dataloader($template_id);
	}


	function get_settings_id($template_id) {
	
		$settings_ids = array(
			GD_TEMPLATE_ACTION_WEBPOSTLINK_CREATE => GD_TEMPLATE_BLOCK_WEBPOSTLINK_CREATE,
			GD_TEMPLATE_ACTION_WEBPOSTLINK_UPDATE => GD_TEMPLATE_BLOCK_WEBPOSTLINK_UPDATE,
			GD_TEMPLATE_ACTION_HIGHLIGHT_CREATE => GD_TEMPLATE_BLOCK_HIGHLIGHT_CREATE,
			GD_TEMPLATE_ACTION_HIGHLIGHT_UPDATE => GD_TEMPLATE_BLOCK_HIGHLIGHT_UPDATE,
			GD_TEMPLATE_ACTION_WEBPOST_CREATE => GD_TEMPLATE_BLOCK_WEBPOST_CREATE,
			GD_TEMPLATE_ACTION_WEBPOST_UPDATE => GD_TEMPLATE_BLOCK_WEBPOST_UPDATE,
		);
		if ($settings_id = $settings_ids[$template_id]) {

			return $settings_id;
		}

		return parent::get_settings_id($template_id);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new Wassup_Template_Processor_CreateUpdatePostActions();