<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Core (reusable) functions of the website
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoP_ResourceLoader_GeneratedResourcesManager {

	public $generatedfiles, $initialized;

	function __construct() {

		$this->reset();
		$this->initialized = false;
	}

	protected function reset() {

		$this->generatedfiles = array(
			'js-bundlegroup-id-by-loading-type' => array(),
			'js-bundle-ids-by-loading-type' => array(),
			'js-resources-by-loading-type' => array(),
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

			// The DB was created in STAGING when running /generate-theme/, so make sure it is copied to PROD during deployment
			if (PoP_Frontend_ServerUtils::use_generatetheme_output_files()) {

				// Get the inner variable from the cache, if it exists
				global $pop_engine_filejsonstorage, $pop_resourceloader_generatedfilesfilelocation;
				$this->generatedfiles = $pop_engine_filejsonstorage->get($pop_resourceloader_generatedfilesfilelocation->get_filepath());
			}
		}
	}

	public function maybe_save() {

		if ($this->modified) {
			$this->save();
		}
	}

	public function save() {

		global $pop_resourceloader_generatedfilesfilelocation;
		$pop_resourceloader_generatedfilesfilelocation->save($this->generatedfiles);
	}

	public function delete() {

		$this->reset();
		
		global $pop_resourceloader_generatedfilesfilelocation;
		$pop_resourceloader_generatedfilesfilelocation->delete();
	}

	public function get_js_bundlegroup_ids($vars_hash_id, $subtype) {

		// Lazy init
		$this->init();
		return $this->generatedfiles['js-bundlegroup-ids'][$vars_hash_id][$subtype];
	}
	public function set_js_bundlegroup_ids($vars_hash_id, $subtype, $bundlegroup_ids) {

		// Lazy init
		$this->init();
		$this->generatedfiles['js-bundlegroup-ids'][$vars_hash_id][$subtype] = $bundlegroup_ids;
		$this->modified = true;
	}
	public function get_js_bundle_ids($vars_hash_id, $subtype) {

		// Lazy init
		$this->init();
		return $this->generatedfiles['js-bundle-ids'][$vars_hash_id][$subtype];
	}
	public function set_js_bundle_ids($vars_hash_id, $subtype, $bundle_ids) {

		// Lazy init
		$this->init();
		$this->generatedfiles['js-bundle-ids'][$vars_hash_id][$subtype] = $bundle_ids;
		$this->modified = true;
	}
	public function get_js_resources($vars_hash_id, $subtype) {

		// Lazy init
		$this->init();
		return $this->generatedfiles['js-resources'][$vars_hash_id][$subtype];
	}
	public function set_js_resources($vars_hash_id, $subtype, $resources) {

		// Lazy init
		$this->init();
		$this->generatedfiles['js-resources'][$vars_hash_id][$subtype] = $resources;
		$this->modified = true;
	}

	public function get_js_bundlegroup_id_by_loading_type($vars_hash_id, $subtype, $loading_type) {

		// Lazy init
		$this->init();
		return $this->generatedfiles['js-bundlegroup-id-by-loading-type'][$vars_hash_id][$subtype][$loading_type];
	}
	public function set_js_bundlegroup_id_by_loading_type($vars_hash_id, $subtype, $loading_type, $bundlegroup_id) {

		// Lazy init
		$this->init();
		$this->generatedfiles['js-bundlegroup-id-by-loading-type'][$vars_hash_id][$subtype][$loading_type] = $bundlegroup_id;
		$this->modified = true;
	}
	public function get_js_bundle_ids_by_loading_type($vars_hash_id, $subtype, $loading_type) {

		// Lazy init
		$this->init();
		return $this->generatedfiles['js-bundle-ids-by-loading-type'][$vars_hash_id][$subtype][$loading_type];
	}
	public function set_js_bundle_ids_by_loading_type($vars_hash_id, $subtype, $loading_type, $bundle_ids) {

		// Lazy init
		$this->init();
		$this->generatedfiles['js-bundle-ids-by-loading-type'][$vars_hash_id][$subtype][$loading_type] = $bundle_ids;
		$this->modified = true;
	}
	public function get_js_resources_by_loading_type($vars_hash_id, $subtype, $loading_type) {

		// Lazy init
		$this->init();
		return $this->generatedfiles['js-resources-by-loading-type'][$vars_hash_id][$subtype][$loading_type];
	}
	public function set_js_resources_by_loading_type($vars_hash_id, $subtype, $loading_type, $resources) {

		// Lazy init
		$this->init();
		$this->generatedfiles['js-resources-by-loading-type'][$vars_hash_id][$subtype][$loading_type] = $resources;
		$this->modified = true;
	}

	public function get_css_bundlegroup_ids($vars_hash_id, $subtype) {

		// Lazy init
		$this->init();
		return $this->generatedfiles['css-bundlegroup-ids'][$vars_hash_id][$subtype];
	}
	public function set_css_bundlegroup_ids($vars_hash_id, $subtype, $bundlegroup_ids) {

		// Lazy init
		$this->init();
		$this->generatedfiles['css-bundlegroup-ids'][$vars_hash_id][$subtype] = $bundlegroup_ids;
		$this->modified = true;
	}
	public function get_css_bundle_ids($vars_hash_id, $subtype) {

		// Lazy init
		$this->init();
		return $this->generatedfiles['css-bundle-ids'][$vars_hash_id][$subtype];
	}
	public function set_css_bundle_ids($vars_hash_id, $subtype, $bundle_ids) {

		// Lazy init
		$this->init();
		$this->generatedfiles['css-bundle-ids'][$vars_hash_id][$subtype] = $bundle_ids;
		$this->modified = true;
	}
	public function get_css_resources($vars_hash_id, $subtype) {

		// Lazy init
		$this->init();
		return $this->generatedfiles['css-resources'][$vars_hash_id][$subtype];
	}
	public function set_css_resources($vars_hash_id, $subtype, $resources) {

		// Lazy init
		$this->init();
		$this->generatedfiles['css-resources'][$vars_hash_id][$subtype] = $resources;
		$this->modified = true;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
global $pop_resourceloader_generatedfilesmanager;
$pop_resourceloader_generatedfilesmanager = new PoP_ResourceLoader_GeneratedResourcesManager();
