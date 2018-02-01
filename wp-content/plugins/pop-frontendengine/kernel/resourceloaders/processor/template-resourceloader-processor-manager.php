<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoP_TemplateResourceLoaderProcessor_Manager/* extends PoP_JSResourceLoaderProcessor_Manager*/ {

	var $templatesource_resources;
	
	function __construct() {

		// parent::__construct();
	
		$this->templatesource_resources = array();
	}
	
	function get_resource($template_source) {

		$resource = $this->templatesource_resources[$template_source];
		if (!$resource) {
			throw new Exception(sprintf('No Resource for $template_source \'%s\' (%s)', $template_source, full_url()));
		}
	
		return $resource;
	}
	
	function add($processor, $resources_to_process) {

		// parent::add($processor, $resources_to_process);
	
		foreach ($resources_to_process as $resource) {
		
			$this->templatesource_resources[$processor->get_filename($resource)] = $resource;
		}	
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
global $pop_templateresourceloaderprocessor_manager;
$pop_templateresourceloaderprocessor_manager = new PoP_TemplateResourceLoaderProcessor_Manager();
