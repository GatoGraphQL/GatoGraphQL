<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_CommentClippedViewComponentHeadersBase extends GD_Template_ProcessorBase {

	function get_template_source($template_id, $atts) {
	
		return GD_TEMPLATESOURCE_VIEWCOMPONENT_HEADER_COMMENTCLIPPED;
	}

	function get_data_fields($template_id, $atts) {
	
		return array('content-clipped');
	}
}

