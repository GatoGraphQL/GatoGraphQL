<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_TriggerLocationTypeaheadScriptLayoutsBase extends GD_Template_ProcessorBase {

	function get_template_source($template_id, $atts) {
	
		return GD_TEMPLATESOURCE_SCRIPT_TRIGGERTYPEAHEADSELECT_LOCATION;
	}

	function get_data_fields($template_id, $atts) {
	
		return array('id', 'name', 'address', 'coordinates');
	}	
}
