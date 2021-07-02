<?php

class PoP_DynamicJSResourceLoaderProcessor extends PoP_JSResourceLoaderProcessor {

	use PoP_DynamicResourceLoaderProcessorTrait;

	function extractMapping(array $resource) {
	
		return false;
	}
	
	// function canBundle(array $resource) {
	
	// 	return false;
	// }
}
