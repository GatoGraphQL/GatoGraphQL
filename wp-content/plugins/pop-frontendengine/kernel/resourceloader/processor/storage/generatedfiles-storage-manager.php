<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Core (reusable) functions of the website
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoP_ResourceLoader_GeneratedFilesStorageManager {

	public $generatedfiles, $initialized;

	function __construct() {

		$this->reset();
		$this->initialized = false;
	}

	protected function reset() {

		$this->generatedfiles = array(
			'js-bundlegroup-ids' => array(),
			'js-bundle-ids' => array(),
			'js-resources' => array(),
			'css-bundlegroup-ids' => array(),
			'css-bundle-ids' => array(),
			'css-resources' => array(),
		);
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

		$this->reset();
		
		global $pop_resourceloader_generatedfilesstoragefilegenerator;
		$pop_resourceloader_generatedfilesstoragefilegenerator->delete();
	}

	public function get_js_bundlegroup_ids($cachename) {

		// Lazy init
		$this->init();
		return $this->generatedfiles['js-bundlegroup-ids'][$cachename];
	}
	public function add_js_bundlegroup_ids($cachename, $bundlegroup_ids) {

		// Lazy init
		$this->init();
		$this->generatedfiles['js-bundlegroup-ids'][$cachename] = $bundlegroup_ids;
	}
	public function get_js_bundle_ids($cachename) {

		// Lazy init
		$this->init();
		return $this->generatedfiles['js-bundle-ids'][$cachename];
	}
	public function add_js_bundle_ids($cachename, $bundle_ids) {

		// Lazy init
		$this->init();
		$this->generatedfiles['js-bundle-ids'][$cachename] = $bundle_ids;
	}
	public function get_js_resources($cachename) {

		// Lazy init
		$this->init();
		return $this->generatedfiles['js-resources'][$cachename];
	}
	public function add_js_resources($cachename, $resources) {

		// Lazy init
		$this->init();
		$this->generatedfiles['js-resources'][$cachename] = $resources;
	}

	public function get_css_bundlegroup_ids($cachename) {

		// Lazy init
		$this->init();
		return $this->generatedfiles['css-bundlegroup-ids'][$cachename];
	}
	public function add_css_bundlegroup_ids($cachename, $bundlegroup_ids) {

		// Lazy init
		$this->init();
		$this->generatedfiles['css-bundlegroup-ids'][$cachename] = $bundlegroup_ids;
	}
	public function get_css_bundle_ids($cachename) {

		// Lazy init
		$this->init();
		return $this->generatedfiles['css-bundle-ids'][$cachename];
	}
	public function add_css_bundle_ids($cachename, $bundle_ids) {

		// Lazy init
		$this->init();
		$this->generatedfiles['css-bundle-ids'][$cachename] = $bundle_ids;
	}
	public function get_css_resources($cachename) {

		// Lazy init
		$this->init();
		return $this->generatedfiles['css-resources'][$cachename];
	}
	public function add_css_resources($cachename, $resources) {

		// Lazy init
		$this->init();
		$this->generatedfiles['css-resources'][$cachename] = $resources;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
global $pop_resourceloader_generatedfilesstoragemanager;
$pop_resourceloader_generatedfilesstoragemanager = new PoP_ResourceLoader_GeneratedFilesStorageManager();
