<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_MAP_DIV', PoP_ServerUtils::get_template_definition('em-map-div'));

class GD_Template_Processor_MapDivs extends GD_Template_Processor_MapDivsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_MAP_DIV,
		);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_MapDivs();