<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_URE_Custom_Template_Processor_ProfileIndividualLayoutsBase extends GD_Template_ProcessorBase {

	function get_template_source($template_id, $atts) {
	
		return GD_TEMPLATESOURCE_LAYOUT_PROFILEINDIVIDUAL_DETAILS;
	}

	function get_data_fields($template_id, $atts) {
	
		return array('individualinterests-strings');
	}

	function get_label_class($template_id, $atts) {
	
		return 'label-info';
	}

	function get_title($template_id, $atts) {
	
		return __('Interests', 'poptheme-wassup');
	}

	function get_template_configuration($template_id, $atts) {

		$ret = parent::get_template_configuration($template_id, $atts);

		switch ($template_id) {
				
			case GD_URE_TEMPLATE_LAYOUT_PROFILEINDIVIDUAL_DETAILS:

				$ret[GD_JS_CLASSES/*'classes'*/]['label'] = $this->get_label_class($template_id, $atts);
				$ret[GD_JS_TITLES/*'titles'*/] = array(				
					'interests' => $this->get_title($template_id, $atts),
				);				
				break;
		}
		
		return $ret;
	}
}
