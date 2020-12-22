<?php

class PoP_VendorCSSResourceLoaderProcessor extends PoP_CSSResourceLoaderProcessor {

	function canBundle(array $resource) {
	
		// Decide by configuration if to bundle external resources
		return PoP_ResourceLoader_ServerUtils::bundleExternalFiles();
	}
		
	function getSubtype(array $resource) {
	
		return POP_RESOURCELOADER_RESOURCESUBTYPE_VENDOR;
	}

	function getSuffix(array $resource) {

		if ($this->alwaysMinified($resource)) {
	
			return '.min.css';
		}

		return parent::getSuffix($resource);
	}

	protected function alwaysMinified(array $resource) {
	
		return true;
	}
}
