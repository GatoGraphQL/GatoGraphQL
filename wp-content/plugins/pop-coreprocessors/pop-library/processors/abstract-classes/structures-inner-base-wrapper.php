<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Configuration
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_StructureInnersBaseWrapper extends GD_Template_ProcessorBaseWrapper {

	function get_layouts($template_id) {
	
		return $this->processor->get_layouts($template_id);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Settings Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
global $gd_template_processorwrapper_manager;
$gd_template_processorwrapper_manager->add('GD_Template_Processor_StructureInnersBase', 'GD_Template_Processor_StructureInnersBaseWrapper');