<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Configuration
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoP_VendorCSSResourceLoaderProcessor extends PoP_CSSResourceLoaderProcessor {

	function can_bundle($resource) {
	
		// Decide by configuration if to bundle external resources
		return PoP_Frontend_ServerUtils::bundle_external_files();
	}
		
	function get_subtype($resource) {
	
		return POP_RESOURCELOADER_RESOURCESUBTYPE_VENDOR;
	}

	function get_suffix($resource) {

		if ($this->always_minified($resource)) {
	
			return '.min.css';
		}

		return parent::get_suffix($resource);
	}

	protected function always_minified($resource) {
	
		return true;
	}
}