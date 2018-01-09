<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Core (reusable) functions of the website
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoP_ResourceLoader_BundleMappingStorageManager {

	public $entries, $initialized;

	function __construct() {

		$this->entries = array();
		$this->initialized = false;
	}

	function init() {

		// Allows lazy init
		if (!$this->initialized) {

			$this->initialized = true;

			// The DB was created in STAGING when running /generate-theme/, so make sure it is copied to PROD during deployment
			if (PoP_Frontend_ServerUtils::use_generatetheme_output_files()) {

				// Get the inner variable from the cache, if it exists
				global $pop_engine_filejsonstorage, $pop_resourceloader_bundlemappingstoragefilegenerator;
				$this->entries = $pop_engine_filejsonstorage->get($pop_resourceloader_bundlemappingstoragefilegenerator->get_filepath());
			}
		}
	}

	public function save($bundle_ids, $bundlegroup_ids, $key_ids) {

		global $pop_resourceloader_bundlemappingstoragefilegenerator;
		$pop_resourceloader_bundlemappingstoragefilegenerator->save($bundle_ids, $bundlegroup_ids, $key_ids);
	}

	public function delete() {

		global $pop_resourceloader_bundlemappingstoragefilegenerator;
		$pop_resourceloader_bundlemappingstoragefilegenerator->delete();
	}

	public function has_cached_entries() {

		// Lazy init
		$this->init();
		return !empty($this->entries);
	}

	public function get_bundle_ids() {

		// Lazy init
		$this->init();
		return $this->entries['bundle-ids'];
	}

	public function get_bundlegroup_ids() {

		// Lazy init
		$this->init();
		return $this->entries['bundlegroup-ids'];
	}

	public function get_key_ids() {

		// Lazy init
		$this->init();
		return $this->entries['key-ids'];
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
global $pop_resourceloader_bundlemappingstoragemanager;
$pop_resourceloader_bundlemappingstoragemanager = new PoP_ResourceLoader_BundleMappingStorageManager();
