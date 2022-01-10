<?php
use PoP\ComponentModel\Facades\Info\ApplicationInfoFacade;
use PoP\ComponentModel\State\ApplicationState;
use PoP\Definitions\Facades\DefinitionManagerFacade;
use PoP\Resources\DefinitionGroups;

trait PoP_DynamicResourceLoaderProcessorTrait {

	function getSubtype(array $resource) {
	
		return POP_RESOURCELOADER_RESOURCESUBTYPE_DYNAMIC;
	}
	
	function getVersion(array $resource) {

		// This script file is dynamically generated getting data from all over the website, so its version depend on the website version
		return ApplicationInfoFacade::getInstance()->getVersion();
	}
	
	function getSuffix(array $resource) {
	
		// This script file is dynamically generated getting data from all over the website, so its version depend on the website version
		return '';
	}

	function getFilename(array $resource) {
	
		// The filename can be mangled/converted into something else/etc
		return DefinitionManagerFacade::getInstance()->getDefinition($resource, DefinitionGroups::RESOURCES);
	}
}
