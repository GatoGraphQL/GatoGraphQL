<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Configuration
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_PostSelectableTypeaheadFormComponentsBaseWrapper extends GD_Template_Processor_SelectableTypeaheadFormComponentsBaseWrapper {

	function get_selected_layout_template($template_id) {

		return $this->processor->get_selected_layout_template($template_id);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Settings Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
global $gd_template_processorwrapper_manager;
$gd_template_processorwrapper_manager->add('GD_Template_Processor_PostSelectableTypeaheadFormComponentsBase', 'GD_Template_Processor_PostSelectableTypeaheadFormComponentsBaseWrapper');