<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Core (reusable) functions of the website
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoP_ResourceLoader_AbbreviationsStorageManager {

	public $abbreviations, $initialized;

	function __construct() {

		$this->abbreviations = array();
		$this->initialized = false;
	}

	function init() {

		// Allows lazy init
		if (!$this->initialized) {

			$this->initialized = true;

			// Get the inner variable from the cache, if it exists
			global $pop_frontend_filejsonstorage, $pop_resourceloader_abbreviationsstorage_generator;
			$this->abbreviations = $pop_frontend_filejsonstorage->get($pop_resourceloader_abbreviationsstorage_generator->get_filepath());
		}
	}

	public function save($bundle_ids, $bundlegroup_ids, $key_ids) {

		global $pop_resourceloader_abbreviationsstorage_generator;
		$pop_resourceloader_abbreviationsstorage_generator->save($bundle_ids, $bundlegroup_ids, $key_ids);
	}

	public function delete() {

		global $pop_resourceloader_abbreviationsstorage_generator;
		$pop_resourceloader_abbreviationsstorage_generator->delete();
	}

	public function has_cached_abbreviations() {

		// Lazy init
		$this->init();
		return !empty($this->abbreviations);
	}

	public function get_bundle_ids() {

		// Lazy init
		$this->init();
		return $this->abbreviations['bundle-ids'];
	}

	public function get_bundlegroup_ids() {

		// Lazy init
		$this->init();
		return $this->abbreviations['bundlegroup-ids'];
	}

	public function get_key_ids() {

		// Lazy init
		$this->init();
		return $this->abbreviations['key-ids'];
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
global $pop_resourceloader_abbreviationsstorage_manager;
$pop_resourceloader_abbreviationsstorage_manager = new PoP_ResourceLoader_AbbreviationsStorageManager();
