<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Configuration
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_SelectableTypeaheadFormComponentsBaseWrapper extends GD_Template_Processor_FormComponentsBaseWrapper {

	function get_trigger_template($template_id) {

		return $this->processor->get_trigger_template($template_id);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Settings Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
global $gd_template_processorwrapper_manager;
$gd_template_processorwrapper_manager->add('GD_Template_Processor_SelectableTypeaheadFormComponentsBase', 'GD_Template_Processor_SelectableTypeaheadFormComponentsBaseWrapper');