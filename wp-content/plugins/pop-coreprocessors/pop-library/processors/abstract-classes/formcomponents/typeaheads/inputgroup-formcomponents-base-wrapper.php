<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Configuration
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_InputGroupFormComponentsBaseWrapper extends GD_Template_Processor_FormComponentsBaseWrapper {

	function get_controls($template_id) {
	
		return $this->processor->get_controls($template_id);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Settings Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
global $gd_template_processorwrapper_manager;
$gd_template_processorwrapper_manager->add('GD_Template_Processor_InputGroupFormComponentsBase', 'GD_Template_Processor_InputGroupFormComponentsBaseWrapper');