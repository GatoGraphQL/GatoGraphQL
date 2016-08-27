<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class Wassup_Template_Processor_LinkAccessLayoutsBase extends GD_Template_ProcessorBase {

	function get_template_source($template_id, $atts) {
	
		return GD_TEMPLATESOURCE_LAYOUT_LINK_ACCESS;
	}

	function get_data_fields($template_id, $atts) {
	
		return array('linkaccess-string');
	}
}
