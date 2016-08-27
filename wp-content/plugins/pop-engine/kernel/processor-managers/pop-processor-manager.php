<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_Manager {

	var $processors;
	
	function __construct() {
	
		$this->processors = array();
	}
	
	function get_processor($template_id) {
	
		$processor = $this->processors[$template_id];

		// Get the wrapper, there will always be one since there's s a wrapper for GD_Template_ProcessorBase
		global $gd_template_processorwrapper_manager;
		return $gd_template_processorwrapper_manager->get_processorwrapper($processor);

		// return $processor;
	}
	
	function add($processor, $templates_to_process) {
	
		foreach ($templates_to_process as $template) {
		
			$this->processors[$template] = $processor;
		}	
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
global $gd_template_processor_manager;
$gd_template_processor_manager = new GD_Template_Processor_Manager();
