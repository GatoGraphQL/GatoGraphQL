<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Configuration
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_FilterInnersBaseWrapper extends GD_Template_ProcessorBaseWrapper {

	function get_filter_object($template_id) {

		return $this->processor->get_filter_object($template_id);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Settings Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
global $gd_template_processorwrapper_manager;
$gd_template_processorwrapper_manager->add('GD_Template_Processor_FilterInnersBase', 'GD_Template_Processor_FilterInnersBaseWrapper');