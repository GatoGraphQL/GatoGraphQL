<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Configuration
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_SubmitButtonsBaseWrapper extends GD_Template_ProcessorBaseWrapper {

	function is_hidden($template_id, $atts) {
	
		return $this->process($template_id, $atts, __FUNCTION__);
	}
	
	function get_label($template_id, $atts) {
	
		return $this->process($template_id, $atts, __FUNCTION__);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Settings Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
global $gd_template_processorwrapper_manager;
$gd_template_processorwrapper_manager->add('GD_Template_Processor_SubmitButtonsBase', 'GD_Template_Processor_SubmitButtonsBaseWrapper');