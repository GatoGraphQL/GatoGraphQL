<?php
use PoP\FileStore\Facades\JSONFileStoreFacade;

class PoP_WebPlatform_ResourceLoaderMappingGenerator {

	public function generate(\PoP\FileStore\File\AbstractFile $file) {

		global $pop_jsresourceloaderprocessor_manager, $pop_webplatform_resourceloader_mappingparser;

		// Reset the inner variable to empty, to generate it once again
		$mapping = array();

		// Get all the .js files from all the plugins
		$resources = array_filter(\PoP\Root\App::applyFilters('PoP_WebPlatform_ResourceLoaderMappingManager:resources', array()));
		$jsObjects = array();

		$fileContents = '';
		foreach ($resources as $resource) {

			$filePath = $pop_jsresourceloaderprocessor_manager->getAssetPath($resource);
			$jsObjects = array_merge(
				$jsObjects,
				$pop_jsresourceloaderprocessor_manager->getJsobjects($resource)
			);
			$fileContents .= file_get_contents($filePath).PHP_EOL;
		}

		$mapping = $pop_webplatform_resourceloader_mappingparser->extract($fileContents, $jsObjects);

		JSONFileStoreFacade::getInstance()->save($file, $mapping);
	}
}
	
/**
 * Initialize
 */
global $pop_webplatform_resourceloader_mappinggenerator;
$pop_webplatform_resourceloader_mappinggenerator = new PoP_WebPlatform_ResourceLoaderMappingGenerator();
