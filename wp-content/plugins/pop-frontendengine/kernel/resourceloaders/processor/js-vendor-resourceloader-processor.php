<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Configuration
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoP_VendorJSResourceLoaderProcessor extends PoP_JSResourceLoaderProcessor {

	function can_bundle($resource) {
	
		// Decide by configuration if to bundle external resources
		return PoP_Frontend_ServerUtils::bundle_external_files();
	}
		
	function extract_mapping($resource) {
	
		return false;
	}
	
	function get_subtype($resource) {
	
		return POP_RESOURCELOADER_RESOURCESUBTYPE_VENDOR;
	}
}