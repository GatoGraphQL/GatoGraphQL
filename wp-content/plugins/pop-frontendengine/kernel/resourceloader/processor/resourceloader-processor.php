<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Configuration
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoP_ResourceLoaderProcessor {

	function __construct() {

		add_action('init', array($this, 'init'));
	}

	function init() {

		global $pop_resourceloaderprocessor_manager;
		$pop_resourceloaderprocessor_manager->add($this, $this->get_resources_to_process());
	}
	
	function get_resources_to_process() {
	
		return array();
	}
		
	function extract_mapping($resource) {
	
		// By default, extract the mapping if its directory has been defined
		return ($this->get_dir($resource) != '');
	}
	
	function get_version($resource) {
	
		return ''; // pop_version();
	}
	
	function get_path($resource) {
	
		return '';
	}
	
	function get_dir($resource) {
	
		return '';
	}
	
	function get_suffix($resource) {
	
		return (PoP_Frontend_ServerUtils::use_minified_resources() ? '.min' : '').'.js';
	}
	
	function get_filename($resource) {
	
		return $resource;
	}
	
	function get_filename_ext($resource) {
	
		return $this->get_filename($resource).$this->get_suffix($resource);
	}
	
	function get_file_url($resource) {
	
		return $this->get_path($resource).'/'.$this->get_filename_ext($resource);
	}
	
	function get_file_path($resource) {
	
		return $this->get_dir($resource).'/'.$this->get_filename_ext($resource);
	}

	function get_jsobjects($resource) {

		return array();
	}
	
	function get_dependencies($resource) {

		$dependencies = array();

		// Pretty much everything depends on the JS Library Manager (to register their public methods), so add it already here
		if ($resource != POP_RESOURCELOADER_JSLIBRARYMANAGER) {
	
			$dependencies[] = POP_RESOURCELOADER_JSLIBRARYMANAGER;
		}

		return $dependencies;
	}
	
	function get_globalscope_method_calls($resource) {
	
		return array();
	}
	
	// function get_external_method_calls($resource) {
	
	// 	return array();
	// }
	
	// function get_internal_method_calls($resource) {
	
	// 	return array();
	// }
	
	function get_public_methods($resource) {
	
		return array();
	}
	
	function get_htmltag_attributes($resource) {

		return '';
	}
	
	function async_load_in_order($resource) {

		return false;
	}
}