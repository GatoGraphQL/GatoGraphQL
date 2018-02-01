<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_MAP_STATICIMAGE_LOCATIONS', PoP_TemplateIDUtils::get_template_definition('em-map-staticimage-locations'));

class GD_Template_Processor_MapStaticImageLocations extends GD_Template_Processor_MapStaticImageLocationsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_MAP_STATICIMAGE_LOCATIONS,
		);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_MapStaticImageLocations();