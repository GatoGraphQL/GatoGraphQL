<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_ProcessorWrapper_Manager {

	var $settings, $processorwrappers;
	
	function __construct() {
	
		$this->settings = array();
		$this->processorwrappers = array();
	}
	
	function get_processorwrapper($processor) {

		$processorwrapper = null;

		// If there's already a processor_wrapper for this template_id, then return it
		$processor_classname = get_class($processor);
		$processorwrapper = $this->processorwrappers[$processor_classname];

		// If not, build a new one from the settings, and assign it under the current processor
		if (!$processorwrapper) {

			do {
				if ($processorwrapper_classname = $this->settings[$processor_classname]) {

					$processorwrapper = new $processorwrapper_classname($processor);
					$this->processorwrappers[$processor_classname] = $processorwrapper;	
					break;
				}
			} while($processor_classname = get_parent_class($processor_classname));
		}

		return $processorwrapper;
	}
	
	function add($processor_classname, $processorwrapper_classname) {

		$this->settings[$processor_classname] = $processorwrapper_classname;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
global $gd_template_processorwrapper_manager;
$gd_template_processorwrapper_manager = new GD_Template_ProcessorWrapper_Manager();
