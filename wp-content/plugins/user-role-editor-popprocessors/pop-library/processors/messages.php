<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_URE_TEMPLATE_MESSAGE_NOCOMMUNITIES', PoP_TemplateIDUtils::get_template_definition('ure-message-nocommunities'));

class GD_URE_Template_Processor_WidgetMessages extends GD_Template_Processor_WidgetMessagesBase {

	function get_templates_to_process() {
	
		return array(
			GD_URE_TEMPLATE_MESSAGE_NOCOMMUNITIES,
		);
	}

	function get_message($template_id) {

		switch ($template_id) {

			case GD_URE_TEMPLATE_MESSAGE_NOCOMMUNITIES:

				return __('No Organizations', 'ure-popprocessors');
		}

		return parent::get_message($template_id);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_URE_Template_Processor_WidgetMessages();