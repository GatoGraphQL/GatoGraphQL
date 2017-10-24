<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoP_TemplateResourceLoaderProcessor_Manager {

	var $templatesource_resources, $extensions;
	
	function __construct() {
	
		$this->templatesource_resources = array();
		// $this->extensions = array();
	}
	
	function get_resource($template_source) {

		$resource = $this->templatesource_resources[$template_source];
		if (!$resource) {
			throw new Exception(sprintf('No Resource for $template_source \'%s\' (%s)', $template_source, full_url()));
		}
	
		return $resource;
	}
	
	// function get_extensions() {

	// 	return $this->extensions;
	// }
	
	function add($processor, $resources_to_process) {
	
		foreach ($resources_to_process as $resource) {
		
			$this->templatesource_resources[$processor->get_filename($resource)] = $resource;

			// // If it is an extension, add it to the array
			// if ($processor->is_extension($resource)) {
			// 	$this->extensions[] = $resource;
			// }
		}	
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
global $pop_templateresourceloaderprocessor_manager;
$pop_templateresourceloaderprocessor_manager = new PoP_TemplateResourceLoaderProcessor_Manager();
