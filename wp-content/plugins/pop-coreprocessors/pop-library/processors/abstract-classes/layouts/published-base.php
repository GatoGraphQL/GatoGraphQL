<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Configuration
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_PostStatusDateLayoutsBase extends GD_Template_ProcessorBase {

	function get_template_source($template_id, $atts) {

		return GD_TEMPLATESOURCE_LAYOUT_POSTSTATUSDATE;
	}

	function get_data_fields($template_id, $atts) {
	
		return array('date', 'status');
	}

	function get_template_configuration($template_id, $atts) {

		$ret = parent::get_template_configuration($template_id, $atts);
	
		$ret[GD_JS_TITLES/*'titles'*/] = array(				
			'publish' => __('Published', 'pop-coreprocessors'),
			'pending' => __('Pending', 'pop-coreprocessors'),
			'draft' => __('Draft', 'pop-coreprocessors'),
		);
		
		return $ret;
	}
}