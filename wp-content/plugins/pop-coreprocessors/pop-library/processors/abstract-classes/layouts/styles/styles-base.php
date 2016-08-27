<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Configuration
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_StylesLayoutsBase extends GD_Template_ProcessorBase {

	function get_template_source($template_id, $atts) {

		return GD_TEMPLATESOURCE_LAYOUT_STYLES;
	}

	function get_elem_target($template_id, $atts) {

		return '';
	}
	function get_elem_styles($template_id, $atts) {

		return array();
	}
	function get_template_configuration($template_id, $atts) {

		$ret = parent::get_template_configuration($template_id, $atts);

		$ret['elem-target'] = $this->get_elem_target($template_id, $atts);
		$ret['elem-styles'] = $this->get_elem_styles($template_id, $atts);

		return $ret;
	}
}