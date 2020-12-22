<?php
use PoP\Hooks\Facades\HooksAPIFacade;

class PoP_ResourceLoaderProcessor {

	function getResourcesToProcess() {
	
		return array();
	}

	// function inBody(array $resource) {
	
	// 	// Is it added in the body instead of through wp_enqueue_script/style?
	// 	return false;
	// }
	
	function getHandle(array $resource) {
	
		return PoP_ResourceLoaderProcessorUtils::getNoconflictResourceName($resource[1]);
	}

	function inline(array $resource) {
	
		// Directly add the contents of the file to the HTML output, instead of including it from a style/script tag
		return false;
	}

	function canBundle(array $resource) {

		// If the resource must be inlined then it cannot be bundled
		if ($this->inline($resource)) {

			return false;
		}
	
		// Can add the contents of the resource on the bundle/bundlegroup generated files?
		return true;
	}
	
	function getVersion(array $resource) {
	
		return '';
	}
	
	function getPath(array $resource) {
	
		return '';
	}
	
	function getDir(array $resource) {
	
		return '';
	}
	
	function getSuffix(array $resource) {
	
		return '';
	}
	
	function getType(array $resource) {
	
		return '';
	}
	
	function getSubtype(array $resource) {
	
		return POP_RESOURCELOADER_RESOURCESUBTYPE_NORMAL;
	}
	
	function getFilename(array $resource) {
	
		return $resource;
	}
	
	function getFilenameExt(array $resource) {
	
		return $this->getFilename($resource).$this->getSuffix($resource);
	}
	
	function getFileUrl(array $resource) {
	
		return $this->getPath($resource).'/'.$this->getFilenameExt($resource);
	}
	
	function getFilePath(array $resource) {
	
		return $this->getDir($resource).'/'.$this->getFilenameExt($resource);
	}
	
	function getAssetPath(array $resource) {
	
		// This function is needed to obtain the contents of the file from the local disk, to produce the bundle/bundlegroup files
		// By default, it's just the file path. But for external resources (eg: from CDNs) they may need to override the default value
		// Also, it allows to create 'resourceloader-mapping.json' always from the original file, and not from its minified version
		// (if constant USE_MINIFIED_RESOURCES is true), over which the process fails
		return $this->getFilePath($resource);
	}
	
	// function getHtmltagAttributes(array $resource) {

	// 	return '';
	// }
	
	function getReferencedFiles(array $resource) {

		// Return an array of relative paths to the referenced files
		return array();
	}
	
	function getDependencies(array $resource) {

		return array();
	}
	
	function getDecoratedResources(array $resource) {

		// This function allows to load files 'mediamanager.js' and 'mediamanager-cors.js' without adding them as dependencies from the featuredimage.js and editor.js files
		// Indeed they are not dependencies, however any of the latter 2 is loaded, the former 2 must also be loaded since they decorate (i.e. add functionality to) them
		// They also allow having logic in function `documentInitializedIndependent`, since otherwise these 2 files have no other JS methods to be mapped to, so they would not be loaded in first place 
		return array();
	}
	
	function getDecorators(array $resource) {

		global $pop_resourceloaderprocessor_manager;

		// Return all resources which are decorating the given resource
		return $pop_resourceloaderprocessor_manager->getDecorators($resource);
	}
	
	// function getDependenciesAndDecorators(array $resource) {

	// 	return array_merge(
	// 		$this->getDependencies($resource),
	// 		$this->getDecorators($resource)
	// 	);
	// }
}
