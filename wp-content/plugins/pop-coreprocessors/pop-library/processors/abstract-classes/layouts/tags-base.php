<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_TagLayoutsBase extends GD_Template_ProcessorBase {

	function get_template_source($template_id, $atts) {
	
		return GD_TEMPLATESOURCE_LAYOUT_TAG;
	}

	function get_data_fields($template_id, $atts) {
	
		// return array('url', 'name', 'symbol');
		return array('url', 'namedescription', 'symbol');
	}
}