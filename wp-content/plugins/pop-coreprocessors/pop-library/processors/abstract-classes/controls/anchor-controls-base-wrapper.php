<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Configuration
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_AnchorControlsBaseWrapper extends GD_Template_Processor_ControlsBaseWrapper {

	function get_href($template_id, $atts) {
	
		return $this->process($template_id, $atts, __FUNCTION__);
	}

	function get_target($template_id, $atts) {

		return $this->process($template_id, $atts, __FUNCTION__);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Settings Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
global $gd_template_processorwrapper_manager;
$gd_template_processorwrapper_manager->add('GD_Template_Processor_AnchorControlsBase', 'GD_Template_Processor_AnchorControlsBaseWrapper');