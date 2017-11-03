<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_ALERT_HOMEMESSAGE', PoP_TemplateIDUtils::get_template_definition('alert-homemessage'));

class GD_Template_Processor_CustomAlerts extends GD_Template_Processor_AlertsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_ALERT_HOMEMESSAGE
		);
	}	

	function get_layout($template_id) {

		return GD_TEMPLATE_LAYOUT_FULLVIEW_HOMEMESSAGE;
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_CustomAlerts();