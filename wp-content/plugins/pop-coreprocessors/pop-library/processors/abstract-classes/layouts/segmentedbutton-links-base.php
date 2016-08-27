<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Configuration
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_SegmentedButtonLinksBase extends GD_Template_ProcessorBase {

	function get_template_source($template_id, $atts) {

		return GD_TEMPLATESOURCE_LAYOUT_SEGMENTEDBUTTON_LINK;
	}

	function get_fontawesome($template_id, $atts) {

		return null;
	}

	function get_template_configuration($template_id, $atts) {
	
		$ret = parent::get_template_configuration($template_id, $atts);	

		if ($fontawesome = $this->get_fontawesome($template_id, $atts)) {
			$ret[GD_JS_FONTAWESOME/*'fontawesome'*/] = $fontawesome;
		}

		return $ret;
	}	
}