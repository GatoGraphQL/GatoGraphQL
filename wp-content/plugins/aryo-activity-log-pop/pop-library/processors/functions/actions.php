<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_ACTION_MARKALLNOTIFICATIONSASREAD', PoP_ServerUtils::get_template_definition('action-markallnotificationsasread'));
define ('GD_TEMPLATE_ACTION_MARKNOTIFICATIONASREAD', PoP_ServerUtils::get_template_definition('action-marknotificationasread'));
define ('GD_TEMPLATE_ACTION_MARKNOTIFICATIONASUNREAD', PoP_ServerUtils::get_template_definition('action-marknotificationasunread'));

class GD_AAL_Template_Processor_FunctionsActions extends GD_Template_Processor_ActionsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_ACTION_MARKALLNOTIFICATIONSASREAD,
			GD_TEMPLATE_ACTION_MARKNOTIFICATIONASREAD,
			GD_TEMPLATE_ACTION_MARKNOTIFICATIONASUNREAD,
		);
	}

	protected function get_actionexecuter($template_id) {

		$executers = array(
			GD_TEMPLATE_ACTION_MARKALLNOTIFICATIONSASREAD => GD_DATALOAD_ACTIONEXECUTER_NOTIFICATION_MARKALLASREAD,
			GD_TEMPLATE_ACTION_MARKNOTIFICATIONASREAD => GD_DATALOAD_ACTIONEXECUTER_NOTIFICATION_MARKASREAD,
			GD_TEMPLATE_ACTION_MARKNOTIFICATIONASUNREAD => GD_DATALOAD_ACTIONEXECUTER_NOTIFICATION_MARKASUNREAD,
		);
		if ($executer = $executers[$template_id]) {

			return $executer;
		}

		return parent::get_actionexecuter($template_id);
	}

	protected function get_iohandler($template_id) {

		switch ($template_id) {
					
			case GD_TEMPLATE_ACTION_MARKALLNOTIFICATIONSASREAD:
			case GD_TEMPLATE_ACTION_MARKNOTIFICATIONASREAD:
			case GD_TEMPLATE_ACTION_MARKNOTIFICATIONASUNREAD:

				return GD_DATALOAD_IOHANDLER_FORM;
		}
		
		return parent::get_iohandler($template_id);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_AAL_Template_Processor_FunctionsActions();