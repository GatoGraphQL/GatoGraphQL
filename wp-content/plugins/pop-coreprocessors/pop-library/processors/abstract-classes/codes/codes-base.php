<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_CodesBase extends GD_Template_ProcessorBase {

	function get_template_source($template_id, $atts) {

		return GD_TEMPLATESOURCE_CODE;
	}

	function get_code($template_id, $atts) {

		return null;
	}

	function get_html_tag($template_id, $atts) {
	
		return 'div';
	}

	function get_template_configuration($template_id, $atts) {

		$ret = parent::get_template_configuration($template_id, $atts);
	
		global $gd_template_processor_manager;
		
		$ret['code'] = $this->get_code($template_id, $atts);
		$ret['html-tag'] = $this->get_html_tag($template_id, $atts);
		
		return $ret;
	}
}
