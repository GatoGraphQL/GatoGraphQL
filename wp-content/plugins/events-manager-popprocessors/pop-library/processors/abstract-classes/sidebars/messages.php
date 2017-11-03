<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_EM_TEMPLATE_MESSAGE_NOLOCATION', PoP_TemplateIDUtils::get_template_definition('em-message-nolocation'));

class GD_EM_Template_Processor_WidgetMessages extends GD_Template_Processor_WidgetMessagesBase {

	function get_templates_to_process() {
	
		return array(
			GD_EM_TEMPLATE_MESSAGE_NOLOCATION,
		);
	}

	function get_message($template_id) {

		switch ($template_id) {

			case GD_EM_TEMPLATE_MESSAGE_NOLOCATION:

				return __('No location', 'em-popprocessors');
		}

		return parent::get_message($template_id);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_EM_Template_Processor_WidgetMessages();