<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Configuration
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoP_DynamicCSSResourceLoaderProcessor extends PoP_CSSResourceLoaderProcessor {

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
}