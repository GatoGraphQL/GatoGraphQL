<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_ALERT_STICKY', PoP_ServerUtils::get_template_definition('alert-sticky'));

class GD_Template_Processor_Alerts extends GD_Template_Processor_AlertsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_ALERT_STICKY
		);
	}	

	function get_layout($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_ALERT_STICKY:
				
				return GD_TEMPLATE_ANNOUNCEMENTSPEECHBUBBLE_STICKY;
		}

		return parent::get_layout($template_id);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_Alerts();