<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_URE_TEMPLATE_MESSAGE_NODETAILS', PoP_TemplateIDUtils::get_template_definition('ure-message-nodetails'));

class GD_URE_Custom_Template_Processor_WidgetMessages extends GD_Template_Processor_WidgetMessagesBase {

	function get_templates_to_process() {
	
		return array(
			GD_URE_TEMPLATE_MESSAGE_NODETAILS,
		);
	}

	function get_message($template_id) {

		switch ($template_id) {

			case GD_URE_TEMPLATE_MESSAGE_NODETAILS:

				return __('No details', 'poptheme-wassup');
		}

		return parent::get_message($template_id);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_URE_Custom_Template_Processor_WidgetMessages();