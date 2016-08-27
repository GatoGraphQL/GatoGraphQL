<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_EventDateCarouselIndicatorLayoutsBase extends GD_Template_ProcessorBase {

	function get_template_source($template_id, $atts) {
	
		return GD_TEMPLATESOURCE_LAYOUT_CAROUSEL_INDICATORS_EVENTDATE;
	}

	function get_data_fields($template_id, $atts) {
	
		return array('start-date-string');
	}	
}
