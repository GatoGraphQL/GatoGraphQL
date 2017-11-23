<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Configuration
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoP_CSSResourceLoaderProcessor extends PoP_ResourceLoaderProcessor {
	
	function get_type($resource) {
	
		return POP_RESOURCELOADER_RESOURCETYPE_CSS;
	}
	
	function get_suffix($resource) {
	
		return (PoP_Frontend_ServerUtils::use_minified_resources() ? '.min' : '').'.css';
	}
}