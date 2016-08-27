<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUTCALENDAR_CONTENT_POPOVER', PoP_ServerUtils::get_template_definition('em-layoutcalendar-content'));

class GD_Template_Processor_CalendarContentLayouts extends GD_Template_Processor_CalendarContentLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUTCALENDAR_CONTENT_POPOVER,
		);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_CalendarContentLayouts();