<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_MAP_ADDMARKER', PoP_TemplateIDUtils::get_template_definition('em-map-addmarker'));

class GD_Template_Processor_MapAddMarkers extends GD_Template_Processor_MapAddMarkersBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_MAP_ADDMARKER,
		);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_MapAddMarkers();