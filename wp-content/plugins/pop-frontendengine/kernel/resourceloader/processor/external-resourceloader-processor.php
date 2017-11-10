<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Configuration
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoP_ExternalResourceLoaderProcessor extends PoP_ResourceLoaderProcessor {

	function can_bundle($resource) {
	
		// Decide by configuration if to bundle external resources
		return PoP_Frontend_ServerUtils::bundle_external_files();
	}
		
	function extract_mapping($resource) {
	
		return false;
	}
}