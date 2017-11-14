<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Core (reusable) functions of the website
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoP_ResourceLoader_GeneratedFilesStorageManager {

	public $generatedfiles, $initialized;

	function __construct() {

		$this->generatedfiles = array(
			'bundlegroup-ids' => array(),
			'bundle-ids' => array(),
			'resources' => array(),
		);
		$this->initialized = false;
	}

	function init() {

		// Allows lazy init
		if (!$this->initialized) {

			$this->initialized = true;

			// Get the inner variable from the cache, if it exists
			global $pop_engine_filejsonstorage, $pop_resourceloader_generatedfilesstoragefilegenerator;
			$this->generatedfiles = $pop_engine_filejsonstorage->get($pop_resourceloader_generatedfilesstoragefilegenerator->get_filepath());
		}
	}

	public function save() {

		global $pop_resourceloader_generatedfilesstoragefilegenerator;
		$pop_resourceloader_generatedfilesstoragefilegenerator->save($this->generatedfiles);
	}

	public function delete() {

		$this->generatedfiles = array(
			'bundlegroup-ids' => array(),
			'bundle-ids' => array(),
			'resources' => array(),
		);

		global $pop_resourceloader_generatedfilesstoragefilegenerator;
		$pop_resourceloader_generatedfilesstoragefilegenerator->delete();
	}

	public function get_bundlegroup_ids($cachename) {

		// Lazy init
		$this->init();
		return $this->generatedfiles['bundlegroup-ids'][$cachename];
	}

	public function add_bundlegroup_ids($cachename, $bundlegroup_ids) {

		// Lazy init
		$this->init();
		$this->generatedfiles['bundlegroup-ids'][$cachename] = $bundlegroup_ids;
	}

	public function get_bundle_ids($cachename) {

		// Lazy init
		$this->init();
		return $this->generatedfiles['bundle-ids'][$cachename];
	}

	public function add_bundle_ids($cachename, $bundle_ids) {

		// Lazy init
		$this->init();
		$this->generatedfiles['bundle-ids'][$cachename] = $bundle_ids;
	}

	public function get_resources($cachename) {

		// Lazy init
		$this->init();
		return $this->generatedfiles['resources'][$cachename];
	}

	public function add_resources($cachename, $resources) {

		// Lazy init
		$this->init();
		$this->generatedfiles['resources'][$cachename] = $resources;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
global $pop_resourceloader_generatedfilesstoragemanager;
$pop_resourceloader_generatedfilesstoragemanager = new PoP_ResourceLoader_GeneratedFilesStorageManager();
