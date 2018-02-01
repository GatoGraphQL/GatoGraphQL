<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Configuration
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoP_DynamicJSResourceLoaderProcessor extends PoP_JSResourceLoaderProcessor {

	function extract_mapping($resource) {
	
		return false;
	}
	
	function get_subtype($resource) {
	
		return POP_RESOURCELOADER_RESOURCESUBTYPE_DYNAMIC;
	}
	
	function get_version($resource) {

		// This script file is dynamically generated getting data from all over the website, so its version depend on the website version
		return pop_version();
	}
	
	function get_suffix($resource) {
	
		// This script file is dynamically generated getting data from all over the website, so its version depend on the website version
		return '';
	}
	
	// function can_bundle($resource) {
	
	// 	return false;
	// }
}