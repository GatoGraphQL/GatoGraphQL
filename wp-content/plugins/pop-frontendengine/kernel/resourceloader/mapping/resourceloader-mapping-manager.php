<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Core (reusable) functions of the website
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoP_Frontend_ResourceLoaderMappingManager {

	protected $mapping, $initialized;

	function __construct() {

		$this->mapping = array();
		$this->initialized = false;
	}

	function init() {

		// Allows lazy init
		if (!$this->initialized) {

			$this->initialized = true;

			// Get the inner variable from the cache, if it exists
			global $pop_frontend_filejsonstorage, $pop_frontend_resourceloader_mappinggenerator;
			$this->mapping = $pop_frontend_filejsonstorage->get($pop_frontend_resourceloader_mappinggenerator->get_filepath());
		}
	}

	public function get_mapping() {

		$this->init();
		return $this->mapping;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
global $pop_frontend_resourceloader_mappingmanager;
$pop_frontend_resourceloader_mappingmanager = new PoP_Frontend_ResourceLoaderMappingManager();
