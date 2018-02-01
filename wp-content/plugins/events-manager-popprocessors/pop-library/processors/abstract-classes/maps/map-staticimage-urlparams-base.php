<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_MapStaticImageURLParamsBase extends GD_Template_ProcessorBase {

	function get_template_source($template_id, $atts) {
	
		return GD_TEMPLATESOURCE_MAP_STATICIMAGE_URLPARAM;
	}

	function get_data_fields($template_id, $atts) {
	
		return array('coordinates');
	}
}
