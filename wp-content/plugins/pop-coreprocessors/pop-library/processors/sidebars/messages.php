<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_MESSAGE_NOREFERENCES', PoP_ServerUtils::get_template_definition('message-noreferences'));
define ('GD_TEMPLATE_MESSAGE_NOCONTACT', PoP_ServerUtils::get_template_definition('message-nocontact'));

class GD_Template_Processor_WidgetMessages extends GD_Template_Processor_WidgetMessagesBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_MESSAGE_NOREFERENCES,
			GD_TEMPLATE_MESSAGE_NOCONTACT,
		);
	}

	function get_message($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_MESSAGE_NOREFERENCES:

				return __('Nothing here', 'pop-coreprocessors');

			case GD_TEMPLATE_MESSAGE_NOCONTACT:

				return __('No contact details', 'pop-coreprocessors');
		}

		return parent::get_message($template_id);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_WidgetMessages();