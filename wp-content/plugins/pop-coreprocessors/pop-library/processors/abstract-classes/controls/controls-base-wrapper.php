<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Configuration
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_ControlsBaseWrapper extends GD_Template_ProcessorBaseWrapper {

	function get_label($template_id, $atts) {
	
		return $this->process($template_id, $atts, __FUNCTION__);
	}

	function get_fontawesome($template_id, $atts) {
	
		return $this->process($template_id, $atts, __FUNCTION__);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Settings Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
global $gd_template_processorwrapper_manager;
$gd_template_processorwrapper_manager->add('GD_Template_Processor_ControlsBase', 'GD_Template_Processor_ControlsBaseWrapper');