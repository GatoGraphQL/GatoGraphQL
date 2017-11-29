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

	// protected function get_manager() {

	// 	global $pop_resourceloaderprocessor_manager;
	// 	return $pop_resourceloaderprocessor_manager;
	// }

	function init() {

		// $this->get_manager()->add($this, $this->get_resources_to_process());
		global $pop_resourceloaderprocessor_manager;
		$pop_resourceloaderprocessor_manager->add($this, $this->get_resources_to_process());
	}
	
	function get_resources_to_process() {
	
		return array();
	}
		
	function extract_mapping($resource) {
	
		return true;
	}

	// function in_body($resource) {
	
	// 	// Is it added in the body instead of through wp_enqueue_script/style?
	// 	return false;
	// }

	function inline($resource) {
	
		// Directly add the contents of the file to the HTML output, instead of including it from a style/script tag
		return false;
	}

	function can_bundle($resource) {

		// // If the resource must be inlined or it goes in the body, then it cannot be bundled
		// if ($this->inline($resource)/* || $this->in_body($resource)*/) {

		// 	return false;
		// }
	
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
	
		return '';
	}
	
	function get_type($resource) {
	
		// $suffix = $this->get_suffix($resource);

		// if (in_array($suffix, array('.js', '.min.js')) {

		// 	return POP_RESOURCELOADER_RESOURCETYPE_JS;
		// }
		// elseif (in_array($suffix, array('.css', '.min.css')) {

		// 	return POP_RESOURCELOADER_RESOURCETYPE_CSS;
		// }

		return '';
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
	
	// function get_htmltag_attributes($resource) {

	// 	return '';
	// }
	
	function get_dependencies($resource) {

		return array();
	}
	
	function get_decorated_resources($resource) {

		// This function allows to load files 'mediamanager.js' and 'mediamanager-cors.js' without adding them as dependencies from the featuredimage.js and editor.js files
		// Indeed they are not dependencies, however any of the latter 2 is loaded, the former 2 must also be loaded since they decorate (i.e. add functionality to) them
		// They also allow having logic in function `documentInitializedIndependent`, since otherwise these 2 files have no other JS methods to be mapped to, so they would not be loaded in first place 
		return array();
	}
	
	function get_decorators($resource) {

		global $pop_resourceloaderprocessor_manager;

		// Return all resources which are decorating the given resource
		return $pop_resourceloaderprocessor_manager->get_decorators($resource);
	}
	
	// function get_dependencies_and_decorators($resource) {

	// 	return array_merge(
	// 		$this->get_dependencies($resource),
	// 		$this->get_decorators($resource)
	// 	);
	// }
}