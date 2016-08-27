<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Configuration
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_TypeaheadMapFormComponentsBaseWrapper extends GD_Template_Processor_FormComponentsBaseWrapper {

	function get_locations_typeahead($template_id) {
	
		return $this->processor->get_locations_typeahead($template_id);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Settings Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
global $gd_template_processorwrapper_manager;
$gd_template_processorwrapper_manager->add('GD_Template_Processor_TypeaheadMapFormComponentsBase', 'GD_Template_Processor_TypeaheadMapFormComponentsBaseWrapper');