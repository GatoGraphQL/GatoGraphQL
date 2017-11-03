<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_MAP_SCRIPT_DRAWMARKERS', PoP_TemplateIDUtils::get_template_definition('em-map-script-drawmarkers'));

class GD_Template_Processor_MapDrawMarkerScripts extends GD_Template_Processor_MapDrawMarkerScriptsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_MAP_SCRIPT_DRAWMARKERS,
		);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_MapDrawMarkerScripts();