<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Configuration
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_NOTIFICATIONTIME', PoP_ServerUtils::get_template_definition('layout-notificationtime'));

class GD_Template_Processor_NotificationTimeLayouts extends GD_Template_Processor_NotificationTimeLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_NOTIFICATIONTIME,
		);
	}	
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_NotificationTimeLayouts();