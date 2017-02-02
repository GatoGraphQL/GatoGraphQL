<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Configuration
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_TagInfoLayoutsBase extends GD_Template_ProcessorBase {

	function get_template_source($template_id, $atts) {

		return GD_TEMPLATESOURCE_LAYOUT_TAGINFO;
	}

	function get_data_fields($template_id, $atts) {
	
		return array('count');
	}

	function get_template_configuration($template_id, $atts) {

		$ret = parent::get_template_configuration($template_id, $atts);
	
		$ret[GD_JS_TITLES/*'titles'*/] = array(				
			'count' => __('Count', 'pop-coreprocessors'),
		);
		
		return $ret;
	}
}