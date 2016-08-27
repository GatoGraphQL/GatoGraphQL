<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Configuration
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_FormComponentsBaseWrapper extends GD_Template_ProcessorBaseWrapper {

	function is_hidden($template_id, $atts) {
	
		return $this->process($template_id, $atts, __FUNCTION__);
	}
	function is_mandatory($template_id, $atts) {
	
		return $this->process($template_id, $atts, __FUNCTION__);
	}
	function collapsible($template_id, $atts) {
	
		return $this->process($template_id, $atts, __FUNCTION__);
	}
	function get_label($template_id, $atts) {
	
		return $this->process($template_id, $atts, __FUNCTION__);
	}
	function get_input($template_id, $atts) {
	
		return $this->process($template_id, $atts, __FUNCTION__);
	}
	function get_name($template_id, $atts) {
	
		return $this->process($template_id, $atts, __FUNCTION__);
	}
	function get_value($template_id, $atts_or_nothing = array()) {
	
		// Method ->get_filterformcomponent_value() does not send the $atts. This is an exceptional case.
		$atts = $atts_or_nothing;
		return $this->processor->get_value($template_id, $atts);

		// Otherwise, it can be cached
		return $this->process($template_id, $atts, __FUNCTION__);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Settings Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
global $gd_template_processorwrapper_manager;
$gd_template_processorwrapper_manager->add('GD_Template_Processor_FormComponentsBase', 'GD_Template_Processor_FormComponentsBaseWrapper');