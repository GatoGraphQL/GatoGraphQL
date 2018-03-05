<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_GFBlockHooks {

	function __construct() {
	
		add_filter(
			'GenericForms_Template_Processor_Actions:action-executer',
			array($this, 'get_actionexecuter'),
			10,
			2
		);
		add_filter(
			'GenericForms_Template_Processor_Actions:iohandler',
			array($this, 'get_iohandler'),
			10,
			2
		);
		add_filter(
			'GenericForms_Template_Processor_Blocks:iohandler',
			array($this, 'get_iohandler'),
			10,
			2
		);
	}

	function get_actionexecuter($actionexecuter, $template_id) {
	
		switch ($template_id) {

			case GD_TEMPLATE_ACTION_CONTACTUS:
			case GD_TEMPLATE_ACTION_CONTACTUSER:
			case GD_TEMPLATE_ACTION_SHAREBYEMAIL:
			case GD_TEMPLATE_ACTION_VOLUNTEER:
			case GD_TEMPLATE_ACTION_FLAG:
			case GD_TEMPLATE_ACTION_NEWSLETTER:

				return GD_GF_DATALOAD_ACTIONEXECUTER_GRAVITYFORMS;

			case GD_TEMPLATE_ACTION_NEWSLETTERUNSUBSCRIPTION:

				return GD_GF_DATALOAD_ACTIONEXECUTER_NEWSLETTERUNSUBSCRIPTION;
		}

		return $actionexecuter;
	}

	function get_iohandler($iohandler, $template_id) {
	
		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_CONTACTUS:
			case GD_TEMPLATE_BLOCK_CONTACTUSER:
			case GD_TEMPLATE_BLOCK_SHAREBYEMAIL:
			case GD_TEMPLATE_BLOCK_VOLUNTEER:
			case GD_TEMPLATE_BLOCK_FLAG:
			case GD_TEMPLATE_BLOCK_NEWSLETTER:
			case GD_TEMPLATE_BLOCKCODE_NEWSLETTER:

			case GD_TEMPLATE_ACTION_CONTACTUS:
			case GD_TEMPLATE_ACTION_CONTACTUSER:
			case GD_TEMPLATE_ACTION_SHAREBYEMAIL:
			case GD_TEMPLATE_ACTION_VOLUNTEER:
			case GD_TEMPLATE_ACTION_FLAG:
			case GD_TEMPLATE_ACTION_NEWSLETTER:
			
				return GD_GF_DATALOAD_IOHANDLER_SHORTCODEFORM;
		}

		return $iohandler;
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_GFBlockHooks();
