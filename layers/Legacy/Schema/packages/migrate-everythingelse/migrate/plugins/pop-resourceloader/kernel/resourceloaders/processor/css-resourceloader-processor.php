<?php

class PoP_CSSResourceLoaderProcessor extends PoP_ResourceLoaderProcessor {
	
	function getType(array $resource) {
	
		return POP_RESOURCELOADER_RESOURCETYPE_CSS;
	}
	
	function getSuffix(array $resource) {
	
		return (PoP_WebPlatform_ServerUtils::useMinifiedResources() ? '.min' : '').'.css';
	}
}
