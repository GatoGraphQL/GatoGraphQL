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

	function get_frame_src($template_id, $atts) {

		return '';
	}
	function get_frame_width($template_id, $atts) {

		return '100%';
	}
	function get_frame_height($template_id, $atts) {

		return '400';
	}
	function print_source($template_id, $atts) {

		return false;
	}
	function get_source_title($template_id, $atts) {

		return sprintf(
			'<em>%s</em>',
			__('Source:', 'pop-coreprocessors')
		);
	}
	function get_source_target($template_id, $atts) {

		return '_blank';
	}
	function get_header($template_id, $atts) {

		return '';
	}

	function get_js_setting($template_id, $atts) {

		$ret = parent::get_js_setting($template_id, $atts);

		// For if executing JS method `modalReloadEmbedPreview`, eg: set by a parent template, such as GD_TEMPLATE_BLOCKGROUP_API_MODAL
		if ($urlType = $this->get_att($template_id, $atts, 'url-type')) {
			
			$ret['url-type'] = $urlType;
		}

		return $ret;
	}

	function get_template_configuration($template_id, $atts) {
	
		$ret = parent::get_template_configuration($template_id, $atts);	

		$ret['width'] = $this->get_frame_width($template_id, $atts);
		$ret['height'] = $this->get_frame_height($template_id, $atts);
		$ret['src'] = $this->get_frame_src($template_id, $atts);
		if ($this->print_source($template_id, $atts)) {
			$ret['print-source'] = true;
			$ret[GD_JS_TITLES/*'titles'*/]['source'] = $this->get_source_title($template_id, $atts);
			$ret['targets']['source'] = $this->get_source_target($template_id, $atts);
		}
		if ($header = $this->get_header($template_id, $atts)) {
			$ret[GD_JS_TITLES/*'titles'*/]['header'] = $header;
		}
		
		return $ret;
	}	
}