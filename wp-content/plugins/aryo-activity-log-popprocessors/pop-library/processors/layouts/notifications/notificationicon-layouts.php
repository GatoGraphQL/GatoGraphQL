<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Configuration
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_NOTIFICATIONICON', PoP_TemplateIDUtils::get_template_definition('layout-notificationicon'));

class GD_Template_Processor_NotificationActionIconLayouts extends GD_Template_Processor_NotificationActionIconLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_NOTIFICATIONICON,
		);
	}	
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_NotificationActionIconLayouts();