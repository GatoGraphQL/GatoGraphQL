<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_MAP_SCRIPT_DRAWMARKERS', PoP_TemplateIDUtils::get_template_definition('em-map-script-drawmarkers'));
define ('GD_TEMPLATE_MAPSTATICIMAGE_SCRIPT_DRAWMARKERS', PoP_TemplateIDUtils::get_template_definition('em-map-staticimage-script-drawmarkers'));
define ('GD_TEMPLATE_MAPSTATICIMAGE_USERORPOST_SCRIPT_DRAWMARKERS', PoP_TemplateIDUtils::get_template_definition('em-map-staticimage-userorpost-script-drawmarkers'));

class GD_Template_Processor_MapDrawMarkerScripts extends GD_Template_Processor_MapDrawMarkerScriptsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_MAP_SCRIPT_DRAWMARKERS,
			GD_TEMPLATE_MAPSTATICIMAGE_SCRIPT_DRAWMARKERS,
			GD_TEMPLATE_MAPSTATICIMAGE_USERORPOST_SCRIPT_DRAWMARKERS,
		);
	}

	function get_mapdiv_template($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_MAPSTATICIMAGE_SCRIPT_DRAWMARKERS:
			
				return GD_TEMPLATE_MAPSTATICIMAGE_DIV;

			case GD_TEMPLATE_MAPSTATICIMAGE_USERORPOST_SCRIPT_DRAWMARKERS:

				return GD_TEMPLATE_MAPSTATICIMAGE_USERORPOST_DIV;
		}
	
		return parent::get_mapdiv_template($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_MapDrawMarkerScripts();