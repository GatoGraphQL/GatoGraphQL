<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_MapMarkerScriptsBase extends GD_Template_ProcessorBase {

	function get_template_source($template_id, $atts) {
	
		return GD_TEMPLATESOURCE_MAP_SCRIPT_MARKERS;
	}

	function get_data_fields($template_id, $atts) {
	
		return array('id', 'coordinates', 'name', 'address');
	}
}
