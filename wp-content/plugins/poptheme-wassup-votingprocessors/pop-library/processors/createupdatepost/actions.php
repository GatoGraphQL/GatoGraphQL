<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_ACTION_OPINIONATEDVOTE_CREATE', PoP_ServerUtils::get_template_definition('action-opinionatedvote-create'));
define ('GD_TEMPLATE_ACTION_OPINIONATEDVOTE_UPDATE', PoP_ServerUtils::get_template_definition('action-opinionatedvote-update'));
define ('GD_TEMPLATE_ACTION_OPINIONATEDVOTE_CREATEORUPDATE', PoP_ServerUtils::get_template_definition('action-opinionatedvote-createorupdate'));
define ('GD_TEMPLATE_ACTION_SINGLEPOSTOPINIONATEDVOTE_CREATEORUPDATE', PoP_ServerUtils::get_template_definition('action-singlepostopinionatedvote-createorupdate'));

class VotingProcessors_Template_Processor_CreateUpdatePostActions extends GD_Template_Processor_ActionsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_ACTION_OPINIONATEDVOTE_CREATE,
			GD_TEMPLATE_ACTION_OPINIONATEDVOTE_UPDATE,
			GD_TEMPLATE_ACTION_OPINIONATEDVOTE_CREATEORUPDATE,
			GD_TEMPLATE_ACTION_SINGLEPOSTOPINIONATEDVOTE_CREATEORUPDATE,
		);
	}

	protected function get_actionexecuter($template_id) {
	
		switch ($template_id) {

			case GD_TEMPLATE_ACTION_OPINIONATEDVOTE_CREATE:
			case GD_TEMPLATE_ACTION_OPINIONATEDVOTE_UPDATE:
			case GD_TEMPLATE_ACTION_OPINIONATEDVOTE_CREATEORUPDATE:
			case GD_TEMPLATE_ACTION_SINGLEPOSTOPINIONATEDVOTE_CREATEORUPDATE:
				
				return GD_DATALOAD_ACTIONEXECUTER_CREATEUPDATE_OPINIONATEDVOTE;
		}

		return parent::get_actionexecuter($template_id);
	}

	protected function get_iohandler($template_id) {
	
		switch ($template_id) {

			case GD_TEMPLATE_ACTION_OPINIONATEDVOTE_CREATE:

				return GD_DATALOAD_IOHANDLER_ADDPOST;
					
			case GD_TEMPLATE_ACTION_OPINIONATEDVOTE_UPDATE:

				return GD_DATALOAD_IOHANDLER_EDITPOST;

			case GD_TEMPLATE_ACTION_OPINIONATEDVOTE_CREATEORUPDATE:
			case GD_TEMPLATE_ACTION_SINGLEPOSTOPINIONATEDVOTE_CREATEORUPDATE:

				return GD_DATALOAD_IOHANDLER_ADDPOST;
		}

		return parent::get_iohandler($template_id);
	}


	function get_dataloader($template_id) {
	
		switch ($template_id) {

			case GD_TEMPLATE_ACTION_OPINIONATEDVOTE_CREATE:
			case GD_TEMPLATE_ACTION_OPINIONATEDVOTE_UPDATE:
			case GD_TEMPLATE_ACTION_OPINIONATEDVOTE_CREATEORUPDATE:
			case GD_TEMPLATE_ACTION_SINGLEPOSTOPINIONATEDVOTE_CREATEORUPDATE:

				return GD_DATALOADER_POSTLIST;
		}

		return parent::get_dataloader($template_id);
	}


	function get_settings_id($template_id) {
	
		switch ($template_id) {

			case GD_TEMPLATE_ACTION_OPINIONATEDVOTE_CREATE:
			
				return GD_TEMPLATE_BLOCK_OPINIONATEDVOTE_CREATE;
			
			case GD_TEMPLATE_ACTION_OPINIONATEDVOTE_UPDATE:
			
				return GD_TEMPLATE_BLOCK_OPINIONATEDVOTE_UPDATE;
		
			case GD_TEMPLATE_ACTION_OPINIONATEDVOTE_CREATEORUPDATE:
		
				return GD_TEMPLATE_BLOCK_OPINIONATEDVOTE_CREATEORUPDATE;

			case GD_TEMPLATE_ACTION_SINGLEPOSTOPINIONATEDVOTE_CREATEORUPDATE:

				return GD_TEMPLATE_BLOCK_SINGLEPOSTOPINIONATEDVOTE_CREATEORUPDATE;
		}

		return parent::get_settings_id($template_id);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new VotingProcessors_Template_Processor_CreateUpdatePostActions();