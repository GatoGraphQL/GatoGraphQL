<?php
use PoP\Resources\DefinitionGroups;
use PoP\ComponentModel\State\ApplicationState;

trait PoP_DynamicResourceLoaderProcessorTrait {

	function getSubtype(array $resource) {
	
		return POP_RESOURCELOADER_RESOURCESUBTYPE_DYNAMIC;
	}
	
	function getVersion(array $resource) {

		// This script file is dynamically generated getting data from all over the website, so its version depend on the website version
		$vars = ApplicationState::getVars();
		return $vars['version'];
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
