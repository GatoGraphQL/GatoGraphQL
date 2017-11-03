<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_ACTION_CREATELOCATION', PoP_TemplateIDUtils::get_template_definition('action-em-createlocation'));

class GD_EM_Template_Processor_CreateLocationActions extends GD_Template_Processor_ActionsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_ACTION_CREATELOCATION,
		);
	}
	
	protected function get_iohandler($template_id) {
	
		switch ($template_id) {

			case GD_TEMPLATE_ACTION_CREATELOCATION:

				return GD_DATALOAD_IOHANDLER_FORM;
		}

		return parent::get_iohandler($template_id);
	}

	protected function get_actionexecuter($template_id) {
	
		switch ($template_id) {

			case GD_TEMPLATE_ACTION_CREATELOCATION:

				return GD_DATALOAD_ACTIONEXECUTER_CREATELOCATION;
		}

		return parent::get_actionexecuter($template_id);
	}

	function get_dataloader($template_id) {
	
		switch ($template_id) {

			case GD_TEMPLATE_ACTION_CREATELOCATION:

				return GD_DATALOADER_DELEGATE;
		}

		return parent::get_dataloader($template_id);
	}

	function get_settings_id($template_id) {
	
		switch ($template_id) {

			case GD_TEMPLATE_ACTION_CREATELOCATION:

				return GD_TEMPLATE_BLOCK_CREATELOCATION;
		}

		return parent::get_settings_id($template_id);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_EM_Template_Processor_CreateLocationActions();