<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_MAP_SCRIPT_MARKERS', PoP_TemplateIDUtils::get_template_definition('em-map-script-markers'));

class GD_Template_Processor_MapMarkerScripts extends GD_Template_Processor_MapMarkerScriptsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_MAP_SCRIPT_MARKERS,
		);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_MapMarkerScripts();