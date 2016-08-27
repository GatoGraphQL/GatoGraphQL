<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_MultiplesBase extends GD_Template_ProcessorBase {

	function get_template_source($template_id, $atts) {

		return GD_TEMPLATESOURCE_MULTIPLE;
	}

	// function get_component_configuration_type($template_id, $atts) {
	
	// 	return GD_TEMPLATECOMPONENT_CONFIGURATION_TYPE_ARRAY;
	// }
}
