<?php
class PoP_Frontend_ResourceLoaderMappingGenerator extends PoP_Engine_FileLocationBase {

	function get_dir() {

		return POP_FRONTENDENGINE_BUILD_DIR;
	}

	function get_filename() {

		return 'resourceloader-mapping.json';
	}

	public function generate() {

		global $pop_jsresourceloaderprocessor_manager, $pop_frontend_resourceloader_mappingparser;

		// Reset the inner variable to empty, to generate it once again
		$mapping = array();

		// Get all the .js files from all the plugins
		$resources = array_filter(apply_filters('PoP_Frontend_ResourceLoaderMappingManager:resources', array()));
		$jsObjects = array();

		$fileContents = '';
		foreach ($resources as $resource) {

			$file = $pop_jsresourceloaderprocessor_manager->get_asset_path($resource);
			$jsObjects = array_merge(
				$jsObjects,
				$pop_jsresourceloaderprocessor_manager->get_jsobjects($resource)
			);
			$fileContents .= file_get_contents($file).PHP_EOL;
		}

		$mapping = $pop_frontend_resourceloader_mappingparser->extract($fileContents, $jsObjects);

		global $pop_engine_filejsonstorage;
		$pop_engine_filejsonstorage->save($this->get_filepath(), $mapping);
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
global $pop_frontend_resourceloader_mappinggenerator;
$pop_frontend_resourceloader_mappinggenerator = new PoP_Frontend_ResourceLoaderMappingGenerator();