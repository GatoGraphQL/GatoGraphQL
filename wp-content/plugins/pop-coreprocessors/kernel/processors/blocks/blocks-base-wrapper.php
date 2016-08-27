<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Configuration
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_BlocksBaseWrapper extends PoPFrontend_Processor_BlocksBaseWrapper {

	function get_submenu($template_id) {

		return $this->processor->get_submenu($template_id);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Settings Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
global $gd_template_processorwrapper_manager;
$gd_template_processorwrapper_manager->add('GD_Template_Processor_BlocksBase', 'GD_Template_Processor_BlocksBaseWrapper');
