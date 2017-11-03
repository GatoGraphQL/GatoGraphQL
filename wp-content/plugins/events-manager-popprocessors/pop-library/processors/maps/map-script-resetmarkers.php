<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_MAP_SCRIPT_RESETMARKERS', PoP_TemplateIDUtils::get_template_definition('em-map-script-resetmarkers'));

class GD_Template_Processor_MapResetMarkerScripts extends GD_Template_Processor_MapResetMarkerScriptsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_MAP_SCRIPT_RESETMARKERS,
		);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_MapResetMarkerScripts();