<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Configuration
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_EmbedPreviewLayoutsBase extends GD_Template_ProcessorBase {

	function get_template_source($template_id, $atts) {

		return GD_TEMPLATESOURCE_LAYOUT_EMBEDPREVIEW;
	}

	function get_frame_src($template_id) {

		return '';
	}
	function get_frame_width($template_id) {

		return '100%';
	}
	function get_frame_height($template_id) {

		return '400';
	}

	function get_template_configuration($template_id, $atts) {
	
		$ret = parent::get_template_configuration($template_id, $atts);	

		$ret['width'] = $this->get_frame_width($template_id);
		$ret['height'] = $this->get_frame_height($template_id);
		$ret['src'] = $this->get_frame_src($template_id);
		
		return $ret;
	}	
}