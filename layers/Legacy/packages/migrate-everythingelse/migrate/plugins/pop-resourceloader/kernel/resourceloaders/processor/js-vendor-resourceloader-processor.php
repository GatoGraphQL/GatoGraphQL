<?php

class PoP_VendorJSResourceLoaderProcessor extends PoP_JSResourceLoaderProcessor {

	function canBundle(array $resource) {
	
		// Decide by configuration if to bundle external resources
		return PoP_ResourceLoader_ServerUtils::bundleExternalFiles();
	}
		
	function extractMapping(array $resource) {
	
		return false;
	}
	
	function getSubtype(array $resource) {
	
		return POP_RESOURCELOADER_RESOURCESUBTYPE_VENDOR;
	}
}
