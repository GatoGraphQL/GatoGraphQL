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

		$ret['width'] = $this->get_frame_width($template_id);
		$ret['height'] = $this->get_frame_height($template_id);
		$ret['src'] = $this->get_frame_src($template_id);
		
		return $ret;
	}	
}