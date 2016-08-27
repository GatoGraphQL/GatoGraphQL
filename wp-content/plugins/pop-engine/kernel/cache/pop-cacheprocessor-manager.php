<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_CacheProcessor_Manager {

	var $processors;
	
	function __construct() {
	
		$this->processors = array();
	}
	
	function get_processor($template_id) {
	
		return $this->processors[$template_id];
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
global $gd_template_cacheprocessor_manager;
$gd_template_cacheprocessor_manager = new GD_Template_CacheProcessor_Manager();
