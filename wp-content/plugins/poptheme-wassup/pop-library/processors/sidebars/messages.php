<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_CUSTOM_TEMPLATE_MESSAGE_NOCATEGORIES', PoP_TemplateIDUtils::get_template_definition('message-nocategories'));

class GD_Custom_Template_Processor_WidgetMessages extends GD_Template_Processor_WidgetMessagesBase {

	function get_templates_to_process() {
	
		return array(
			GD_CUSTOM_TEMPLATE_MESSAGE_NOCATEGORIES,
		);
	}

	function get_message($template_id) {

		switch ($template_id) {

			case GD_CUSTOM_TEMPLATE_MESSAGE_NOCATEGORIES:

				return __('No Categories', 'poptheme-wassup');
		}

		return parent::get_message($template_id);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Custom_Template_Processor_WidgetMessages();