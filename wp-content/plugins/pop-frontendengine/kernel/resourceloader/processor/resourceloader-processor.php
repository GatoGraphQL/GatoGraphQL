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
	
		return true;
	}

	function can_bundle($resource) {
	
		// Can add the contents of the resource on the bundle/bundlegroup generated files?
		return true;
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
	
	function get_asset_path($resource) {
	
		// This function is needed to obtain the contents of the file from the local disk, to produce the bundle/bundlegroup files
		// By default, it's just the file path. But for external resources (eg: from CDNs) they may need to override the default value
		// Also, it allows to create 'resourceloader-mapping.json' always from the original file, and not from its minified version
		// (if constant POP_SERVER_USEMINIFIEDRESOURCES is true), over which the process fails
		return $this->get_file_path($resource);
	}

	function get_jsobjects($resource) {

		return array();
	}
	
	function get_dependencies($resource) {

		return array();
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

		if ($this->is_async($resource)) {

			return "async='async'";
		}
		elseif ($this->is_defer($resource)) {

			return "defer='defer'";
		}

		return '';
	}
	
	function is_async($resource) {

		return false;
	}
	
	function is_defer($resource) {

		return false;
	}
	
	function async_load_in_order($resource) {

		return false;
	}
}